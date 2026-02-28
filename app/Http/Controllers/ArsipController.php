<?php

namespace App\Http\Controllers;

use App\Models\Arsip;
use App\Services\ActivityLogService;
use Illuminate\Http\Request;

class ArsipController extends Controller
{
    /**
     * Check if user can access arsip module
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

        // Dynamic Pagination (Desktop: 9, Mobile: 10)
        $isMobile = preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", request()->header('User-Agent'));
        $perPage = $isMobile ? 10 : 9;

        $query = \App\Models\Arsip::with('kegiatan')->withCount('attachments');

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

        // Sort
        $query->orderByDesc('created_at')->orderByDesc('id');

        $arsip = $query->paginate($perPage)->withQueryString();
        
        $kegiatanList = \App\Models\Kegiatan::orderBy('judul')->get();

        return view('dashboard.arsip.index', compact('arsip', 'kegiatanList'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $this->checkAccess();
        $kegiatan = \App\Models\Kegiatan::all();

        return view('dashboard.arsip.create', compact('kegiatan'));
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
            'kegiatan_id' => 'nullable|exists:kegiatans,id',
        ]);

        $arsip = \App\Models\Arsip::create($validated);

        // Log activity
        ActivityLogService::logCreate('arsip', $arsip, $request);

        return redirect()->route('arsip.show', $arsip->id)
            ->with('toast', [
            'type' => 'success',
            'message' => 'Folder Arsip berhasil dibuat. Silakan tambahkan file di bawah.',
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Arsip $arsip)
    {
        $this->checkAccess();
        $arsip->load(['kegiatan', 'attachments' => function ($q) {
            $q->latest();
        }]);

        return view('dashboard.arsip.show', compact('arsip'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Arsip $arsip)
    {
        $this->checkAccess();
        $kegiatan = \App\Models\Kegiatan::all();

        return view('dashboard.arsip.edit', compact('arsip', 'kegiatan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Arsip $arsip)
    {
        $this->checkAccess();

        $validated = $request->validate([
            'judul' => 'required|string|max:255',
            'deskripsi' => 'nullable|string',
            'kegiatan_id' => 'nullable|exists:kegiatans,id',
        ]);

        $oldData = $arsip->toArray();
        $arsip->update($validated);

        // Log activity
        ActivityLogService::logUpdate('arsip', $arsip, $oldData, $request);

        return redirect()->route('arsip.show', $arsip->id)
            ->with('toast', [
            'type' => 'success',
            'message' => 'Arsip berhasil diupdate.',
        ]);
    }

    /**
     * Store new attachment to existing arsip
     */
    public function storeAttachment(Request $request, Arsip $arsip)
    {
        $this->checkAccess();

        $request->validate([
            'file' => 'required|file|max:20480', // 20MB Max per file
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = $file->getClientOriginalName();
            $fileSize = $file->getSize();
            $fileType = $file->getClientOriginalExtension();
            $path = $file->store('arsip/attachments', 'public');

            $arsip->attachments()->create([
                'file_path' => $path,
                'file_name' => $fileName,
                'file_size' => $fileSize,
                'file_type' => $fileType,
            ]);

            return redirect()->back()->with('toast', [
                'type' => 'success',
                'message' => 'File berhasil ditambahkan ke arsip.',
            ]);
        }

        return redirect()->back()->with('toast', [
            'type' => 'error',
            'message' => 'Gagal mengunggah file.',
        ]);
    }

    /**
     * Delete attachment
     */
    public function destroyAttachment(Arsip $arsip, \App\Models\ArsipAttachment $attachment)
    {
        $this->checkAccess();

        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($attachment->file_path)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($attachment->file_path);
        }

        $attachment->delete();

        return redirect()->back()->with('toast', [
            'type' => 'success',
            'message' => 'File berhasil dihapus dari arsip.',
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Arsip $arsip)
    {
        $this->checkAccess();

        // Log activity before delete
        ActivityLogService::logDelete('arsip', $arsip, request());

        $arsip->delete();

        return redirect()->back()
            ->with('toast', [
            'type' => 'success',
            'message' => 'Arsip berhasil dihapus.',
        ]);
    }
}
