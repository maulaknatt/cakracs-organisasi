<x-admin-layout title="Program Kerja & Kegiatan">
<div class="flex flex-col gap-8">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 animate-fade-in">
        <div>
            <h1 class="text-3xl font-black text-slate-900 dark:text-white tracking-tight">Program Kerja</h1>
            <p class="text-slate-500 dark:text-slate-400 font-medium mt-1">Kelola dan pantau seluruh agenda organisasi Anda</p>
        </div>
        <a href="{{ route('kegiatan.create') }}" data-turbo-frame="modal"
           class="btn-primary flex items-center justify-center gap-2 group">
            <svg class="w-5 h-5 transition-transform group-hover:rotate-90" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
            <span>Tambah Program</span>
        </a>
    </div>

    @if(session('success'))
        <div class="p-4 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/20 rounded-xl flex items-center gap-3 animate-fade-in">
            <div class="w-8 h-8 rounded-full bg-emerald-500 text-white flex items-center justify-center shrink-0 shadow-lg shadow-emerald-500/20">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
            </div>
            <p class="text-sm font-bold text-emerald-800 dark:text-emerald-400">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Cards Grid with Scrollable Content --}}
    <div class="max-h-[640px] overflow-y-auto px-1 -mx-1 custom-scrollbar scroll-smooth">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 pb-6">
        @forelse($kegiatans as $kegiatan)
            @php
                $isPast = \Carbon\Carbon::parse($kegiatan->tanggal)->isPast();
                $progress = (int)($kegiatan->progress ?? 0);
                $tugasCount = $kegiatan->tugas()->count();
                $tugasDone  = $kegiatan->tugas()->where('status','done')->count();
                $anggotaCount = $kegiatan->anggotaList()->count();

                if ($kegiatan->status === 'selesai' || $isPast) {
                    $statusLabel = 'Selesai';
                    $statusColor = 'bg-blue-500';
                    $borderColor = 'border-t-blue-400';
                    $badgeBg     = 'bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400 border-blue-200 dark:border-blue-500/20';
                } elseif ($kegiatan->status === 'aktif') {
                    $statusLabel = 'Aktif';
                    $statusColor = 'bg-blue-500';
                    $borderColor = 'border-t-blue-500';
                    $badgeBg     = 'bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400 border-blue-200 dark:border-blue-500/20';
                } else {
                    $statusLabel = 'Mendatang';
                    $statusColor = 'bg-blue-500';
                    $borderColor = 'border-t-indigo-500';
                    $badgeBg     = 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-700 dark:text-indigo-400 border-indigo-200 dark:border-indigo-500/20';
                }
            @endphp

            <div class="card !p-0 overflow-hidden h-full flex flex-col group !bg-white dark:!bg-[#171e30]/60 !border-0 !border-t-4 {{ $borderColor }} !rounded-2xl !shadow-md hover:!shadow-xl transition-all duration-300 relative cursor-pointer"
                 onclick="if(!event.target.closest('button') && !event.target.closest('a.action-btn')) window.location='{{ route('kegiatan.show', $kegiatan->id) }}'">

                <div class="p-6 flex flex-col flex-1">
                    {{-- Top: Icon + Status --}}
                    <div class="flex items-start justify-between mb-5">
                        <div class="w-11 h-11 rounded-xl flex items-center justify-center {{ $statusColor }}/10 border border-current/10">
                            <svg class="w-5 h-5 {{ str_replace('bg-', 'text-', $statusColor) }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                        </div>

                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[11px] font-bold uppercase tracking-wider border {{ $badgeBg }}">
                            @if($kegiatan->status === 'aktif')
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                            @endif
                            {{ $statusLabel }}
                        </span>
                    </div>

                    {{-- Title --}}
                    <h3 class="text-lg font-black text-slate-900 dark:text-white leading-tight mb-2 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors line-clamp-2">
                        {{ $kegiatan->judul }}
                    </h3>

                    {{-- Date --}}
                    <div class="flex items-center gap-2 text-[13px] text-slate-500 dark:text-slate-400 mb-4">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="font-medium">{{ \Carbon\Carbon::parse($kegiatan->tanggal)->translatedFormat('d F Y') }}</span>
                    </div>

                    {{-- Description snippet --}}
                    @if($kegiatan->deskripsi)
                        <p class="text-[13px] text-slate-500 dark:text-slate-400 line-clamp-2 mb-5 leading-relaxed">{{ Str::limit($kegiatan->deskripsi, 100) }}</p>
                    @endif

                    {{-- Progress bar --}}
                    <div class="mt-auto mb-5">
                        <div class="flex items-center justify-between text-[11px] mb-1.5">
                            <span class="font-semibold text-slate-400 dark:text-slate-500 uppercase tracking-wider">Progress</span>
                            <span class="font-bold text-slate-700 dark:text-slate-300">{{ $progress }}%</span>
                        </div>
                        <div class="w-full rounded-full h-1.5 bg-slate-100 dark:bg-white/5">
                            <div class="h-1.5 rounded-full transition-all duration-700"
                                 style="width:{{ $progress }}%; background:linear-gradient(90deg,#3b82f6,#6366f1);"></div>
                        </div>
                    </div>

                    {{-- Stats row --}}
                    <div class="flex items-center gap-4 text-[12px] font-bold text-slate-400 dark:text-slate-500 mb-5">
                        <span class="flex items-center gap-1.5" title="Total tugas">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                            {{ $tugasDone }}/{{ $tugasCount }} tugas
                        </span>
                        <span class="flex items-center gap-1.5" title="Jumlah panitia">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            {{ $anggotaCount }} panitia
                        </span>
                    </div>

                    {{-- Footer: Actions --}}
                    <div class="flex items-center gap-2 pt-4 border-t border-slate-100 dark:border-white/5">
                        <a href="{{ route('kegiatan.show', $kegiatan->id) }}"
                           class="action-btn flex-1 h-9 flex items-center justify-center gap-2 rounded-lg text-[12px] font-bold bg-blue-600 text-white hover:bg-blue-700 shadow-sm transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            Workspace
                        </a>

                        <a href="{{ route('kegiatan.edit', $kegiatan->id) }}" data-turbo-frame="modal"
                           class="action-btn w-9 h-9 flex items-center justify-center rounded-lg border border-slate-200 dark:border-white/10 text-slate-400 hover:text-blue-500 hover:border-blue-300 dark:hover:border-blue-500/40 hover:bg-blue-50 dark:hover:bg-blue-500/10 transition-all"
                           title="Edit">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>

                        <form action="{{ route('kegiatan.destroy', $kegiatan->id) }}" method="POST" class="inline-block">
                            @csrf @method('DELETE')
                            <button type="button"
                                    onclick="window.showModalConfirm(this.closest('form'), 'Hapus Program?', 'Apakah Anda yakin? Seluruh data terkait program ini akan dihapus secara permanen.', 'Hapus Permanen', 'Batal')"
                                    class="action-btn w-9 h-9 flex items-center justify-center rounded-lg border border-slate-200 dark:border-white/10 text-slate-400 hover:text-rose-500 hover:border-rose-300 dark:hover:border-rose-500/40 hover:bg-rose-50 dark:hover:bg-rose-500/10 transition-all">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full py-20 text-center animate-fade-in">
                <div class="w-20 h-20 bg-slate-50 dark:bg-white/5 rounded-3xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <h3 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">Belum Ada Program</h3>
                <p class="text-slate-500 dark:text-slate-400 font-medium mt-1">Mulailah dengan membuat program kerja pertama Anda hari ini.</p>
                <a href="{{ route('kegiatan.create') }}" data-turbo-frame="modal" class="btn-primary mt-6 inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                    Buat Program Sekarang
                </a>
            </div>
        @endforelse
        </div>
    </div>

    @if($kegiatans instanceof \Illuminate\Pagination\LengthAwarePaginator && $kegiatans->hasPages())
        <div class="mt-5">{{ $kegiatans->withQueryString()->links() }}</div>
    @endif

</div>
</x-admin-layout>
