<?php

namespace App\Http\Controllers;

use App\Models\Keuangan;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class KeuanganController extends Controller
{
    /**
     * Check if user can access keuangan module
     */
    private function checkAccess()
    {
        $user = auth()->user();
        if (!$user || (!$user->isSuperAdmin() && !$user->isAdmin() && !$user->isPengurus())) {
            abort(403, 'Akses ditolak. Hanya Admin dan Pengurus yang dapat mengakses halaman ini.');
        }
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $this->checkAccess();

        $query = \App\Models\Keuangan::with('kegiatan');

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%")
                    ->orWhere('jumlah', 'like', "%{$search}%")
                    ->orWhereHas('kegiatan', function ($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%");
                }
                );
            });
        }

        // Filter: Jenis Transaksi
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }

        // Filter: Kegiatan
        if ($request->filled('kegiatan_id')) {
            $query->where('kegiatan_id', $request->kegiatan_id);
        }

        // Filter: Tanggal dari
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_dari);
        }

        // Filter: Tanggal sampai
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_sampai);
        }

        // Filter: Bulan
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', $request->bulan);
        }

        // Filter: Tahun
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal', $request->tahun);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'tanggal');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder)->orderBy('id', 'desc');

        // Global Organization Stats (Always consistent)
        $globalStats = \App\Models\Keuangan::selectRaw("
            SUM(CASE WHEN jenis = 'masuk' THEN jumlah ELSE 0 END) as total_pemasukan,
            SUM(CASE WHEN jenis = 'keluar' THEN jumlah ELSE 0 END) as total_pengeluaran
        ")->first();

        $globalPemasukan = $globalStats->total_pemasukan ?? 0;
        $globalPengeluaran = $globalStats->total_pengeluaran ?? 0;
        $globalSaldo = $globalPemasukan - $globalPengeluaran;

        // Filtered Totals (Summary of current view)
        $totalQuery = clone $query;
        $allFilteredData = $totalQuery->get();
        $totalFilteredPemasukan = $allFilteredData->where('jenis', 'masuk')->sum('jumlah');
        $totalFilteredPengeluaran = $allFilteredData->where('jenis', 'keluar')->sum('jumlah');
        $saldoFiltered = $totalFilteredPemasukan - $totalFilteredPengeluaran;

        $perPage = $request->get('per_page', 10);
        $keuangan = $query->latest()->paginate($perPage)->withQueryString();

        // Check if any filter is active
        $isFiltered = $request->anyFilled(['search', 'jenis', 'kegiatan_id', 'tanggal_dari', 'tanggal_sampai', 'bulan', 'tahun']);

        // Get filter options
        $kegiatanList = \App\Models\Kegiatan::all();
        $keuanganPerKegiatan = [];
        foreach ($kegiatanList as $kegiatan) {
            $transaksi = \App\Models\Keuangan::where('kegiatan_id', $kegiatan->id)->get();
            if ($transaksi->count() > 0) {
                $pemasukan = $transaksi->where('jenis', 'masuk')->sum('jumlah');
                $pengeluaran = $transaksi->where('jenis', 'keluar')->sum('jumlah');
                $keuanganPerKegiatan[] = [
                    'kegiatan' => $kegiatan,
                    'pemasukan' => $pemasukan,
                    'pengeluaran' => $pengeluaran,
                    'saldo' => $pemasukan - $pengeluaran,
                    'transaksi' => $transaksi->count()
                ];
            }
        }

        $years = \App\Models\Keuangan::selectRaw('EXTRACT(YEAR FROM tanggal)::int as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('dashboard.keuangan.index', compact(
            'keuangan',
            'globalPemasukan',
            'globalPengeluaran',
            'globalSaldo',
            'totalFilteredPemasukan',
            'totalFilteredPengeluaran',
            'saldoFiltered',
            'isFiltered',
            'kegiatanList',
            'keuanganPerKegiatan',
            'years'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->checkAccess();
        $kegiatan = \App\Models\Kegiatan::all();

        return view('dashboard.keuangan.create', compact('kegiatan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->checkAccess();

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'jenis' => 'required|in:masuk,keluar',
            'deskripsi' => 'nullable|string',
            'jumlah' => 'required|numeric|min:0',
            'kegiatan_id' => 'nullable|exists:kegiatans,id',
        ]);

        $keuangan = \App\Models\Keuangan::create($validated);

        // Log activity
        ActivityLogService::logCreate('keuangan', $keuangan, $request);

        return redirect()->route('keuangan.index')
            ->with('toast', [
            'type' => 'success',
            'message' => 'Transaksi berhasil ditambahkan.',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Keuangan $keuangan)
    {
        $this->checkAccess();
        $keuangan->load('kegiatan');

        return view('dashboard.keuangan.show', compact('keuangan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Keuangan $keuangan)
    {
        $this->checkAccess();
        $kegiatan = \App\Models\Kegiatan::all();

        return view('dashboard.keuangan.edit', compact('keuangan', 'kegiatan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Keuangan $keuangan)
    {
        $this->checkAccess();

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'tanggal' => 'required|date',
            'jenis' => 'required|in:masuk,keluar',
            'deskripsi' => 'nullable|string',
            'jumlah' => 'required|numeric|min:0',
            'kegiatan_id' => 'nullable|exists:kegiatans,id',
        ]);

        $oldData = $keuangan->toArray();
        $keuangan->update($validated);

        // Log activity
        ActivityLogService::logUpdate('keuangan', $keuangan, $oldData, $request);

        return redirect()->route('keuangan.index')
            ->with('toast', [
            'type' => 'success',
            'message' => 'Transaksi berhasil diupdate.',
        ]);
    }

    /**
     * Export keuangan data to CSV.
     */
    public function export(Request $request)
    {
        $this->checkAccess();

        $query = \App\Models\Keuangan::with('kegiatan');

        // Apply same filters as index
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%")
                    ->orWhere('jumlah', 'like', "%{$search}%")
                    ->orWhereHas('kegiatan', function ($q) use ($search) {
                    $q->where('judul', 'like', "%{$search}%");
                }
                );
            });
        }
        if ($request->filled('jenis')) {
            $query->where('jenis', $request->jenis);
        }
        if ($request->filled('kegiatan_id')) {
            $query->where('kegiatan_id', $request->kegiatan_id);
        }
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal', '<=', $request->tanggal_sampai);
        }
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal', $request->bulan);
        }
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal', $request->tahun);
        }

        $sortBy = $request->get('sort_by', 'tanggal');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder)->orderBy('id', 'desc');

        $results = $query->get();

        $fileName = 'Laporan-Keuangan-' . date('Y-m-d-His') . '.csv';

        $headers = [
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        ];

        $columns = ['Tanggal', 'Judul', 'Deskripsi', 'Jenis', 'Kegiatan', 'Jumlah (Rp)'];

        $callback = function () use ($results, $columns) {
            $file = fopen('php://output', 'w');

            // Add BOM for Excel UTF-8 support
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            fputcsv($file, $columns);

            foreach ($results as $row) {
                fputcsv($file, [
                    $row->tanggal,
                    $row->judul,
                    $row->deskripsi,
                    ucfirst($row->jenis),
                    $row->kegiatan ? $row->kegiatan->judul : '-',
                    $row->jumlah
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Keuangan $keuangan)
    {
        $this->checkAccess();

        // Log activity before delete
        ActivityLogService::logDelete('keuangan', $keuangan, request());

        $keuangan->delete();

        return redirect()->route('keuangan.index')
            ->with('toast', [
            'type' => 'success',
            'message' => 'Transaksi berhasil dihapus.',
        ]);
    }
}
