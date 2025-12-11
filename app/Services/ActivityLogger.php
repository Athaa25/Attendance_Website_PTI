<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class ActivityLogger
{
    public static function log(string $action, Model|string|null $subject, string $description): void
    {
        ActivityLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'entity_type' => is_string($subject) ? $subject : ($subject?->getMorphClass()),
            'entity_id' => $subject instanceof Model ? $subject->getKey() : null,
            'description' => $description,
        ]);
    }
}
