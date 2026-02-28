<?php

namespace App\Http\Controllers;

use App\Models\Dokumentasi;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class DokumentasiController extends Controller
{
    /**
     * Hanya Super Admin & Pengurus yang boleh upload/edit/hapus dokumentasi.
     * Anggota hanya boleh melihat (termasuk di workspace kegiatan).
     */
    private function canManageDokumentasi(): bool
    {
        $user = auth()->user();

        return $user && ($user->isSuperAdmin() || $user->isPengurus());
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = auth()->user();

        $query = \App\Models\Dokumentasi::with('kegiatan');

        // Anggota melihat semua dokumentasi (read-only); filter highlight dihilangkan agar transparan
        // Batasan hanya pada create/edit/delete

        // Search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('judul', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%")
                    ->orWhereHas('kegiatan', function ($q) use ($search) {
                        $q->where('judul', 'like', "%{$search}%");
                    });
            });
        }

        // Filter: Kegiatan
        if ($request->filled('kegiatan_id')) {
            $query->where('kegiatan_id', $request->kegiatan_id);
        }

        // Filter: Highlight
        if ($request->filled('highlight')) {
            $query->where('highlight', $request->highlight == '1');
        }

        // Filter: Tahun
        if ($request->filled('tahun')) {
            $query->whereYear('created_at', $request->tahun);
        }

        // Filter: Tanggal dari
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('created_at', '>=', $request->tanggal_dari);
        }

        // Filter: Tanggal sampai
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_sampai);
        }

        // Sort
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $dokumentasi = $query->paginate(12)->withQueryString();

        // Get filter options
        $kegiatanList = \App\Models\Kegiatan::orderBy('judul')->get();
        $years = \App\Models\Dokumentasi::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        return view('dashboard.dokumentasi.index', compact('dokumentasi', 'kegiatanList', 'years'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (! $this->canManageDokumentasi()) {
            abort(403, 'Akses ditolak. Hanya Super Admin dan Pengurus yang dapat mengupload dokumentasi.');
        }
        $kegiatan = \App\Models\Kegiatan::all();

        return view('dashboard.dokumentasi.create', compact('kegiatan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (! $this->canManageDokumentasi()) {
            abort(403, 'Akses ditolak. Hanya Super Admin dan Pengurus yang dapat mengupload dokumentasi.');
        }

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'file' => 'required|file|image|max:10240', // 10MB Max
            'kegiatan_id' => 'nullable|exists:kegiatans,id',
            'highlight' => 'nullable|boolean',
        ]);

        $data = $validated;
        
        // Handle file upload
        if ($request->hasFile('file')) {
            $data['file'] = $request->file('file')->store('dokumentasi', 'public');
        }

        // Handle highlight checkbox
        $data['highlight'] = $request->has('highlight');

        $dokumentasi = \App\Models\Dokumentasi::create($data);

        // Log activity (upload)
        ActivityLogService::log(
            action: 'upload',
            module: 'dokumentasi',
            targetId: $dokumentasi->id,
            description: 'Mengupload dokumentasi: '.($dokumentasi->judul ?? 'ID '.$dokumentasi->id),
            newValue: $dokumentasi->toArray(),
            request: $request
        );

        // Redirect ke workspace kegiatan jika upload dari workspace
        if ($request->get('redirect') === 'workspace' && $dokumentasi->kegiatan_id) {
            return redirect()->route('kegiatan.show', $dokumentasi->kegiatan_id)
                ->with('toast', ['type' => 'success', 'message' => 'Dokumentasi berhasil diupload.']);
        }

        return redirect()->route('dokumentasi.index')
            ->with('toast', [
                'type' => 'success',
                'message' => 'Dokumentasi berhasil diupload.',
            ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Dokumentasi $dokumentasi)
    {
        $dokumentasi->load('kegiatan');

        return view('dashboard.dokumentasi.show', compact('dokumentasi'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Dokumentasi $dokumentasi)
    {
        if (! $this->canManageDokumentasi()) {
            abort(403, 'Akses ditolak. Hanya Super Admin dan Pengurus yang dapat mengedit dokumentasi.');
        }
        $kegiatan = \App\Models\Kegiatan::all();

        return view('dashboard.dokumentasi.edit', compact('dokumentasi', 'kegiatan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Dokumentasi $dokumentasi)
    {
        if (! $this->canManageDokumentasi()) {
            abort(403, 'Akses ditolak. Hanya Super Admin dan Pengurus yang dapat mengedit dokumentasi.');
        }
        $oldData = $dokumentasi->toArray();
        $data = $request->all();
        if ($request->hasFile('file')) {
            $data['file'] = $request->file('file')->store('dokumentasi', 'public');
        }
        $data['highlight'] = $request->has('highlight');
        $dokumentasi->update($data);

        // Log activity
        ActivityLogService::logUpdate('dokumentasi', $dokumentasi, $oldData, $request);

        return redirect()->route('dokumentasi.index')
            ->with('toast', [
                'type' => 'success',
                'message' => 'Dokumentasi berhasil diupdate.',
            ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Dokumentasi $dokumentasi)
    {
        if (! $this->canManageDokumentasi()) {
            abort(403, 'Akses ditolak. Hanya Super Admin dan Pengurus yang dapat menghapus dokumentasi.');
        }
        // Log activity before delete
        ActivityLogService::logDelete('dokumentasi', $dokumentasi, request());

        $dokumentasi->delete();

        return redirect()->route('dokumentasi.index')
            ->with('toast', [
                'type' => 'success',
                'message' => 'Dokumentasi berhasil dihapus.',
            ]);
    }
}
