<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ActivityLogService
{
    /**
     * Log an activity
     */
    public static function log(
        string $action,
        string $module,
        ?int $targetId = null,
        ?string $description = null,
        ?array $oldValue = null,
        ?array $newValue = null,
        ?Request $request = null
    ): ActivityLog {
        $user = Auth::user();

        return ActivityLog::create([
            'user_id' => $user?->id,
            'user_name' => $user?->name ?? 'System',
            'role' => $user?->role?->nama_role ?? 'Guest',
            'action' => $action,
            'module' => $module,
            'target_id' => $targetId,
            'description' => $description ?? self::generateDescription($action, $module, $targetId),
            'old_value' => $oldValue,
            'new_value' => $newValue,
            'ip_address' => $request?->ip() ?? request()->ip(),
            'user_agent' => $request?->userAgent() ?? request()->userAgent(),
        ]);
    }

    /**
     * Generate default description
     */
    private static function generateDescription(string $action, string $module, ?int $targetId): string
    {
        $moduleLabel = ucfirst($module);
        $actionLabel = match ($action) {
            'create' => 'menambahkan',
            'update' => 'mengubah',
            'delete' => 'menghapus',
            'login' => 'melakukan login',
            'logout' => 'melakukan logout',
            'upload' => 'mengupload',
            default => $action,
        };

        if ($targetId) {
            return "{$actionLabel} {$moduleLabel} dengan ID {$targetId}";
        }

        return "{$actionLabel} {$moduleLabel}";
    }

    /**
     * Log create action
     */
    public static function logCreate(string $module, $model, ?Request $request = null): ActivityLog
    {
        return self::log(
            action: 'create',
            module: $module,
            targetId: $model->id ?? null,
            description: "Menambahkan {$module} baru: ".($model->name ?? $model->title ?? $model->judul ?? 'ID '.($model->id ?? 'N/A')),
            newValue: $model->toArray(),
            request: $request
        );
    }

    /**
     * Log update action
     */
    public static function logUpdate(string $module, $model, array $oldData, ?Request $request = null): ActivityLog
    {
        return self::log(
            action: 'update',
            module: $module,
            targetId: $model->id ?? null,
            description: "Mengubah {$module}: ".($model->name ?? $model->title ?? $model->judul ?? 'ID '.($model->id ?? 'N/A')),
            oldValue: $oldData,
            newValue: $model->toArray(),
            request: $request
        );
    }

    /**
     * Log delete action
     */
    public static function logDelete(string $module, $model, ?Request $request = null): ActivityLog
    {
        return self::log(
            action: 'delete',
            module: $module,
            targetId: $model->id ?? null,
            description: "Menghapus {$module}: ".($model->name ?? $model->title ?? $model->judul ?? 'ID '.($model->id ?? 'N/A')),
            oldValue: $model->toArray(),
            request: $request
        );
    }

    /**
     * Log login action
     */
    public static function logLogin($user, ?Request $request = null): ActivityLog
    {
        return self::log(
            action: 'login',
            module: 'auth',
            targetId: $user->id ?? null,
            description: "User {$user->name} melakukan login",
            request: $request
        );
    }

    /**
     * Log logout action
     */
    public static function logLogout($user, ?Request $request = null): ActivityLog
    {
        return self::log(
            action: 'logout',
            module: 'auth',
            targetId: $user->id ?? null,
            description: "User {$user->name} melakukan logout",
            request: $request
        );
    }
}
