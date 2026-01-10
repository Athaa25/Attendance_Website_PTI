<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\View\View;

class ActivityLogController extends Controller
{
    public function index(): View
    {
        $logs = $this->buildLogs(request());

        return view('activity-logs.index', [
            'logs' => $logs,
        ]);
    }

    public function search(Request $request): JsonResponse
    {
        $logs = $this->buildLogs($request);

        return response()->json([
            'html' => view('activity-logs.partials.table', [
                'logs' => $logs,
            ])->render(),
        ]);
    }

    private function buildLogs(Request $request): LengthAwarePaginator
    {
        $logs = ActivityLog::with('user')
            ->latest()
            ->paginate(25)
            ->withQueryString();

        $logs->withPath(route('activity-logs.index'));

        return $logs;
    }
}
