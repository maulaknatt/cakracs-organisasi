<x-admin-layout title="Detail Tugas">

    <div class="max-w-3xl mx-auto">

        {{-- Back --}}
        <div class="mb-6">
            <a href="{{ route('tugas.index') }}" class="inline-flex items-center gap-2 text-[14px] font-semibold text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Daftar Tugas
            </a>
        </div>

        {{-- Task Card --}}
        <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700/60 rounded-3xl overflow-hidden shadow-xl shadow-slate-200/50 dark:shadow-none mb-5">

            {{-- Top Color Bar based on status --}}
            <div class="h-1.5 {{ $tugas->status === 'done' ? 'bg-gradient-to-r from-emerald-400 to-teal-500' : (\Carbon\Carbon::parse($tugas->deadline)->isPast() ? 'bg-gradient-to-r from-red-400 to-rose-500' : 'bg-gradient-to-r from-blue-400 to-indigo-500') }}"></div>

            {{-- Header --}}
            <div class="px-8 pt-7 pb-6 border-b border-slate-100 dark:border-slate-700/50">
                <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-4">
                    <div class="flex-1 min-w-0">
                        {{-- Status + Program badges --}}
                        <div class="flex flex-wrap items-center gap-2 mb-4">
                            @if($tugas->status === 'done')
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[12.5px] font-bold bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Sudah Selesai
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[12.5px] font-bold bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400 border border-amber-200 dark:border-amber-500/20">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                    Belum Selesai
                                </span>
                            @endif

                            @if($tugas->kegiatan)
                                <span class="px-3 py-1.5 rounded-full text-[12.5px] font-semibold bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400 border border-blue-100 dark:border-blue-500/20">
                                    📋 {{ $tugas->kegiatan->judul }}
                                </span>
                            @endif

                            @if($tugas->deadline && \Carbon\Carbon::parse($tugas->deadline)->isPast() && $tugas->status !== 'done')
                                <span class="px-3 py-1.5 rounded-full text-[12.5px] font-bold bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 border border-red-100 dark:border-red-500/20">
                                    ⚠️ Terlambat
                                </span>
                            @endif
                        </div>

                        <h1 class="text-[24px] sm:text-[28px] font-black text-slate-900 dark:text-white tracking-tight leading-tight mb-3">
                            {{ $tugas->judul }}
                        </h1>

                        {{-- Deadline info --}}
                        @if($tugas->deadline)
                            <div class="flex items-center gap-2 {{ \Carbon\Carbon::parse($tugas->deadline)->isPast() && $tugas->status !== 'done' ? 'text-red-500' : 'text-slate-500 dark:text-slate-400' }}">
                                <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-[14px] font-semibold">
                                    Deadline: {{ \Carbon\Carbon::parse($tugas->deadline)->translatedFormat('d F Y') }}
                                    @if(\Carbon\Carbon::parse($tugas->deadline)->isPast() && $tugas->status !== 'done')
                                        ({{ \Carbon\Carbon::parse($tugas->deadline)->diffForHumans() }})
                                    @else
                                        ({{ \Carbon\Carbon::parse($tugas->deadline)->diffForHumans() }})
                                    @endif
                                </span>
                            </div>
                        @endif
                    </div>

                    {{-- Edit Button --}}
                    <a href="{{ route('tugas.edit', $tugas->id) }}" data-turbo-frame="modal" class="btn-secondary shrink-0 gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        Edit Tugas
                    </a>
                </div>
            </div>

            {{-- Description --}}
            <div class="px-8 py-7">
                @if($tugas->deskripsi)
                    <div class="mb-6">
                        <h2 class="text-[12px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 mb-4">Deskripsi Tugas</h2>
                        <div class="text-[15.5px] text-slate-600 dark:text-slate-300 leading-relaxed whitespace-pre-line">
                            {{ $tugas->deskripsi }}
                        </div>
                    </div>
                @else
                    <div class="py-8 text-center rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-dashed border-slate-200 dark:border-slate-700">
                        <svg class="w-10 h-10 text-slate-300 dark:text-slate-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="text-[14px] text-slate-400 font-medium">Tidak ada deskripsi</p>
                    </div>
                @endif
            </div>

            {{-- Program Info (if linked) --}}
            @if($tugas->kegiatan)
                <div class="mx-8 mb-8 p-5 rounded-2xl bg-blue-50 dark:bg-blue-500/10 border border-blue-100 dark:border-blue-500/20">
                    <p class="text-[11.5px] font-black uppercase tracking-widest text-blue-400 dark:text-blue-500 mb-2">Terkait dengan Program</p>
                    <a href="{{ route('kegiatan.show', $tugas->kegiatan->id) }}"
                       class="inline-flex items-center gap-2 text-[15px] font-bold text-blue-600 dark:text-blue-400 hover:underline">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                        </svg>
                        {{ $tugas->kegiatan->judul }}
                    </a>
                </div>
            @endif
        </div>

    </div>
    <turbo-frame id="modal"></turbo-frame>
</x-admin-layout>
