<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    /**
     * Check if user can access activity log
     */
    private function checkAccess()
    {
        $user = auth()->user();
        if (!$user || (!$user->isSuperAdmin() && !$user->isAdmin())) {
            abort(403, 'Akses ditolak. Hanya Admin yang dapat mengakses halaman ini.');
        }
    }

    /**
     * Display a listing of activity logs
     */
    public function index(Request $request)
    {
        $this->checkAccess();

        $query = ActivityLog::with('user')->latest();

        // Filter by user
        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        // Filter by module
        if ($request->filled('module')) {
            $query->where('module', $request->module);
        }

        // Filter by action
        if ($request->filled('action')) {
            $query->where('action', $request->action);
        }

        // Filter by date range
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('user_name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('module', 'like', "%{$search}%");
            });
        }

        $perPage = $request->get('per_page', 10);
        $logs = $query->paginate($perPage)->withQueryString();

        // Get filter options
        $users = \App\Models\User::orderBy('name')->get();
        $modules = ActivityLog::distinct()->pluck('module')->sort();
        $dbActions = ActivityLog::distinct()->pluck('action')->toArray();
        $actions = collect(array_merge(['create', 'update', 'delete', 'login', 'logout', 'upload'], $dbActions))->unique()->sort();

        return view('dashboard.activity-log.index', compact('logs', 'users', 'modules', 'actions'));
    }

    /**
     * Show single activity log detail
     */
    public function show(ActivityLog $activityLog)
    {
        $this->checkAccess();
        $activityLog->load('user');

        return view('dashboard.activity-log.show', compact('activityLog'));
    }

    /**
     * Delete an activity log
     */
    public function destroy(ActivityLog $activityLog)
    {
        $this->checkAccess();
        
        $activityLog->delete();

        return redirect()->route('activity-log.index')
            ->with('toast', [
                'type' => 'success',
                'message' => 'Log aktivitas berhasil dihapus.',
            ]);
    }
}
