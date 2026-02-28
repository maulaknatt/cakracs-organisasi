<x-admin-layout title="Program Kerja & Kegiatan">
    
    {{-- Action Bar --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-black text-slate-900 dark:text-white tracking-tight">Program Kerja</h1>
            <p class="text-sm font-medium text-slate-500 mt-1">Kelola dan pantau seluruh agenda organisasi Anda.</p>
        </div>
        
        <div class="flex items-center gap-2">
            <x-ui.button variant="secondary" size="md" icon='<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 4.5h14.25M3 9h9.75M3 13.5h9.75m4.5-4.5v12m0 0l-3.75-3.75M17.25 21L21 17.25"/></svg>'>
                Filter
            </x-ui.button>
            <a href="{{ route('kegiatan.create') }}" data-turbo-frame="modal">
                <x-ui.button variant="primary" size="md" icon='<svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.5v15m7.5-7.5h-15"/></svg>'>
                    Tambah Program
                </x-ui.button>
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/20 rounded-xl flex items-center gap-3 animate-slide-in">
            <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-lg shadow-emerald-500/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
            </div>
            <p class="text-sm font-bold text-emerald-800 dark:text-emerald-400">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Kegiatan Grid Scrollable Container --}}
    <div class="max-h-[75vh] overflow-y-auto overflow-x-hidden pt-1 px-1 pb-10 custom-scrollbar rounded-2xl">
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-6">
            @forelse($kegiatans as $kegiatan)
                <x-ui.card class="group flex flex-col h-full hover:border-brand/40 hover:shadow-premium-md transition-all duration-300">
                    <div class="flex-1">
                        <div class="flex items-start justify-between gap-4 mb-4">
                            <div class="p-2.5 rounded-xl bg-slate-50 dark:bg-slate-800 border border-slate-100 dark:border-slate-700 group-hover:bg-brand/5 group-hover:border-brand/20 transition-colors">
                                <svg class="w-5 h-5 text-slate-400 group-hover:text-brand transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            </div>
                            
                            @php
                                $isPast = \Carbon\Carbon::parse($kegiatan->tanggal)->isPast();
                            @endphp
                            <span @class([
                                "px-2 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest border",
                                "bg-blue-50 text-blue-600 border-blue-100 dark:bg-blue-500/10 dark:text-blue-400 dark:border-blue-500/20" => !$isPast,
                                "bg-slate-50 text-slate-500 border-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:border-slate-700" => $isPast,
                            ])>
                                {{ $isPast ? 'Selesai' : 'Mendatang' }}
                            </span>
                        </div>

                        <h3 class="text-lg font-bold text-slate-900 dark:text-white line-clamp-2 leading-snug mb-2 group-hover:text-brand transition-colors">
                            {{ $kegiatan->judul }}
                        </h3>
                        
                        <div class="flex items-center gap-2 text-xs font-medium text-slate-500 mb-6">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            {{ \Carbon\Carbon::parse($kegiatan->tanggal)->translatedFormat('d F Y') }}
                        </div>
                    </div>

                    <div class="flex items-center gap-2 mt-auto pt-6 border-t border-slate-50 dark:border-slate-800/50">
                        <a href="{{ route('kegiatan.show', $kegiatan->id) }}" class="flex-1">
                            <x-ui.button variant="secondary" size="sm" class="w-full">Workspace</x-ui.button>
                        </a>
                        
                        <div class="flex items-center gap-1">
                            <a href="{{ route('kegiatan.edit', $kegiatan->id) }}" data-turbo-frame="modal" title="Edit">
                                <x-ui.button variant="ghost" size="sm" class="px-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                                </x-ui.button>
                            </a>
                            
                            <form action="{{ route('kegiatan.destroy', $kegiatan->id) }}" method="POST" class="inline-block">
                                @csrf
                                @method('DELETE')
                                <button type="button" 
                                        onclick="window.showModalConfirm(this.closest('form'), 'Hapus Program?', 'Apakah Anda yakin? Seluruh data terkait program ini akan dihapus secara permanen.', 'Hapus Permanen', 'Batal')"
                                        class="p-2 text-slate-400 hover:text-red-600 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-lg transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </x-ui.card>
            @empty
                <div class="col-span-full flex flex-col items-center justify-center py-20 bg-slate-50 dark:bg-slate-900/50 rounded-3xl border-2 border-dashed border-slate-200 dark:border-slate-800">
                    <div class="w-20 h-20 bg-white dark:bg-slate-800 rounded-3xl shadow-premium flex items-center justify-center mb-6">
                        <svg class="w-10 h-10 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" /></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white">Belum Ada Program</h3>
                    <p class="text-sm text-slate-500 mt-2 mb-8">Mulailah dengan membuat program kerja pertama Anda hari ini.</p>
                    <a href="{{ route('kegiatan.create') }}" data-turbo-frame="modal">
                        <x-ui.button variant="primary" size="lg">Buat Program Sekarang</x-ui.button>
                    </a>
                </div>
            @endforelse
        </div>
        
        @if($kegiatans instanceof \Illuminate\Pagination\LengthAwarePaginator && $kegiatans->hasPages())
            <div class="mt-4">{{ $kegiatans->withQueryString()->links() }}</div>
        @endif
    </div>

    <turbo-frame id="modal"></turbo-frame>
</x-admin-layout>
