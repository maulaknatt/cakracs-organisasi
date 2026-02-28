@section('title', 'Detail Dokumentasi - ' . $dokumentasi->judul)

<x-admin-layout title="Dashboard">
<div class="max-w-4xl mx-auto">
    {{-- Breadcrumbs & Actions --}}
    <div class="flex items-center justify-between mb-6">
        <a href="{{ route('dokumentasi.index') }}" class="back-link">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Dokumentasi
        </a>

    </div>

    {{-- Main Content Card --}}
    <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-sm overflow-hidden border-t-4 border-t-blue-600 dark:border-t-blue-500">
        <div class="p-8">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-10">
                {{-- Left Side: Preview --}}
                <div class="space-y-4">
                    <div class="relative group rounded-2xl overflow-hidden bg-slate-100 dark:bg-slate-900 border border-slate-200 dark:border-slate-700 shadow-inner aspect-[4/3]">
                        <img src="{{ asset('storage/'.$dokumentasi->file) }}" class="w-full h-full object-contain" alt="{{ $dokumentasi->judul }}">
                        <div class="absolute inset-x-0 bottom-0 p-4 bg-gradient-to-t from-black/60 to-transparent opacity-0 group-hover:opacity-100 transition-opacity">
                            <p class="text-[10px] text-white/80 font-medium">Klik tombol di kanan untuk mengunduh</p>
                        </div>
                    </div>
                </div>

                {{-- Right Side: Info & Actions --}}
                <div class="flex flex-col justify-between">
                    <div class="space-y-6">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <span class="px-2 py-0.5 bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400 text-[10px] font-bold uppercase tracking-wider rounded">Foto Dokumentasi</span>
                                @if($dokumentasi->highlight)
                                    <span class="px-2 py-0.5 bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400 text-[10px] font-bold uppercase tracking-wider rounded inline-flex items-center gap-1">
                                        <svg class="h-2.5 w-2.5" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" /></svg>
                                        Highlight
                                    </span>
                                @endif
                            </div>
                            <h1 class="text-3xl font-bold text-slate-900 dark:text-white leading-tight uppercase tracking-tight">{{ $dokumentasi->judul }}</h1>
                            <p class="text-sm text-slate-500 dark:text-slate-500 mt-1">
                                Diabadikan pada {{ $dokumentasi->created_at->format('d F Y, H:i') }} WIB
                            </p>
                        </div>

                        @if($dokumentasi->kegiatan)
                            <div class="p-4 bg-blue-50/50 dark:bg-blue-500/5 rounded-xl border border-blue-100 dark:border-blue-500/10">
                                <h4 class="text-xs font-semibold text-blue-700 dark:text-blue-400 uppercase tracking-widest mb-2">Terkait Kegiatan</h4>
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-lg bg-blue-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/20">
                                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                        </svg>
                                    </div>
                                    <div class="flex-1">
                                        <h5 class="text-sm font-bold text-slate-900 dark:text-white line-clamp-1">{{ $dokumentasi->kegiatan->judul }}</h5>
                                        <a href="{{ route('kegiatan.show', $dokumentasi->kegiatan->id) }}" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">Buka Workspace Kegiatan &rarr;</a>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="space-y-3 pt-6">
                            <a href="{{ asset('storage/'.$dokumentasi->file) }}" target="_blank" class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-slate-900 dark:bg-blue-600 text-white rounded-xl hover:bg-slate-800 dark:hover:bg-blue-700 transition-all font-bold text-sm shadow-xl shadow-slate-900/10 dark:shadow-blue-500/20">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                Buka Foto Resolusi Penuh
                            </a>
                            <a href="{{ asset('storage/'.$dokumentasi->file) }}" download class="w-full inline-flex items-center justify-center gap-2 px-6 py-3 bg-white dark:bg-slate-700 text-slate-900 dark:text-white border border-slate-200 dark:border-slate-600 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-600 transition-all font-bold text-sm">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Unduh Foto Dokumentasi
                            </a>

                            {{-- Admin Actions --}}
                            <div class="flex items-center gap-3 pt-3 border-t border-slate-100 dark:border-slate-700 mt-2">
                                <a href="{{ route('dokumentasi.edit', $dokumentasi->id) }}" class="flex-1 inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 text-slate-700 dark:text-slate-300 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors text-sm font-medium shadow-sm">
                                    <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                    </svg>
                                    Edit
                                </a>
                                <form action="{{ route('dokumentasi.destroy', $dokumentasi->id) }}" method="POST" class="flex-1">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" onclick="window.showModalConfirm(this.closest('form'), 'Hapus Dokumentasi', 'Apakah Anda yakin ingin menghapus foto dokumentasi ini?')" class="w-full inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 rounded-xl hover:bg-red-100 dark:hover:bg-red-500/20 transition-colors text-sm font-medium shadow-sm border border-red-200 dark:border-red-500/30">
                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</x-admin-layout>

