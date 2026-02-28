<?php

namespace App\Http\Controllers;

use App\Models\Tugas;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class TugasController extends Controller
{
    /**
     * Hanya Super Admin & Pengurus yang boleh tambah/edit/hapus tugas.
     * Anggota hanya boleh lihat (termasuk tugas yang diassign ke mereka dan tugas kegiatan yang diikuti).
     */
    private function canManageTugas(): bool
    {
        $user = auth()->user();

        return $user && ($user->isSuperAdmin() || $user->isAdmin() || $user->isPengurus());
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        // Base query
        $query = \App\Models\Tugas::with(['kegiatan']);

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
            $query->where('status', $request->status);
        }


        // Filter: Kegiatan
        if ($request->filled('kegiatan_id')) {
            $query->where('kegiatan_id', $request->kegiatan_id);
        }

        // Filter: Deadline dari
        if ($request->filled('deadline_dari')) {
            $query->whereDate('deadline', '>=', $request->deadline_dari);
        }

        // Filter: Deadline sampai
        if ($request->filled('deadline_sampai')) {
            $query->whereDate('deadline', '<=', $request->deadline_sampai);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'id');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $perPage = $request->get('per_page', 10);
        $tugas = $query->latest()->paginate($perPage)->withQueryString();

        $kegiatanList = \App\Models\Kegiatan::all();

        return view('dashboard.tugas.index', compact(
            'tugas',
            'kegiatanList'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!$this->canManageTugas()) {
            abort(403, 'Akses ditolak. Hanya Super Admin dan Pengurus yang dapat menambah tugas.');
        }
        $kegiatanList = \App\Models\Kegiatan::orderBy('judul')->get();

        return view('dashboard.tugas.create', compact('kegiatanList'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!$this->canManageTugas()) {
            abort(403, 'Akses ditolak. Hanya Super Admin dan Pengurus yang dapat menambah tugas.');
        }

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'penanggung_jawab' => 'nullable|exists:users,id',
            'deadline' => 'nullable|date',
            'status' => 'required|in:todo,progress,done',
            'kegiatan_id' => 'nullable|exists:kegiatans,id',
        ]);

        $tugas = \App\Models\Tugas::create($validated);

        // Log activity
        ActivityLogService::logCreate('tugas', $tugas, $request);

        return redirect()->route('tugas.index')
            ->with('toast', [
            'type' => 'success',
            'message' => 'Tugas berhasil ditambahkan.',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Tugas $tugas)
    {
        return view('dashboard.tugas.show', compact('tugas'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tugas $tugas)
    {
        if (!$this->canManageTugas()) {
            abort(403, 'Akses ditolak. Hanya Super Admin dan Pengurus yang dapat mengedit tugas.');
        }
        $kegiatanList = \App\Models\Kegiatan::orderBy('judul')->get();

        return view('dashboard.tugas.edit', compact('tugas', 'kegiatanList'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tugas $tugas)
    {
        if (!$this->canManageTugas()) {
            abort(403, 'Akses ditolak. Hanya Super Admin dan Pengurus yang dapat mengedit tugas.');
        }

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'penanggung_jawab' => 'nullable|exists:users,id',
            'deadline' => 'nullable|date',
            'status' => 'required|in:todo,progress,done',
            'kegiatan_id' => 'nullable|exists:kegiatans,id',
        ]);

        $oldData = $tugas->toArray();
        $tugas->update($validated);

        // Log activity
        ActivityLogService::logUpdate('tugas', $tugas, $oldData, $request);

        return redirect()->route('tugas.index')
            ->with('toast', [
            'type' => 'success',
            'message' => 'Tugas berhasil diupdate.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tugas $tugas)
    {
        if (!$this->canManageTugas()) {
            abort(403, 'Akses ditolak. Hanya Super Admin dan Pengurus yang dapat menghapus tugas.');
        }
        // Log activity before delete
        ActivityLogService::logDelete('tugas', $tugas, request());

        $tugas->delete();

        return redirect()->route('tugas.index')
            ->with('toast', [
            'type' => 'success',
            'message' => 'Tugas berhasil dihapus.',
        ]);
    }
}
