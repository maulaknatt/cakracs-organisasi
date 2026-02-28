<x-admin-layout title="Arsip">
<div class="flex flex-col gap-6">

<div class="page-header">
    <div>
        <h1 class="page-header-title">Arsip Dokumen</h1>
        <p class="page-header-sub">Museum digital perjalanan dan sejarah organisasi</p>
    </div>
    <a href="{{ route('arsip.create') }}" data-turbo-frame="modal" class="btn-primary gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
        Simpan Arsip
    </a>
</div>

{{-- Search + Filter --}}
<div class="card p-5 mb-5"
     x-data="{ open: {{ request()->anyFilled(['kegiatan_id']) ? 'true' : 'false' }} }">
    <form method="GET" action="{{ route('arsip.index') }}">
        <div class="flex items-center gap-2">
            <div class="relative flex-1">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 pointer-events-none text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari judul atau program..." class="form-input pl-9">
            </div>
            <button type="button" @click="open = !open" class="btn-secondary gap-2 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h18M7 9h10m-5 5h5"/></svg>
                Filter
            </button>
            <button type="submit" class="btn-primary shrink-0">Cari</button>
        </div>
        <div x-show="open" x-transition class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-3">
            <select name="kegiatan_id" class="form-select text-xs">
                <option value="">Semua Program / Kegiatan</option>
                @foreach($kegiatanList as $kegiatan)
                    <option value="{{ $kegiatan->id }}" {{ request('kegiatan_id') == $kegiatan->id ? 'selected' : '' }}>{{ $kegiatan->judul }}</option>
                @endforeach
            </select>
        </div>
    </form>
</div>

{{-- Grid --}}
@if($arsip->count())
    <div class="max-h-[80vh] overflow-y-auto overflow-x-hidden pt-1 px-1 pb-10 custom-scrollbar rounded-2xl">
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
        @foreach($arsip as $item)
            <div class="card-premium flex flex-col group relative overflow-hidden transition-all hover:-translate-y-1">
                <div class="flex items-start justify-between gap-3 mb-5">
                    <div class="w-12 h-12 rounded-xl flex items-center justify-center flex-shrink-0 bg-blue-500/10 text-blue-500">
                        <svg class="w-5.5 h-5.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.75" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9l-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <div class="flex items-center gap-1.5 opacity-0 group-hover:opacity-100 transition-opacity">
                        <a href="{{ route('arsip.show', $item->id) }}" class="btn-icon" title="Buka">
                            <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </a>
                        <a href="{{ route('arsip.edit', $item->id) }}" data-turbo-frame="modal" class="btn-icon" title="Edit">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                        <form method="POST" action="{{ route('arsip.destroy', $item->id) }}" onsubmit="event.preventDefault(); window.showModalConfirm(this, 'Hapus Arsip', 'Hapus arsip ini beserta file yang terlampir di dalamnya?')">
                            @csrf @method('DELETE')
                            <button type="button" onclick="window.showModalConfirm(this.closest('form'), 'Hapus Arsip', 'Hapus arsip ini beserta file yang terlampir di dalamnya?')" class="btn-icon text-red-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-500/10" title="Hapus">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>

                <a href="{{ route('arsip.show', $item->id) }}" class="block mb-2.5 hover:text-blue-500 transition-colors">
                    <h3 class="text-[16px] font-bold text-slate-900 dark:text-gray-100 line-clamp-2 leading-tight">
                        {{ $item->judul }}
                    </h3>
                </a>

                @if($item->deskripsi)
                    <p class="text-[13.5px] text-slate-500 dark:text-slate-400 line-clamp-2 mb-4">{{ $item->deskripsi }}</p>
                @endif

                @if($item->kegiatan)
                    <span class="badge badge-indigo mb-2">{{ Str::limit($item->kegiatan->judul, 30) }}</span>
                @endif

                <p class="text-[13px] mt-2 text-slate-500 dark:text-slate-400">
                    {{ \Carbon\Carbon::parse($item->tanggal ?? $item->created_at)->translatedFormat('d F Y') }}
                </p>
            </div>
        @endforeach
        </div>
    </div>
@else
    <div class="card">
        <div class="empty-state">
            <div class="empty-state-icon">
                <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 19a2 2 0 01-2-2V7a2 2 0 012-2h4l2 2h4a2 2 0 012 2v1M5 19h14a2 2 0 002-2v-5a2 2 0 00-2-2H9l-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                </svg>
            </div>
            <p class="empty-state-title">Belum ada arsip</p>
            <p class="empty-state-sub">Simpan dokumen dan momen penting organisasi di sini</p>
            <a href="{{ route('arsip.create') }}" data-turbo-frame="modal" class="btn-primary mt-6">Simpan Arsip Pertama</a>
        </div>
    </div>
@endif

@if($arsip instanceof \Illuminate\Pagination\LengthAwarePaginator && $arsip->hasPages())
    <div class="mt-6">{{ $arsip->withQueryString()->links() }}</div>
@endif

</div>{{-- end flex flex-col gap-6 --}}
</x-admin-layout>
