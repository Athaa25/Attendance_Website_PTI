# HadirIn – Web + Backend Stack

Repo ini sekarang terhubung langsung ke backend resmi [`Backend-HadirIn`](https://github.com/itsDhaxy/Backend-HadirIn) dan menyatukan tiga komponen utama:

1. **Laravel Admin Panel** (repo ini) – dashboard manajemen kehadiran.
2. **API Laravel (Absensi Backend)** – logika verifikasi wajah + rekap harian dari repo HadirIn.
3. **FastAPI Face Service + Cloudflared tunnel** – layanan Python untuk pencocokan wajah dan eksposur publik.

Semua kode backend HadirIn tersedia lewat submodule `integrations/Backend-HadirIn`, sementara integrasi langsung (controller, model, migrasi, route, config) sudah dicangkokkan ke aplikasi Laravel lokal sehingga kedua sisi saling merespon.

---

## Struktur Direktori

```
Attendance_Website_PTI/
├── app/Http/Controllers        # +FaceController, AdminAttendanceController, AbsensiController dari HadirIn
├── app/Models                  # +Absensi & FaceIdentity
├── config/attendance.php       # konfigurasi jam kerja & FastAPI URL
├── database/migrations         # migrasi tabel absensis lengkap
├── resources/views/absensi     # tampilan monitoring absensi realtime
├── routes/api.php              # endpoint API HadirIn
└── integrations/
    └── Backend-HadirIn/        # submodule asli (absensi_backend, face_service, Cloudflared)
```

---

## Persiapan & Instalasi

1. **Clone + update submodule**
   ```bash
   git clone <repo-ini>
   cd Attendance_Website_PTI
   git submodule update --init --recursive
   ```

2. **Dependency PHP & JS**
   ```bash
   composer install
   npm install
   ```

3. **Salin konfigurasi**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Kemudian sesuaikan nilai berikut:
   - `DB_*` (atau gunakan `DB_DATABASE=database/database.sqlite`)
   - `FASTAPI_URL=http://127.0.0.1:8001` (port mengikuti FastAPI yang dijalankan)
   - `WORK_START`, `WORK_END`, `GRACE_MINUTES` bila jam kerja berbeda.

4. **Migrasi database**
   ```bash
   php artisan migrate
   ```
   Ini akan membuat tabel baru `absensis` beserta kolom IN/OUT dan meta yang dibutuhkan FaceController/AdminAttendanceController.

5. **Jalankan Laravel**
   ```bash
   php artisan serve --host=0.0.0.0 --port=8000
   npm run dev    # opsional bila butuh asset Vite
   ```

---

## Menjalankan Face Service (FastAPI)

Semua skrip ada di `integrations/Backend-HadirIn/face_service`.

```bash
cd integrations/Backend-HadirIn/face_service
python -m venv .venv                                        # atau aktifkan venv bawaan
.venv/Scripts/activate                                      # Windows
pip install -r requirements.txt                             # jika tersedia
pip install fastapi uvicorn face-recognition pillow numpy   # install requirements
uvicorn main:app --host 0.0.0.0 --port 8001 --reload
```

Set `FASTAPI_URL=http://127.0.0.1:8001` supaya Laravel mengirim foto ke layanan ini. Untuk meng-ekspose FastAPI ke publik, gunakan Cloudflared yang ada pada submodule:

```bash
cd integrations/Backend-HadirIn/Cloudflared
cloudflared.exe tunnel --url http://localhost:8001
```

---

## Endpoint API Baru

| Method | Endpoint                         | Deskripsi |
|--------|----------------------------------|-----------|
| POST   | `/api/face-verify`               | Flutter → Laravel → FastAPI → simpan absensi sekaligus cek IN/OUT otomatis. |
| POST   | `/api/attendance`                | Endpoint fallback untuk menerima payload JSON dari FastAPI. |
| GET    | `/api/admin/attendance/today`    | Rekap absensi hari ini (count on-time/late/absent). |
| POST   | `/api/admin/attendance/update`   | Admin override status/ alasan ketidakhadiran. |

Selain API, halaman monitoring cepat tersedia di `/absensi` yang menampilkan isi tabel `absensis` terbaru.

---

## Testing

Tes dasar bisa dijalankan lewat:

```bash
php artisan test
```

Pastikan database testing sudah terkoneksi dan migrasi sudah dijalankan sebelum menjalankan tes berbasis feature tambahan.

---

## Catatan

- Submodule `integrations/Backend-HadirIn` mengikuti commit upstream. Jalankan `git submodule update --remote` bila ingin menarik update terbaru backend HadirIn.
- Konfigurasi jam kerja bisa diubah di `config/attendance.php` atau lewat variabel `WORK_START`, `WORK_END`, `GRACE_MINUTES`.
- Endpoint FastAPI wajib merespon dalam format JSON `{ success: bool, user: string, distance, gap }` seperti implementasi HadirIn.
