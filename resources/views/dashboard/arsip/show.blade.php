@section('title', 'Folder Arsip - ' . $arsip->judul)

<x-admin-layout title="Dashboard">
    <div class="mb-6">
        <a href="{{ route('arsip.index') }}" class="back-link">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali ke Arsip
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-4 gap-8">
        {{-- Sidebar: Folder Info --}}
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-sm overflow-hidden">
                <div class="p-6">
                    <div class="w-16 h-16 rounded-2xl bg-blue-600 flex items-center justify-center text-white mb-6 shadow-lg shadow-blue-500/30">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z" />
                        </svg>
                    </div>
                    
                    <h1 class="text-xl font-bold text-slate-900 dark:text-white leading-tight mb-2">{{ $arsip->judul }}</h1>
                    <div class="flex items-center gap-2 mb-4">
                        @if($arsip->kegiatan_id)
                            <span class="px-2 py-0.5 bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400 text-[10px] font-bold uppercase tracking-wider rounded">Kegiatan</span>
                        @else
                            <span class="px-2 py-0.5 bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400 text-[10px] font-bold uppercase tracking-wider rounded">Umum</span>
                        @endif
                        <span class="text-[10px] text-slate-500 font-medium uppercase tracking-wider">{{ $arsip->created_at->format('Y') }}</span>
                    </div>

                    <p class="text-xs text-slate-600 dark:text-slate-400 leading-relaxed mb-6 italic">
                        "{{ $arsip->deskripsi ?: 'Tidak ada deskripsi tambahan.' }}"
                    </p>

                    <div class="space-y-3 pt-6 border-t border-slate-100 dark:border-slate-700/50">
                        <div class="flex items-center justify-between text-[10px] uppercase font-bold tracking-widest text-slate-400">
                            <span>Statistik Folder</span>
                        </div>
                        <div class="flex items-center justify-between p-3 bg-slate-50 dark:bg-slate-900/50 rounded-xl border border-slate-100 dark:border-slate-800">
                            <span class="text-xs font-medium text-slate-500">Total File</span>
                            <span class="text-xs font-bold text-slate-900 dark:text-white">{{ $arsip->attachments->count() }}</span>
                        </div>
                    </div>
                </div>

                @if($arsip->kegiatan)
                <div class="bg-slate-50/50 dark:bg-slate-900/30 p-6 border-t border-slate-100 dark:border-slate-700/50">
                    <h4 class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Kegiatan Terkait</h4>
                    <a href="{{ route('kegiatan.show', $arsip->kegiatan->id) }}" class="group block">
                        <h5 class="text-xs font-bold text-slate-700 dark:text-slate-300 group-hover:text-blue-600 transition-colors mb-1 line-clamp-1">{{ $arsip->kegiatan->judul }}</h5>
                        <p class="text-[10px] text-slate-500 uppercase tracking-tighter italic font-medium">Buka Workspace &rarr;</p>
                    </a>
                </div>
                @endif
            </div>

            {{-- Upload Form --}}
            <div class="bg-gradient-to-br from-blue-600 to-indigo-700 rounded-2xl p-6 text-white shadow-xl shadow-blue-500/20">
                <h4 class="text-sm font-bold mb-1">Tambah Dokumen</h4>
                <p class="text-[10px] text-blue-100 mb-4 font-medium">Unggah file satu per satu secara bertahap</p>
                
                <form action="{{ route('arsip.attachments.store', $arsip->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="space-y-3">
                        <div class="relative group">
                            <input type="file" name="file" id="file_upload" class="hidden" required onchange="this.form.submit()">
                            <label for="file_upload" class="flex flex-col items-center justify-center p-4 border-2 border-dashed border-white/30 rounded-xl hover:border-white hover:bg-white/10 transition-all cursor-pointer">
                                <svg class="h-6 w-6 mb-2 text-blue-100" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                </svg>
                                <span class="text-[10px] font-bold uppercase tracking-widest">Pilih File</span>
                            </label>
                        </div>
                        <p class="text-[9px] text-blue-100/70 leading-tight text-center">Max size: 20MB. PDF, DOCX, JPG, PNG, dll.</p>
                    </div>
                </form>
            </div>
        </div>

        {{-- Main Content: File List --}}
        <div class="lg:col-span-3">
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-sm overflow-hidden flex flex-col h-full max-h-[800px]">
                <div class="p-6 border-b border-slate-100 dark:border-slate-700 flex items-center justify-between bg-slate-50/50 dark:bg-slate-900/20 shrink-0">
                    <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Daftar Dokumen</h3>
                    <div class="flex items-center gap-2">
                        <span class="flex h-2 w-2 rounded-full bg-emerald-500 pulse"></span>
                        <span class="text-[10px] font-bold text-slate-500 uppercase tracking-widest">Digital Vault</span>
                    </div>
                </div>

                @if($arsip->attachments->count() > 0)
                    <div class="overflow-x-auto overflow-y-auto custom-scrollbar flex-1">
                        <table class="w-full text-left">
                            <thead class="bg-slate-50/50 dark:bg-slate-900/30 text-[10px] uppercase font-bold text-slate-500 tracking-widest sticky top-0 z-10 backdrop-blur-sm">
                                <tr>
                                    <th class="px-6 py-4">Nama Dokumen</th>
                                    <th class="px-6 py-4">Tipe</th>
                                    <th class="px-6 py-4">Ukuran</th>
                                    <th class="px-6 py-4">Tanggal Unggah</th>
                                    <th class="px-6 py-4 text-right">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                                @foreach($arsip->attachments as $attachment)
                                    @php
                                        $ext = strtolower($attachment->file_type);
                                        $iconClass = 'text-slate-400';
                                        if(in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) $iconClass = 'text-emerald-500';
                                        elseif($ext === 'pdf') $iconClass = 'text-red-500';
                                        elseif(in_array($ext, ['doc', 'docx'])) $iconClass = 'text-blue-500';
                                        elseif(in_array($ext, ['xls', 'xlsx', 'csv'])) $iconClass = 'text-emerald-600';
                                        elseif(in_array($ext, ['zip', 'rar'])) $iconClass = 'text-amber-600';

                                        // Size formatting
                                        $size = $attachment->file_size;
                                        if ($size >= 1048576) $formattedSize = round($size / 1048576, 2) . ' MB';
                                        elseif ($size >= 1024) $formattedSize = round($size / 1024, 2) . ' KB';
                                        else $formattedSize = $size . ' bytes';
                                    @endphp
                                    <tr class="hover:bg-slate-50/50 dark:hover:bg-slate-900/40 transition-colors group">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-900 flex items-center justify-center border border-slate-200 dark:border-slate-800 group-hover:scale-110 transition-transform shadow-sm">
                                                    @if(in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                                        <svg class="h-5 w-5 {{ $iconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                        </svg>
                                                    @elseif($ext === 'pdf')
                                                        <svg class="h-5 w-5 {{ $iconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                        </svg>
                                                    @else
                                                        <svg class="h-5 w-5 {{ $iconClass }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                                        </svg>
                                                    @endif
                                                </div>
                                                <div>
                                                    <h5 class="text-xs font-bold text-slate-800 dark:text-slate-200 line-clamp-1 group-hover:text-blue-600 transition-colors">{{ $attachment->file_name }}</h5>
                                                    <p class="text-[9px] text-slate-400 font-medium uppercase tracking-tighter">{{ $ext ?: 'Binary' }} File</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 uppercase text-[10px] font-bold text-slate-500 dark:text-slate-400">
                                            {{ $ext }}
                                        </td>
                                        <td class="px-6 py-4 text-xs font-medium text-slate-600 dark:text-slate-400">
                                            {{ $formattedSize }}
                                        </td>
                                        <td class="px-6 py-4 text-xs text-slate-500 dark:text-slate-500">
                                            {{ $attachment->created_at->format('d/m/Y H:i') }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex items-center justify-end gap-2 opacity-60 group-hover:opacity-100 transition-opacity">
                                                <a href="{{ asset('storage/' . $attachment->file_path) }}" target="_blank" class="p-2 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-blue-600 hover:text-white transition-all shadow-sm" title="Lihat">
                                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                </a>
                                                <a href="{{ asset('storage/' . $attachment->file_path) }}" download="{{ $attachment->file_name }}" class="p-2 rounded-lg bg-slate-100 dark:bg-slate-700 hover:bg-blue-600 hover:text-white transition-all shadow-sm" title="Unduh">
                                                    <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a2 2 0 002 2h12a2 2 0 002-2v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                    </svg>
                                                </a>
                                                <form action="{{ route('arsip.attachments.destroy', [$arsip->id, $attachment->id]) }}" method="POST" class="inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="button" onclick="window.showModalConfirm(this.closest('form'), 'Hapus File', 'Hapus file ini dari arsip?')" class="p-2 rounded-lg bg-red-100 dark:bg-red-500/20 text-red-600 dark:text-red-400 hover:bg-red-600 hover:text-white transition-all shadow-sm" title="Hapus">
                                                        <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="flex flex-col items-center justify-center py-32 px-6 text-center">
                        <div class="w-20 h-20 rounded-full bg-slate-50 dark:bg-slate-900/50 flex items-center justify-center mb-6 border border-slate-100 dark:border-slate-800">
                            <svg class="h-10 w-10 text-slate-300 dark:text-slate-700" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 13h6m-3-3v6m-9 1V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
                            </svg>
                        </div>
                        <h4 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider mb-2">Folder Ini Masih Kosong</h4>
                        <p class="text-[10px] text-slate-500 max-w-[250px] leading-relaxed">Belum ada dokumen yang diunggah ke folder ini. Gunakan panel di sebelah kiri untuk menambah dokumen baru.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>


<style>
    @keyframes pulse {
        0%, 100% { transform: scale(1); opacity: 1; }
        50% { transform: scale(1.5); opacity: 0.5; }
    }
    .pulse {
        animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
    }
</style>
</x-admin-layout>

