<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Validator;
use App\Models\Absensi;
use App\Models\Employee;
use App\Models\FaceIdentity;
use App\Services\AttendanceSyncService;

class FaceController extends Controller
{
    /**
     * ENROLL WAJAH
     * Admin/Client -> Laravel -> FastAPI (encode) -> simpan embedding
     */
    public function enrollFace(Request $request)
    {
        $name = trim((string) $request->input('name', ''));
        $images = $this->collectImages($request);

        if ($name === '' || empty($images)) {
            return response()->json([
                'success' => false,
                'status' => 'failed',
                'message' => 'Name dan image wajib diisi.'
            ], 400);
        }

        $fastapiBase = trim((string) config('attendance.fastapi_url', env('FASTAPI_URL', '')));
        if ($fastapiBase === '') {
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => 'FASTAPI_URL belum diset.',
            ], 500);
        }

        $slug = $this->slugPerson($name);
        $employee = $this->findEmployeeByFaceName($name);
        $results = [];
        $errors = [];

        foreach ($images as $file) {
            $resp = $this->sendEnrollToFastApi($name, $file, $fastapiBase);
            if (! $resp['success']) {
                $errors[] = $resp['error'];
                continue;
            }

            $photoPath = $this->storeFacePhoto($file, $slug);
            $results[] = [
                'person' => $resp['person'],
                'saved' => $resp['saved'],
                'photo' => $photoPath,
            ];
        }

        if (! empty($results)) {
            $this->upsertFaceIdentity($name, $employee);
        }

        if (! empty($errors)) {
            return response()->json([
                'success' => false,
                'status' => 'failed',
                'message' => implode(' | ', array_unique($errors)),
                'uploaded' => count($results),
            ], 400);
        }

        return response()->json([
            'success' => true,
            'status' => 'success',
            'uploaded' => count($results),
            'results' => $results,
        ], 200);
    }

    /**
     * JALUR LAMA
     * Flutter -> (upload foto) -> Laravel -> FastAPI -> simpan DB
     */
    public function verifyFace(Request $request)
    {
        if (!$request->hasFile('image')) {
            return response()->json(['success' => false, 'status' => 'failed', 'message' => 'No image'], 400);
        }
        $file = $request->file('image');

        // Jam kerja
        $workStart = config('attendance.work_start', env('WORK_START', '10:00'));
        $workEnd   = config('attendance.work_end',   env('WORK_END',   '16:00'));
        $grace     = (int) config('attendance.grace_minutes', env('GRACE_MINUTES', 0));

        $now = now();
        $startCut = (clone $now)->setTimeFromTimeString($workStart)->addMinutes($grace);
        $endCut   = (clone $now)->setTimeFromTimeString($workEnd)->subMinutes($grace);

        try {
            $fastapiBase = trim((string) config('attendance.fastapi_url', env('FASTAPI_URL', '')));
            $fastapiUrl = rtrim($fastapiBase, '/') . '/verify-face';
            $headers = $this->fastApiHeaders($fastapiBase);

            // Kirim pakai stream + timeout lebih longgar
            $stream = fopen($file->getRealPath(), 'r');
            $resp = Http::withHeaders($headers)
                ->connectTimeout(5)
                ->timeout(45)
                ->attach('image', $stream, $file->getClientOriginalName(), [
                    'Content-Type' => $file->getMimeType() ?: 'image/jpeg'
                ])
                ->post($fastapiUrl);
            if (is_resource($stream)) fclose($stream);

            $json = $resp->json();

            if (!$resp->successful() || !($json['success'] ?? false)) {
                return response()->json([
                    'success' => false,
                    'status'  => 'failed',
                    'reason'  => $json['message'] ?? 'Wajah tidak dikenali',
                    'fastapi_status' => $resp->status(),
                    'fastapi_raw'    => $resp->body(),
                ], 400);
            }

            // === GUARD: block Unknown/kosong ===
            $rawName = trim((string) ($json['user'] ?? ''));
            if ($rawName === '' || strcasecmp($rawName, 'unknown') === 0) {
                return response()->json([
                    'success' => false,
                    'status'  => 'failed',
                    'message' => 'Wajah belum dikenali (Unknown). Tidak disimpan.'
                ], 422);
            }

            $identity = $this->findFaceIdentity($rawName);
            $employee = $this->resolveEmployeeFromFace($rawName, $identity);

            if ($employee) {
                $name = $employee->full_name;
            } elseif ($identity) {
                $name = $identity->name ?: $this->formatFaceName($rawName);
            } else {
                return response()->json([
                    'success' => false,
                    'status'  => 'failed',
                    'message' => 'Wajah terdeteksi tetapi belum terdaftar sebagai pegawai.'
                ], 422);
            }
            // ===================================

            $day  = $now->toDateString();

            // Satu baris per (name, day)
            $row = Absensi::query()
                ->where('day', $day)
                ->where(function ($query) use ($name, $rawName) {
                    $query->where('name', $name);
                    if ($rawName !== $name) {
                        $query->orWhere('name', $rawName);
                    }
                })
                ->first();

            if (! $row) {
                $row = Absensi::create([
                    'name' => $name,
                    'day' => $day,
                    'time' => now(),
                ]);
            } elseif ($row->name !== $name) {
                $row->name = $name;
            }

            $type  = strtoupper($request->input('type', '')); // IN / OUT
            $phase = null;

            if ($type === 'IN') {
                if (!is_null($row->check_in_time)) {
                    return response()->json([
                        'success' => true,
                        'status'  => 'already',
                        'message' => 'Sudah check-in',
                        'data'    => $row
                    ], 200);
                }
                $row->check_in_time = $now->format('H:i:s');

                // hanya isi status kalau kosong (jangan timpa admin override)
                if (empty($row->check_in_status)) {
                    $row->check_in_status = $now->lessThanOrEqualTo($startCut) ? 'On Time' : 'Late';
                }
                $phase = 'IN';

            } elseif ($type === 'OUT') {
                if (is_null($row->check_in_time)) {
                    return response()->json([
                        'success' => false,
                        'status'  => 'failed',
                        'message' => 'Belum check-in. Silakan check-in terlebih dahulu.'
                    ], 400);
                }
                if (!is_null($row->check_out_time)) {
                    return response()->json([
                        'success' => true,
                        'status'  => 'already',
                        'message' => 'Sudah check-out',
                        'data'    => $row
                    ], 200);
                }
                $row->check_out_time = $now->format('H:i:s');

                if (empty($row->check_out_status)) {
                    $row->check_out_status = $now->greaterThanOrEqualTo($endCut) ? 'On Time' : 'Early';
                }
                $phase = 'OUT';

            } else {
                // fallback otomatis
                if (is_null($row->check_in_time)) {
                    $row->check_in_time = $now->format('H:i:s');
                    if (empty($row->check_in_status)) {
                        $row->check_in_status = $now->lessThanOrEqualTo($startCut) ? 'On Time' : 'Late';
                    }
                    $phase = 'IN';
                } elseif (is_null($row->check_out_time)) {
                    $row->check_out_time = $now->format('H:i:s');
                    if (empty($row->check_out_status)) {
                        $row->check_out_status = $now->greaterThanOrEqualTo($endCut) ? 'On Time' : 'Early';
                    }
                    $phase = 'OUT';
                } else {
                    return response()->json([
                        'success' => true,
                        'status'  => 'already',
                        'message' => 'Absen hari ini sudah lengkap (IN & OUT).',
                        'data'    => $row
                    ], 200);
                }
            }

            if (isset($json['distance'])) $row->distance = (float) $json['distance'];
            if (isset($json['gap']))      $row->gap      = (float) $json['gap'];

            $row->save();
            try {
                app(AttendanceSyncService::class)->syncAttendanceRecordFromAbsensi($row, $employee);
            } catch (\Throwable $e) {
            }

            return response()->json([
                'success' => true,
                'status'  => 'success',
                'phase'   => $phase,
                'data'    => $row,
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * JALUR BARU
     * Flutter -> FastAPI -> Flutter -> Laravel JSON -> DB
     */
    public function storeFromFastapiJson(Request $r)
    {
        try {
            // === GUARD: block Unknown/kosong ===
            $rawName = trim((string) $r->input('name', ''));
            if ($rawName === '' || strcasecmp($rawName, 'unknown') === 0) {
                return response()->json([
                    'success' => false,
                    'status'  => 'failed',
                    'message' => 'Wajah belum dikenali (Unknown). Tidak disimpan.'
                ], 422);
            }

            $identity = $this->findFaceIdentity($rawName);
            $employee = $this->resolveEmployeeFromFace($rawName, $identity);

            if ($employee) {
                $name = $employee->full_name;
            } elseif ($identity) {
                $name = $identity->name ?: $this->formatFaceName($rawName);
            } else {
                return response()->json([
                    'success' => false,
                    'status'  => 'failed',
                    'message' => 'Wajah terdeteksi tetapi belum terdaftar sebagai pegawai.'
                ], 422);
            }
            // ===================================

            $type = strtoupper((string) $r->input('type', ''));
            if (!in_array($type, ['IN', 'OUT'], true)) {
                return response()->json(['success'=>false, 'message'=>'type harus IN/OUT'], 422);
            }

            $distance = $r->input('distance');
            $gap      = $r->input('gap');

            // Jam kerja
            $workStart = config('attendance.work_start', env('WORK_START', '10:00'));
            $workEnd   = config('attendance.work_end',   env('WORK_END',   '16:00'));
            $grace     = (int) config('attendance.grace_minutes', env('GRACE_MINUTES', 0));

            $now = now();
            $day = $now->toDateString();
            $startCut = (clone $now)->setTimeFromTimeString($workStart)->addMinutes($grace);
            $endCut   = (clone $now)->setTimeFromTimeString($workEnd)->subMinutes($grace);

            $row = Absensi::query()
                ->where('day', $day)
                ->where(function ($query) use ($name, $rawName) {
                    $query->where('name', $name);
                    if ($rawName !== $name) {
                        $query->orWhere('name', $rawName);
                    }
                })
                ->first();

            if (! $row) {
                $row = Absensi::create([
                    'name' => $name,
                    'day' => $day,
                    'time' => now(),
                ]);
            } elseif ($row->name !== $name) {
                $row->name = $name;
            }

            if ($type === 'IN') {
                if ($row->check_in_time) {
                    return response()->json(['success'=>true, 'status'=>'already', 'message'=>'Sudah check-in', 'data'=>$row], 200);
                }
                $row->check_in_time = $now->format('H:i:s');

                if (empty($row->check_in_status)) {
                    $row->check_in_status = $now->lessThanOrEqualTo($startCut) ? 'On Time' : 'Late';
                }

            } else { // OUT
                if (!$row->check_in_time) {
                    return response()->json(['success'=>false, 'status'=>'failed', 'message'=>'Belum check-in'], 400);
                }
                if ($row->check_out_time) {
                    return response()->json(['success'=>true, 'status'=>'already', 'message'=>'Sudah check-out', 'data'=>$row], 200);
                }
                $row->check_out_time = $now->format('H:i:s');

                if (empty($row->check_out_status)) {
                    $row->check_out_status = $now->greaterThanOrEqualTo($endCut) ? 'On Time' : 'Early';
                }
            }

            if ($distance !== null) $row->distance = (float) $distance;
            if ($gap !== null)      $row->gap      = (float) $gap;

            $row->save();
            try {
                app(AttendanceSyncService::class)->syncAttendanceRecordFromAbsensi($row, $employee);
            } catch (\Throwable $e) {
            }

            return response()->json([
                'success' => true,
                'status'  => 'success',
                'phase'   => $type,
                'data'    => $row,
            ], 200);

        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    /**
     * WEB ADMIN
     * Kelola enroll wajah + reload embeddings dari folder faces/.
     */
    public function showEnrollForm()
    {
        return view('face.enroll');
    }

    public function storeEnrollForm(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'max:120'],
            'images' => ['array'],
            'images.*' => ['file'],
            'image' => ['file'],
        ]);
        $validator->after(function ($validator) use ($request) {
            if (! $request->hasFile('images') && ! $request->hasFile('image')) {
                $validator->errors()->add('images', 'Foto wajah wajib diupload.');
            }
        });
        $validated = $validator->validate();

        $fastapiBase = trim((string) config('attendance.fastapi_url', env('FASTAPI_URL', '')));
        if ($fastapiBase === '') {
            return back()->withErrors(['fastapi' => 'FASTAPI_URL belum diset.'])->withInput();
        }

        $images = $this->collectImages($request);
        if (empty($images)) {
            return back()->withErrors(['images' => 'Foto wajah wajib diupload.'])->withInput();
        }

        $name = $validated['name'];
        $employee = $this->findEmployeeByFaceName($name);
        $slug = $this->slugPerson($name);
        $successCount = 0;
        $errors = [];

        foreach ($images as $file) {
            $resp = $this->sendEnrollToFastApi($name, $file, $fastapiBase);
            if (! $resp['success']) {
                $errors[] = $resp['error'];
                continue;
            }

            $this->storeFacePhoto($file, $slug);
            $successCount++;
        }

        if ($successCount === 0) {
            return back()->withErrors([
                'fastapi' => array_values(array_unique($errors)) ?: ['Gagal enroll wajah.'],
            ])->withInput();
        }

        $this->upsertFaceIdentity($name, $employee);

        if (! empty($errors)) {
            return back()
                ->withErrors(['fastapi' => array_values(array_unique($errors))])
                ->with('status', "Enroll sebagian berhasil. Foto tersimpan: {$successCount}.");
        }

        return back()->with('status', "Enroll berhasil. Foto tersimpan: {$successCount}.");
    }

    public function reloadFromFaces()
    {
        $fastapiBase = trim((string) config('attendance.fastapi_url', env('FASTAPI_URL', '')));
        if ($fastapiBase === '') {
            return back()->withErrors(['fastapi' => 'FASTAPI_URL belum diset.']);
        }

        $fastapiUrl = rtrim($fastapiBase, '/') . '/reload-from-faces';
        $headers = $this->fastApiHeaders($fastapiBase);

        try {
            $resp = Http::withHeaders($headers)
                ->connectTimeout(5)
                ->timeout(60)
                ->post($fastapiUrl);
        } catch (\Throwable $e) {
            return back()->withErrors(['fastapi' => $e->getMessage()]);
        }

        $json = $resp->json();
        if (!$resp->successful() || !($json['success'] ?? false)) {
            return back()->withErrors([
                'fastapi' => $json['message'] ?? 'Gagal reload embeddings dari faces.',
            ]);
        }

        $created = $json['created'] ?? 0;
        $count = $json['count'] ?? 0;

        return back()->with('status', "Reload selesai. Dibuat: {$created}, total vectors: {$count}");
    }

    public function reloadFromFacesApi()
    {
        $fastapiBase = trim((string) config('attendance.fastapi_url', env('FASTAPI_URL', '')));
        if ($fastapiBase === '') {
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => 'FASTAPI_URL belum diset.',
            ], 500);
        }

        $fastapiUrl = rtrim($fastapiBase, '/') . '/reload-from-faces';
        $headers = $this->fastApiHeaders($fastapiBase);

        try {
            $resp = Http::withHeaders($headers)
                ->connectTimeout(5)
                ->timeout(60)
                ->post($fastapiUrl);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'status' => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }

        $json = $resp->json();
        if (!$resp->successful() || !($json['success'] ?? false)) {
            return response()->json([
                'success' => false,
                'status' => 'failed',
                'message' => $json['message'] ?? 'Gagal reload embeddings dari faces.',
                'fastapi_status' => $resp->status(),
                'fastapi_raw' => $resp->body(),
            ], 400);
        }

        return response()->json([
            'success' => true,
            'created' => $json['created'] ?? 0,
            'count' => $json['count'] ?? 0,
        ], 200);
    }

    private function collectImages(Request $request): array
    {
        $images = [];
        if ($request->hasFile('images')) {
            $images = $request->file('images', []);
        } elseif ($request->hasFile('image')) {
            $images = [$request->file('image')];
        }

        return array_values(array_filter($images, function ($file) {
            return $file instanceof UploadedFile && $file->isValid();
        }));
    }

    private function sendEnrollToFastApi(string $name, UploadedFile $file, string $fastapiBase): array
    {
        $fastapiUrl = rtrim($fastapiBase, '/') . '/enroll';
        $headers = $this->fastApiHeaders($fastapiBase);
        $fileName = $file->getClientOriginalName();
        $path = $file->getRealPath();
        if (! $path) {
            return ['success' => false, 'error' => "{$fileName}: Gagal membaca file image."];
        }

        $stream = fopen($path, 'r');
        if ($stream === false) {
            return ['success' => false, 'error' => "{$fileName}: Gagal membaca file image."];
        }

        try {
            $resp = Http::withHeaders($headers)
                ->connectTimeout(5)
                ->timeout(45)
                ->attach('image', $stream, $file->getClientOriginalName(), [
                    'Content-Type' => $file->getMimeType() ?: 'image/jpeg'
                ])
                ->post($fastapiUrl, [
                    'name' => $name,
                ]);
        } catch (\Throwable $e) {
            return ['success' => false, 'error' => "{$fileName}: {$e->getMessage()}"];
        } finally {
            if (is_resource($stream)) {
                fclose($stream);
            }
        }

        $json = $resp->json();
        if (! is_array($json)) {
            $json = [];
        }
        if (! $resp->successful() || !($json['success'] ?? false)) {
            $raw = trim((string) $resp->body());
            $raw = $raw !== '' ? mb_substr($raw, 0, 200) : null;
            return [
                'success' => false,
                'error' => "{$fileName}: " . ($json['message']
                    ?? ($raw ? "FastAPI error: {$raw}" : 'Gagal enroll wajah.')),
            ];
        }

        return [
            'success' => true,
            'person' => $json['person'] ?? null,
            'saved' => $json['saved'] ?? null,
        ];
    }

    private function storeFacePhoto(UploadedFile $file, string $slug): string
    {
        $dir = public_path('face-enrollments/' . $slug);
        if (! is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $ext = strtolower($file->getClientOriginalExtension() ?: 'jpg');
        $baseName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeBase = preg_replace('/[^a-z0-9\-_.]+/i', '_', $baseName) ?: 'face';
        $random = bin2hex(random_bytes(3));
        $fileName = time() . '_' . $random . '_' . $safeBase . '.' . $ext;

        $file->move($dir, $fileName);

        return 'face-enrollments/' . $slug . '/' . $fileName;
    }

    private function slugPerson(string $name): string
    {
        $slug = strtolower(trim($name));
        $slug = preg_replace('/[^a-z0-9\-_. ]+/', '', $slug);
        $slug = preg_replace('/\s+/', '_', $slug);

        return $slug !== '' ? $slug : 'user_' . time();
    }

    private function upsertFaceIdentity(string $name, ?Employee $employee): void
    {
        if (! Schema::hasTable('face_identities')) {
            return;
        }

        $slug = $this->slugPerson($name);

        FaceIdentity::updateOrCreate(
            ['person_slug' => $slug],
            [
                'name' => $employee?->full_name ?? $name,
                'pegawai_id' => $employee?->id,
            ]
        );
    }

    private function findFaceIdentity(string $faceName): ?FaceIdentity
    {
        if (! Schema::hasTable('face_identities')) {
            return null;
        }

        $faceName = trim($faceName);
        if ($faceName === '') {
            return null;
        }

        $slug = $this->slugPerson($faceName);
        $normalized = mb_strtolower(str_replace('_', ' ', $faceName));

        return FaceIdentity::query()
            ->where('person_slug', $faceName)
            ->orWhere('person_slug', $slug)
            ->orWhereRaw('LOWER(name) = ?', [$normalized])
            ->first();
    }

    private function resolveEmployeeFromFace(string $faceName, ?FaceIdentity $identity): ?Employee
    {
        if ($identity && $identity->pegawai_id) {
            $employee = Employee::query()->find($identity->pegawai_id);
            if ($employee) {
                return $employee;
            }
        }

        return $this->findEmployeeByFaceName($faceName);
    }

    private function formatFaceName(string $faceName): string
    {
        $text = trim($faceName);
        if ($text === '') {
            return $faceName;
        }

        $text = str_replace('_', ' ', $text);

        return ucwords($text);
    }

    private function findEmployeeByFaceName(string $faceName): ?Employee
    {
        $faceName = trim($faceName);
        if ($faceName === '') {
            return null;
        }

        $lower = mb_strtolower($faceName);
        $normalized = str_replace('_', ' ', $lower);

        $employee = Employee::query()
            ->whereRaw('LOWER(full_name) = ?', [$lower])
            ->orWhereRaw('LOWER(full_name) = ?', [$normalized])
            ->first();
        if ($employee) {
            return $employee;
        }

        $slug = $this->slugPerson($faceName);
        $candidates = Employee::query()->get(['id', 'full_name']);
        foreach ($candidates as $candidate) {
            if ($this->slugPerson($candidate->full_name) === $slug) {
                return $candidate;
            }
        }

        return null;
    }

    private function fastApiHeaders(string $fastapiBase): array
    {
        $host = parse_url($fastapiBase, PHP_URL_HOST);
        if (is_string($host) && str_contains($host, 'ngrok')) {
            return ['ngrok-skip-browser-warning' => 'true'];
        }

        return [];
    }
}
