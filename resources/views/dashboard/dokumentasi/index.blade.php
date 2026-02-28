<x-admin-layout title="Dokumentasi">

<div class="flex flex-col gap-6">
    <div class="page-header">
        <div>
            <h1 class="page-header-title">Galeri Dokumentasi</h1>
            <p class="page-header-sub">Simpan dan pamerkan momen terbaik setiap kegiatan</p>
        </div>
        <a href="{{ route('dokumentasi.create') }}" data-turbo-frame="modal" class="btn-primary gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
            Upload Foto
        </a>
    </div>

    {{-- Search + Filter --}}
    <div class="card p-5"
         x-data="{ open: {{ request()->anyFilled(['kegiatan_id','highlight']) ? 'true' : 'false' }} }">
        <form method="GET" action="{{ route('dokumentasi.index') }}">
            <div class="flex items-center gap-2">
                <div class="relative flex-1">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-3.5 h-3.5 pointer-events-none text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari judul dokumentasi..." class="form-input pl-9">
                </div>
                <button type="button" @click="open = !open" class="btn-secondary gap-2 shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h18M7 9h10m-5 5h5"/></svg>
                    Filter
                </button>
                <button type="submit" class="btn-primary shrink-0">Cari</button>
            </div>
            <div x-show="open" x-transition class="grid grid-cols-2 gap-3 mt-3">
                <select name="kegiatan_id" class="form-select text-xs">
                    <option value="">Semua Program</option>
                    @foreach(\App\Models\Kegiatan::orderBy('judul')->get() as $kegiatan)
                        <option value="{{ $kegiatan->id }}" {{ request('kegiatan_id') == $kegiatan->id ? 'selected' : '' }}>{{ $kegiatan->judul }}</option>
                    @endforeach
                </select>
                <select name="highlight" class="form-select text-xs">
                    <option value="">Semua Tipe</option>
                    <option value="1" {{ request('highlight') === '1' ? 'selected' : '' }}>Highlight</option>
                    <option value="0" {{ request('highlight') === '0' ? 'selected' : '' }}>Normal</option>
                </select>
            </div>
        </form>
    </div>

    {{-- Photo Grid --}}
    @if($dokumentasi->count())
        <div class="max-h-[80vh] overflow-y-auto overflow-x-hidden pt-1 px-1 pb-10 custom-scrollbar rounded-2xl">
            <div class="grid grid-cols-2 sm:grid-cols-3 xl:grid-cols-4 gap-4">
                @foreach($dokumentasi as $item)
                    <div class="card-premium overflow-hidden group transition-all hover:-translate-y-1 hover:border-brand/40">
                        <div class="relative aspect-video bg-slate-100 dark:bg-slate-800">
                            @if($item->file)
                                <img src="{{ asset('storage/'.$item->file) }}"
                                     alt="{{ $item->judul }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-brand/5">
                                    <svg class="w-8 h-8 text-brand/40" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                </div>
                            @endif
                            {{-- Overlay on hover --}}
                            <div class="absolute inset-0 flex items-center justify-center gap-2 opacity-0 group-hover:opacity-100 transition-opacity bg-slate-900/60 backdrop-blur-sm">
                                <a href="{{ route('dokumentasi.show', $item->id) }}" class="btn-icon bg-white/10 hover:bg-white/20 text-white" title="Lihat">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </a>
                                <a href="{{ route('dokumentasi.edit', $item->id) }}" data-turbo-frame="modal" class="btn-icon bg-white/10 hover:bg-white/20 text-white" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form method="POST" action="{{ route('dokumentasi.destroy', $item->id) }}"
                                      onsubmit="event.preventDefault(); window.showModalConfirm(this, 'Hapus Foto', 'Apakah Anda yakin ingin menghapus foto dokumentasi ini?')">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="window.showModalConfirm(this.closest('form'), 'Hapus Foto', 'Apakah Anda yakin ingin menghapus foto dokumentasi ini?')" class="btn-icon bg-red-500/20 hover:bg-red-500/40 text-red-200" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </div>
                        <div class="p-4">
                            <p class="text-[14.5px] font-bold text-slate-800 dark:text-gray-100 truncate">{{ $item->judul }}</p>
                            @if($item->kegiatan)
                                <p class="text-[13px] font-medium mt-1 text-blue-500 truncate">{{ $item->kegiatan->judul }}</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @else
        <div class="card p-10 flex items-center justify-center">
            <div class="empty-state">
                <div class="empty-state-icon mx-auto w-12 h-12 flex items-center justify-center rounded-xl bg-slate-100 dark:bg-slate-800 mb-3">
                    <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                </div>
                <p class="empty-state-title text-center text-sm font-bold text-slate-900 dark:text-white">Belum ada dokumentasi</p>
                <p class="empty-state-sub text-center">Upload foto dari setiap kegiatan untuk membangun galeri organisasi</p>
                <div class="flex justify-center">
                    <a href="{{ route('dokumentasi.create') }}" data-turbo-frame="modal" class="btn-primary mt-6">Upload Foto Pertama</a>
                </div>
            </div>
        </div>
    @endif

    @if($dokumentasi instanceof \Illuminate\Pagination\LengthAwarePaginator && $dokumentasi->hasPages())
        <div class="mt-4">{{ $dokumentasi->withQueryString()->links() }}</div>
    @endif
</div>

</x-admin-layout>
