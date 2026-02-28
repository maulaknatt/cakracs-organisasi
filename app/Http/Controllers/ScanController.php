<?php

namespace App\Http\Controllers;

use App\Models\AttendanceLog;
use App\Models\AttendanceToken;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ScanController extends Controller
{
    public function index()
    {
        return view('user.scan');
    }

    public function store(Request $request)
    {
        $request->validate([
            'token' => 'required|string',
        ]);

        $user = Auth::user();

        // 1. Find token
        $token = AttendanceToken::where('token', $request->token)->with('session')->first();

        if (! $token) {
            return response()->json(['message' => 'Token tidak valid.'], 400);
        }

        // 2. Validate expiration
        if (Carbon::now()->greaterThan($token->expires_at)) {
            return response()->json(['message' => 'Token sudah kadaluarsa.'], 400);
        }

        // 3. Validate usage
        if ($token->used_at) {
            return response()->json(['message' => 'Token sudah digunakan.'], 400);
        }

        $session = $token->session;

        // 4. Validate session active
        if (! $session->is_active) {
            return response()->json(['message' => 'Sesi absensi sudah ditutup.'], 400);
        }

        // 5. Check duplicate attendance
        $alreadyScanned = AttendanceLog::where('attendance_session_id', $session->id)
            ->where('user_id', $user->id)
            ->exists();

        if ($alreadyScanned) {
            return response()->json(['message' => 'Anda sudah melakukan absensi untuk sesi ini.'], 400);
        }

        // Success!
        // Mark token used (optional if one token needed per user, but better to allow one token = one usage if we want strictness, or many users per token?
        // User requested: "token hanya bisa dipakai sekali".
        $token->update(['used_at' => Carbon::now()]);

        // Record log
        AttendanceLog::create([
            'attendance_session_id' => $session->id,
            'user_id' => $user->id,
            'scanned_at' => Carbon::now(),
        ]);

        return response()->json([
            'message' => 'Absensi berhasil!',
            'session' => $session->title,
            'timestamp' => Carbon::now()->toDateTimeString(),
        ]);
    }
}
