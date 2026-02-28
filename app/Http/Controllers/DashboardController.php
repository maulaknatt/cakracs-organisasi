<?php

namespace App\Http\Controllers;

use App\Models\Dokumentasi;
use App\Models\Kegiatan;
use App\Models\Keuangan;
use App\Models\Pengumuman;
use App\Models\Tugas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $acaraAktif = Kegiatan::orderByDesc('tanggal_mulai')->first();

        // Global stats for everyone
        $allTasks = Tugas::all();

        // Tugas Saya: Tasks from kegiatan the user is part of (for list if still used)
        if ($user->isSuperAdmin() || $user->isPengurus()) {
            $tugasSaya = Tugas::latest()->limit(3)->get();
        }
        else {
            $kegiatanIds = $user->panitiaKegiatan()->pluck('kegiatan_id');
            $tugasSaya = Tugas::whereIn('kegiatan_id', $kegiatanIds)->latest()->limit(3)->get();
        }

        $pengumuman = Pengumuman::orderByDesc('tanggal')->limit(1)->get();
        $saldoTotal = Keuangan::where('jenis', 'masuk')->sum('jumlah') - Keuangan::where('jenis', 'keluar')->sum('jumlah');

        // Stats for Chart
        $totalTugas = $allTasks->count();
        $selesaiTugas = $allTasks->where('status', 'done')->count();
        $belumSelesaiTugas = $totalTugas - $selesaiTugas;
        $progressPercentage = $totalTugas > 0 ? round(($selesaiTugas / $totalTugas) * 100) : 0;

        // Get online users - only when using database session driver
        try {
            if (config('session.driver') === 'database') {
                $onlineUserIds = \DB::table('sessions')
                    ->whereNotNull('user_id')
                    ->where('last_activity', '>=', now()->subMinutes(5)->getTimestamp())
                    ->pluck('user_id');
                $onlineUsers = User::whereIn('id', $onlineUserIds)->limit(5)->get();
            } else {
                $onlineUsers = collect();
            }
        } catch (\Exception $e) {
            $onlineUsers = collect();
        }

        // Task Trend Data (Last 7 Days)
        $taskTrendData = [];
        $taskTrendLabels = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $taskTrendLabels[] = $date->format('d/m');
            $taskTrendData[] = Tugas::where('status', 'done')
                ->whereDate('updated_at', $date)
                ->count();
        }

        return view('dashboard.index', compact(
            'acaraAktif',
            'tugasSaya',
            'pengumuman',
            'saldoTotal',
            'totalTugas',
            'selesaiTugas',
            'belumSelesaiTugas',
            'progressPercentage',
            'onlineUsers',
            'taskTrendData',
            'taskTrendLabels'
        ));
    }

    /**
     * Global search across all modules
     */
    public function search(Request $request)
    {
        try {
            $q = $request->get('q', '');
            $results = [];

            if (strlen($q) < 2) {
                return response()->json(['results' => []]);
            }

            $user = auth()->user();

            if (!$user) {
                return response()->json(['results' => []]);
            }

            // Search Kegiatan
            try {
                if ($user->canAccess('kegiatan')) {
                    $kegiatans = Kegiatan::where(function ($query) use ($q) {
                        $query->where('judul', 'like', "%{$q}%")
                            ->orWhere('deskripsi', 'like', "%{$q}%");
                    })
                        ->limit(5)
                        ->get();

                    foreach ($kegiatans as $kegiatan) {
                        $results[] = [
                            'type' => 'Kegiatan',
                            'label' => $kegiatan->judul ?? 'Kegiatan',
                            'url' => route('kegiatan.show', $kegiatan->id),
                        ];
                    }
                }
            }
            catch (\Exception $e) {
                Log::error('Search Kegiatan error: ' . $e->getMessage());
            }

            // Search Tugas
            try {
                if ($user->canAccess('tugas')) {
                    $query = Tugas::where(function ($qry) use ($q) {
                        $qry->where('judul', 'like', "%{$q}%")
                            ->orWhere('deskripsi', 'like', "%{$q}%");
                    });

                    if ($user->isAnggota()) {
                        $kegiatanIds = $user->panitiaKegiatan()->pluck('kegiatan_id');
                        $query->whereIn('kegiatan_id', $kegiatanIds);
                    }

                    $tugas = $query->limit(5)->get();

                    foreach ($tugas as $t) {
                        $results[] = [
                            'type' => 'Tugas',
                            'label' => $t->judul ?? 'Tugas',
                            'url' => route('tugas.show', $t->id),
                        ];
                    }
                }
            }
            catch (\Exception $e) {
                Log::error('Search Tugas error: ' . $e->getMessage());
            }

            // Search Anggota
            try {
                if ($user->isSuperAdmin()) {
                    $anggota = User::where(function ($query) use ($q) {
                        $query->where('name', 'like', "%{$q}%")
                            ->orWhere('email', 'like', "%{$q}%");
                    })
                        ->limit(5)
                        ->get();

                    foreach ($anggota as $a) {
                        $results[] = [
                            'type' => 'Anggota',
                            'label' => ($a->name ?? 'User') . ' (' . ($a->jabatan ?? '-') . ')',
                            'url' => route('anggota.show', $a->id),
                        ];
                    }
                }
            }
            catch (\Exception $e) {
                Log::error('Search Anggota error: ' . $e->getMessage());
            }

            // Search Keuangan
            try {
                if ($user->canAccess('keuangan')) {
                    $keuangan = Keuangan::where(function ($query) use ($q) {
                        $query->where('judul', 'like', "%{$q}%")
                            ->orWhere('deskripsi', 'like', "%{$q}%");
                    })
                        ->limit(5)
                        ->get();

                    foreach ($keuangan as $k) {
                        $results[] = [
                            'type' => 'Keuangan',
                            'label' => ($k->judul ?? $k->deskripsi ?? 'Transaksi') . ' - Rp ' . number_format($k->jumlah ?? 0, 0, ',', '.'),
                            'url' => route('keuangan.show', $k->id),
                        ];
                    }
                }
            }
            catch (\Exception $e) {
                Log::error('Search Keuangan error: ' . $e->getMessage());
            }

            // Search Dokumentasi
            try {
                if ($user->canAccess('dokumentasi')) {
                    $query = Dokumentasi::where('judul', 'like', "%{$q}%");

                    if ($user->isAnggota()) {
                        $query->where('highlight', true);
                    }

                    $dokumentasi = $query->limit(5)->get();

                    foreach ($dokumentasi as $d) {
                        $results[] = [
                            'type' => 'Dokumentasi',
                            'label' => $d->judul ?? 'Dokumentasi',
                            'url' => route('dokumentasi.show', $d->id),
                        ];
                    }
                }
            }
            catch (\Exception $e) {
                Log::error('Search Dokumentasi error: ' . $e->getMessage());
            }

            // Search Pengumuman
            try {
                if ($user->canAccess('pengumuman')) {
                    $pengumuman = Pengumuman::where(function ($query) use ($q) {
                        $query->where('judul', 'like', "%{$q}%")
                            ->orWhere('isi', 'like', "%{$q}%");
                    })
                        ->limit(5)
                        ->get();

                    foreach ($pengumuman as $p) {
                        $results[] = [
                            'type' => 'Pengumuman',
                            'label' => $p->judul ?? 'Pengumuman',
                            'url' => route('pengumuman.show', $p->id),
                        ];
                    }
                }
            }
            catch (\Exception $e) {
                Log::error('Search Pengumuman error: ' . $e->getMessage());
            }

            return response()->json([
                'results' => $results,
                'count' => count($results),
            ]);
        }
        catch (\Exception $e) {
            Log::error('Search error: ' . $e->getMessage(), [
                'query' => $request->get('q'),
                'trace' => $e->getTraceAsString(),
            ]);

            return response()->json([
                'results' => [],
                'error' => 'Terjadi kesalahan saat mencari. Silakan coba lagi.',
            ], 500);
        }
    }
}
