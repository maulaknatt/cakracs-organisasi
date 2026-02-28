<?php

namespace App\Http\Controllers;

use App\Models\AttendanceLog;
use App\Models\AttendanceSession;
use App\Models\AttendanceToken;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AttendanceController extends Controller
{
    public function index(Request $request)
    {
        $query = AttendanceSession::query();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        $sessions = $query->latest('date')->latest('created_at')->paginate(10)->withQueryString();

        return view('admin.attendance.index', compact('sessions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        AttendanceSession::create([
            'title' => $request->title,
            'date' => $request->date,
            'is_active' => false,
        ]);

        return back()->with('success', 'Sesi absensi berhasil dibuat.');
    }

    public function toggle(AttendanceSession $session)
    {
        $session->update(['is_active' => !$session->is_active]);

        $status = $session->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return back()->with('success', "Sesi absensi berhasil $status.");
    }

    public function update(Request $request, AttendanceSession $session)
    {
        // Permission check
        $currentUser = auth()->user();
        if (!$currentUser->isSuperAdmin() && !$currentUser->isPengurus() && $currentUser->jabatan !== 'Ketua') {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'date' => 'required|date',
        ]);

        $session->update([
            'title' => $request->title,
            'date' => $request->date,
        ]);

        return back()->with('success', 'Sesi absensi berhasil diperbarui.');
    }

    public function destroy(AttendanceSession $session)
    {
        // Permission check
        $currentUser = auth()->user();
        if (!$currentUser->isSuperAdmin() && !$currentUser->isPengurus() && $currentUser->jabatan !== 'Ketua') {
            abort(403, 'Unauthorized action.');
        }

        $session->delete();

        return back()->with('success', 'Sesi absensi berhasil dihapus.');
    }

    public function show(AttendanceSession $session)
    {
        // Load users with pagination to show who is present vs absent
        $users = \App\Models\User::orderBy('name')->paginate(20)->withQueryString();

        // Eager load logs for this session
        $session->load('logs');

        // Map attendance status using through() for paginator
        $attendanceData = $users->through(function ($user) use ($session) {
            $log = $session->logs->where('user_id', $user->id)->first();

            return [
            'user' => $user,
            'is_present' => (bool)$log,
            'scanned_at' => $log ? $log->scanned_at : null,
            ];
        });

        return view('admin.attendance.show', compact('session', 'attendanceData'));
    }

    public function getQrToken(AttendanceSession $session)
    {
        if (!$session->is_active) {
            return response()->json(['error' => 'Sesi tidak aktif'], 403);
        }

        // Generate new token valid for 15 seconds
        $token = Str::random(32);
        AttendanceToken::create([
            'attendance_session_id' => $session->id,
            'token' => $token,
            'expires_at' => Carbon::now()->addMinutes(5),
        ]);

        return response()->json([
            'token' => $token,
            'expires_at' => Carbon::now()->addMinutes(5)->toIso8601String(),
        ]);
    }

    public function storeManual(Request $request, AttendanceSession $session)
    {
        // Permission check
        $user = auth()->user();
        if (!$user->isSuperAdmin() && !$user->isPengurus() && $user->jabatan !== 'Ketua') {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        // Check if already present
        $exists = \App\Models\AttendanceLog::where('attendance_session_id', $session->id)
            ->where('user_id', $request->user_id)
            ->exists();

        if ($exists) {
            return back()->with('error', 'User ini sudah absen.');
        }

        \App\Models\AttendanceLog::create([
            'attendance_session_id' => $session->id,
            'user_id' => $request->user_id,
            'scanned_at' => Carbon::now(),
        ]);

        return back()->with('success', 'Absensi manual berhasil ditambahkan.');
    }

    public function destroyManual(AttendanceSession $session, \App\Models\User $user)
    {
        // Permission check
        $currentUser = auth()->user();
        if (!$currentUser->isSuperAdmin() && !$currentUser->isPengurus() && $currentUser->jabatan !== 'Ketua') {
            abort(403, 'Unauthorized action.');
        }

        AttendanceLog::where('attendance_session_id', $session->id)
            ->where('user_id', $user->id)
            ->delete();

        return back()->with('success', 'Data absensi berhasil dihapus.');
    }

    public function export(AttendanceSession $session)
    {
        // Permission check
        $currentUser = auth()->user();
        if (!$currentUser->isSuperAdmin() && !$currentUser->isPengurus() && $currentUser->jabatan !== 'Ketua') {
            abort(403, 'Unauthorized action.');
        }

        $filename = 'Absensi_' . str_replace(' ', '_', $session->title) . '_' . $session->date->format('Y-m-d') . '.csv';

        $headers = [
            'Content-type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=$filename",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0',
        ];

        // Get all members (Anggota/Pengurus)
        $users = \App\Models\User::whereHas('role', function ($q) {
            $q->whereIn('nama_role', ['Anggota', 'Pengurus', 'Super Admin']);
        })->orderBy('name')->get();

        // Pre-fetch logs for this session
        $logs = $session->logs->keyBy('user_id');

        $callback = function () use ($users, $logs) {
            $file = fopen('php://output', 'w');

            // Header Row (Tanpa Email)
            fputcsv($file, ['No', 'Nama Anggota', 'Jabatan', 'Status', 'Waktu Absensi']);

            $no = 1;
            foreach ($users as $user) {
                $log = $logs->get($user->id);
                $status = $log ? 'Hadir' : 'Tidak Hadir';
                $time = $log ? $log->scanned_at->format('H:i:s') : '-';
                $jabatan = $user->jabatan ?? '-';

                fputcsv($file, [
                    $no++,
                    $user->name,
                    $jabatan,
                    $status,
                    $time,
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
