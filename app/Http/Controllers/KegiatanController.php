<?php

namespace App\Http\Controllers;

use App\Models\Kegiatan;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class KegiatanController extends Controller
{
    /**
     * Check if user can access kegiatan module
     */
    private function checkAccess()
    {
        $user = auth()->user();
        if (!$user || (!$user->isSuperAdmin() && !$user->isAdmin() && !$user->isPengurus())) {
            abort(403, 'Akses ditolak. Hanya Admin dan Pengurus yang dapat mengakses halaman ini.');
        }
    }

    /**
     * Semua anggota boleh melihat daftar kegiatan.
     */
    public function index(Request $request)
    {
        $query = Kegiatan::withCount([
            'tugas',
            'tugas as selesai_tugas_count' => function ($q) {
            $q->where('status', 'done');
        }
        ]);

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        // Filter: Status
        if ($request->filled('status')) {
            $now = now();
            if ($request->status === 'aktif') {
                $query->where('tanggal_mulai', '<=', $now)
                    ->where('tanggal_selesai', '>=', $now);
            }
            elseif ($request->status === 'selesai') {
                $query->where('tanggal_selesai', '<', $now);
            }
            elseif ($request->status === 'akan_datang') {
                $query->where('tanggal_mulai', '>', $now);
            }
        }

        // Filter: Tahun
        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_mulai', $request->tahun);
        }

        // Filter: Tanggal dari
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('tanggal_mulai', '>=', $request->tanggal_dari);
        }

        // Filter: Tanggal sampai
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('tanggal_selesai', '<=', $request->tanggal_sampai);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'tanggal_mulai');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder)->orderBy('id', 'desc');

        $kegiatans = $query->latest()->paginate(12)->withQueryString();

        // Get filter options
        $years = Kegiatan::selectRaw('YEAR(tanggal_mulai) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        $canManage = auth()->user() && (auth()->user()->isSuperAdmin() || auth()->user()->isPengurus());

        return view('dashboard.kegiatan.index', compact('kegiatans', 'years', 'canManage'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->checkAccess();

        return view('dashboard.kegiatan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->checkAccess();

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $kegiatan = Kegiatan::create($validated);

        // Log activity
        ActivityLogService::logCreate('kegiatan', $kegiatan, $request);

        return redirect()->route('kegiatan.index')
            ->with('toast', [
            'type' => 'success',
            'message' => 'Kegiatan berhasil ditambahkan.',
        ]);
    }

    /**
     * Semua anggota boleh membuka workspace (read-only untuk Anggota).
     */
    public function show(Kegiatan $kegiatan)
    {
        $kegiatan->loadCount([
            'tugas',
            'tugas as selesai_tugas_count' => function ($q) {
            $q->where('status', 'done');
        }
        ]);

        $canManage = auth()->user() && (auth()->user()->isSuperAdmin() || auth()->user()->isPengurus());

        return view('dashboard.kegiatan.show', compact('kegiatan', 'canManage'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kegiatan $kegiatan)
    {
        $this->checkAccess();

        return view('dashboard.kegiatan.edit', compact('kegiatan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kegiatan $kegiatan)
    {
        $this->checkAccess();

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
        ]);

        $oldData = $kegiatan->toArray();
        $kegiatan->update($validated);

        // Log activity
        ActivityLogService::logUpdate('kegiatan', $kegiatan, $oldData, $request);

        return redirect()->route('kegiatan.index')
            ->with('toast', [
            'type' => 'success',
            'message' => 'Kegiatan berhasil diupdate.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Kegiatan $kegiatan)
    {
        $this->checkAccess();

        // Log activity before delete
        ActivityLogService::logDelete('kegiatan', $kegiatan, request());

        $kegiatan->delete();

        return redirect()->route('kegiatan.index')
            ->with('toast', [
            'type' => 'success',
            'message' => 'Kegiatan berhasil dihapus.',
        ]);
    }

    /**
     * Tambah tugas dari workspace kegiatan (form internal).
     * Hanya Super Admin & Pengurus.
     */
    public function storeTugas(Request $request, Kegiatan $kegiatan)
    {
        $this->checkAccess();
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'deadline' => 'nullable|date',
            'status' => 'nullable|in:todo,done',
        ]);
        $validated['kegiatan_id'] = $kegiatan->id;
        $validated['status'] = $validated['status'] ?? 'todo';
        $tugas = \App\Models\Tugas::create($validated);
        ActivityLogService::logCreate('tugas', $tugas, $request);

        return redirect()->route('kegiatan.show', $kegiatan)
            ->with('toast', ['type' => 'success', 'message' => 'Tugas berhasil ditambahkan.']);
    }

    /**
     * Upload dokumentasi dari workspace (form internal). kegiatan_id otomatis.
     */
    public function storeDokumentasi(Request $request, Kegiatan $kegiatan)
    {
        $this->checkAccess();
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file' => 'required|file|image|max:10240',
        ]);
        $validated['kegiatan_id'] = $kegiatan->id;
        $validated['highlight'] = false;
        if ($request->hasFile('file')) {
            $validated['file'] = $request->file('file')->store('dokumentasi', 'public');
        }
        $dokumentasi = \App\Models\Dokumentasi::create($validated);
        ActivityLogService::logCreate('dokumentasi', $dokumentasi, $request);

        return redirect()->route('kegiatan.show', [$kegiatan, 'tab' => 'dokumentasi'])
            ->with('toast', ['type' => 'success', 'message' => 'Dokumentasi berhasil diupload.']);
    }

    /**
     * Tambah anggota kegiatan (manual nama + jabatan). Form modal workspace.
     */
    public function storeAnggota(Request $request, Kegiatan $kegiatan)
    {
        $this->checkAccess();
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|in:Ketua,Wakil,Sekretaris,Bendahara,Anggota',
        ]);
        $kegiatan->anggotaList()->create($validated);

        return redirect()->route('kegiatan.show', [$kegiatan, 'tab' => 'anggota'])
            ->with('toast', ['type' => 'success', 'message' => 'Anggota berhasil ditambahkan.']);
    }

    /**
     * Update anggota kegiatan (nama + jabatan).
     */
    public function updateAnggota(Request $request, Kegiatan $kegiatan, \App\Models\KegiatanAnggota $anggota)
    {
        $this->checkAccess();
        if ($anggota->kegiatan_id != $kegiatan->id) {
            abort(404);
        }
        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'jabatan' => 'required|string|in:Ketua,Wakil,Sekretaris,Bendahara,Anggota',
        ]);
        $anggota->update($validated);

        return redirect()->route('kegiatan.show', [$kegiatan, 'tab' => 'anggota'])
            ->with('toast', ['type' => 'success', 'message' => 'Anggota berhasil diupdate.']);
    }

    /**
     * Hapus anggota dari kegiatan.
     */
    public function destroyAnggota(Kegiatan $kegiatan, \App\Models\KegiatanAnggota $anggota)
    {
        $this->checkAccess();
        if ($anggota->kegiatan_id != $kegiatan->id) {
            abort(404);
        }
        $anggota->delete();

        return redirect()->route('kegiatan.show', [$kegiatan, 'tab' => 'anggota'])
            ->with('toast', ['type' => 'success', 'message' => 'Anggota dihapus dari kegiatan.']);
    }

    /**
     * Upload arsip dari workspace (form internal). kegiatan_id otomatis.
     */
    public function storeArsip(Request $request, Kegiatan $kegiatan)
    {
        $this->checkAccess();
        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'file' => 'required|file|max:20480', // 20MB max
        ]);
        $validated['kegiatan_id'] = $kegiatan->id;

        if ($request->hasFile('file')) {
            $validated['file'] = $request->file('file')->store('arsip', 'public');
        }

        $arsip = \App\Models\Arsip::create($validated);
        \App\Services\ActivityLogService::logCreate('arsip', $arsip, $request);

        return redirect()->route('kegiatan.show', [$kegiatan, 'tab' => 'arsip'])
            ->with('toast', ['type' => 'success', 'message' => 'Arsip berhasil diupload.']);
    }
}
