<?php

use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Route;


Route::get('/login', [LoginController::class , 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class , 'login']);
Route::post('/logout', [LoginController::class , 'logout'])->name('logout');

// Website Profil (Publik)
use App\Http\Controllers\ProfilBerandaController;

Route::get('/', [ProfilBerandaController::class , 'index'])->name('profil.beranda');
Route::view('/tentang', 'profil.tentang')->name('profil.tentang');
use App\Http\Controllers\ProfilKegiatanController;

Route::get('/kegiatan', [ProfilKegiatanController::class , 'index'])->name('profil.kegiatan');
Route::get('/kegiatan/{id}', [ProfilKegiatanController::class , 'show'])->name('profil.kegiatan.detail');
use App\Http\Controllers\ProfilGaleriController;

Route::get('/galeri', [ProfilGaleriController::class , 'index'])->name('profil.galeri');
Route::view('/kontak', 'profil.kontak')->name('profil.kontak');

// Attendance System
Route::middleware(['auth'])->group(function () {
    // Admin: Attendance Management
    Route::group(['prefix' => 'dashboard/attendance', 'as' => 'attendance.'], function () {
            Route::get('/', [\App\Http\Controllers\AttendanceController::class , 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\AttendanceController::class , 'store'])->name('store');
            Route::put('/{session}', [\App\Http\Controllers\AttendanceController::class , 'update'])->name('update');
            Route::delete('/{session}', [\App\Http\Controllers\AttendanceController::class , 'destroy'])->name('destroy');
            Route::get('/{session}', [\App\Http\Controllers\AttendanceController::class , 'show'])->name('show');
            Route::post('/{session}/toggle', [\App\Http\Controllers\AttendanceController::class , 'toggle'])->name('toggle');
            Route::post('/{session}/qr-token', [\App\Http\Controllers\AttendanceController::class , 'getQrToken'])->name('getQrToken');

            // Manual Attendance
            Route::post('/{session}/manual', [\App\Http\Controllers\AttendanceController::class , 'storeManual'])->name('storeManual');
            Route::delete('/{session}/manual/{user}', [\App\Http\Controllers\AttendanceController::class , 'destroyManual'])->name('destroyManual');
            Route::get('/{session}/export', [\App\Http\Controllers\AttendanceController::class , 'export'])->name('export');
        }
        );

        // User: Scan
        Route::get('/scan', [\App\Http\Controllers\ScanController::class , 'index'])->name('scan.index');
        Route::post('/scan', [\App\Http\Controllers\ScanController::class , 'store'])->name('scan.store');
    });

// Dashboard Admin (Internal)
use App\Http\Controllers\DashboardController;

Route::prefix('dashboard')->middleware(['auth'])->group(function () {
    // Dashboard - semua role bisa akses
    Route::get('/', [DashboardController::class , 'index'])->name('dashboard');

    // Global Search
    Route::get('/search', [DashboardController::class , 'search'])->name('dashboard.search');

    // Kegiatan - semua role boleh lihat daftar & workspace; create/edit/delete di controller
    Route::post('kegiatan/{kegiatan}/tugas', [App\Http\Controllers\KegiatanController::class , 'storeTugas'])->name('kegiatan.tugas.store');
    Route::post('kegiatan/{kegiatan}/dokumentasi', [App\Http\Controllers\KegiatanController::class , 'storeDokumentasi'])->name('kegiatan.dokumentasi.store');
    Route::post('kegiatan/{kegiatan}/arsip', [App\Http\Controllers\KegiatanController::class , 'storeArsip'])->name('kegiatan.arsip.store');
    Route::post('kegiatan/{kegiatan}/anggota', [App\Http\Controllers\KegiatanController::class , 'storeAnggota'])->name('kegiatan.anggota.store');
    Route::put('kegiatan/{kegiatan}/anggota/{anggota}', [App\Http\Controllers\KegiatanController::class , 'updateAnggota'])->name('kegiatan.anggota.update');
    Route::delete('kegiatan/{kegiatan}/anggota/{anggota}', [App\Http\Controllers\KegiatanController::class , 'destroyAnggota'])->name('kegiatan.anggota.destroy');
    Route::resource('kegiatan', App\Http\Controllers\KegiatanController::class);

    // Tugas - semua role bisa akses (dengan batasan berbeda, check di controller)
    Route::resource('tugas', App\Http\Controllers\TugasController::class)
        ->parameters(['tugas' => 'tugas']);

    // Keuangan - Super Admin & Pengurus (check di controller)
    Route::get('keuangan/export', [App\Http\Controllers\KeuanganController::class , 'export'])->name('keuangan.export');
    Route::resource('keuangan', App\Http\Controllers\KeuanganController::class);

    // Anggota - hanya Super Admin (check di controller)
    Route::resource('anggota', App\Http\Controllers\AnggotaController::class);

    // Dokumentasi - semua role bisa akses (dengan batasan berbeda, check di controller)
    Route::resource('dokumentasi', App\Http\Controllers\DokumentasiController::class);

    // Arsip - hanya Super Admin (check di controller)
    Route::post('arsip/{arsip}/attachments', [App\Http\Controllers\ArsipController::class , 'storeAttachment'])->name('arsip.attachments.store');
    Route::delete('arsip/{arsip}/attachments/{attachment}', [App\Http\Controllers\ArsipController::class , 'destroyAttachment'])->name('arsip.attachments.destroy');
    Route::resource('arsip', App\Http\Controllers\ArsipController::class);

    // Pengumuman - Super Admin & Pengurus (check di controller)
    Route::post('pengumuman/{pengumuman}/like', [App\Http\Controllers\PengumumanController::class , 'toggleLike'])->name('pengumuman.like');
    Route::post('pengumuman/{pengumuman}/pin', [App\Http\Controllers\PengumumanController::class , 'togglePin'])->name('pengumuman.pin');
    Route::post('pengumuman/{pengumuman}/comment', [App\Http\Controllers\PengumumanController::class , 'storeComment'])->name('pengumuman.comments.store');
    Route::put('pengumuman/comments/{comment}', [App\Http\Controllers\PengumumanController::class , 'updateComment'])->name('pengumuman.comments.update');
    Route::delete('pengumuman/comments/{comment}', [App\Http\Controllers\PengumumanController::class , 'destroyComment'])->name('pengumuman.comments.destroy');
    Route::post('pengumuman/comments/{comment}/like', [App\Http\Controllers\PengumumanController::class , 'toggleCommentLike'])->name('pengumuman.comments.like');
	Route::resource('pengumuman', App\Http\Controllers\PengumumanController::class);

    // Pengaturan
    Route::get('pengaturan', [App\Http\Controllers\PengaturanController::class , 'index'])->name('pengaturan.index');
    Route::put('pengaturan/organisasi', [App\Http\Controllers\PengaturanController::class , 'updateOrganization'])->name('pengaturan.updateOrganization');
    Route::delete('pengaturan/logo', [App\Http\Controllers\PengaturanController::class , 'deleteOrganizationLogo'])->name('pengaturan.deleteOrganizationLogo');
    Route::put('pengaturan/profil', [App\Http\Controllers\PengaturanController::class , 'updateProfile'])->name('pengaturan.updateProfile');
    Route::delete('pengaturan/delete-photo', [App\Http\Controllers\PengaturanController::class , 'deleteProfilePhoto'])->name('pengaturan.deleteProfilePhoto');
    Route::put('pengaturan/password', [App\Http\Controllers\PengaturanController::class , 'updatePassword'])->name('pengaturan.updatePassword');

    // Activity Log - hanya Super Admin (check di controller)
    Route::get('activity-log', [App\Http\Controllers\ActivityLogController::class , 'index'])->name('activity-log.index');
    Route::get('activity-log/{activityLog}', [App\Http\Controllers\ActivityLogController::class , 'show'])->name('activity-log.show');
    Route::delete('activity-log/{activityLog}', [App\Http\Controllers\ActivityLogController::class , 'destroy'])->name('activity-log.destroy');

    // Chat Room
    Route::get('chat', [App\Http\Controllers\ChatController::class , 'index'])->name('chat.index');
    Route::post('chat/send', [App\Http\Controllers\ChatController::class , 'sendMessage'])->name('chat.send');
    Route::post('chat/sticker/upload', [App\Http\Controllers\ChatController::class , 'uploadSticker'])->name('chat.sticker.upload');
    Route::delete('chat/{chat}/delete', [App\Http\Controllers\ChatController::class , 'destroy'])->name('chat.destroy');
    Route::post('chat/poll/{poll}/vote', [App\Http\Controllers\ChatController::class , 'votePoll'])->name('chat.poll.vote');

    // Voice & Soundboard
    Route::get('voice', [\App\Http\Controllers\Dashboard\VoiceController::class , 'index'])->name('voice.index');
    Route::post('voice/token', [\App\Http\Controllers\Dashboard\VoiceController::class , 'getToken'])->name('voice.token');
    Route::post('voice/sync-state', [\App\Http\Controllers\Dashboard\VoiceController::class , 'syncState'])->name('voice.sync-state');
    Route::post('voice/broadcast-sound', [\App\Http\Controllers\Dashboard\VoiceController::class , 'broadcastSound'])->name('voice.broadcast-sound');

    // AI Assistant
    Route::get('api/chat/history', [App\Http\Controllers\Api\AIChatController::class, 'history'])->name('ai.history');
    Route::post('api/chat', [App\Http\Controllers\Api\AIChatController::class, 'chat'])->name('ai.chat');
    Route::delete('api/chat/history', [App\Http\Controllers\Api\AIChatController::class, 'clearHistory'])->name('ai.clear');

    // Notifications
    Route::get('notifications/unread', [App\Http\Controllers\Dashboard\NotificationController::class, 'getUnread'])->name('notifications.unread');
    Route::post('notifications/{id}/read', [App\Http\Controllers\Dashboard\NotificationController::class, 'markAsRead'])->name('notifications.read');
    Route::post('notifications/read-all', [App\Http\Controllers\Dashboard\NotificationController::class, 'markAllAsRead'])->name('notifications.read-all');
});
