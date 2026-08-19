<?php

namespace App\Services;

use App\Models\logs\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogService
{
    public static function log(
        string $action,
        ?string $module = null,
        ?string $description = null,
        ?Model $subject = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ): ActivityLog {

        return ActivityLog::create([

            'user_id' => Auth::id(),

            'action' => $action,

            'module' => $module,

            'description' => $description,

            'subject_type' => $subject
                ? get_class($subject)
                : null,

            'subject_id' => $subject?->getKey(),

            'old_values' => $oldValues,

            'new_values' => $newValues,

            'url' => Request::fullUrl(),

            'method' => Request::method(),

            'ip_address' => Request::ip(),

            'user_agent' => Request::userAgent(),

        ]);
    }
}
