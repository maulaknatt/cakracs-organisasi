<x-admin-layout :title="$kegiatan->judul">
@php
    $tugasKegiatan = $kegiatan->tugas()->latest()->get();
    $keuanganKegiatan = \App\Models\Keuangan::where('kegiatan_id', $kegiatan->id)->latest('tanggal')->get();
    $dokumentasiKegiatan = $kegiatan->dokumentasi()->latest()->get();
    $jabatanOrder = ['Ketua' => 1, 'Wakil' => 2, 'Sekretaris' => 3, 'Bendahara' => 4, 'Anggota' => 5];
    $anggotaKegiatan = $kegiatan->anggotaList->sortBy(fn($a) => $jabatanOrder[$a->jabatan ?? 'Anggota'] ?? 9)->values();
    
    $totalPemasukan = $keuanganKegiatan->where('jenis', 'masuk')->sum('jumlah');
    $totalPengeluaran = $keuanganKegiatan->where('jenis', 'keluar')->sum('jumlah');
    $saldo = $totalPemasukan - $totalPengeluaran;
    $canManage = $canManage ?? (auth()->user() && (auth()->user()->isSuperAdmin() || auth()->user()->isPengurus()));
@endphp

{{-- Back Button --}}
<div class="mb-6">
    <a href="{{ route('kegiatan.index') }}" class="inline-flex items-center gap-2 text-[14px] font-semibold text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
        Kembali ke Program Kerja
    </a>
</div>

{{-- Header Card Premium --}}
<div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700/60 rounded-premium overflow-hidden shadow-sm">
    {{-- Gradient top bar --}}
    <div class="h-1.5"
         style="background:linear-gradient(90deg, #3b82f6, #6366f1, #8b5cf6);"></div>
    <div class="p-6 sm:p-8">
        <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-5">
            <div class="flex-1">
                <div class="flex flex-wrap items-center gap-2 mb-3">
                    @if($kegiatan->status === 'selesai')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[12.5px] font-bold bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Selesai
                        </span>
                    @elseif($kegiatan->status === 'aktif')
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[12.5px] font-bold bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400 border border-blue-200 dark:border-blue-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span> Aktif
                        </span>
                    @else
                        <span class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-full text-[12.5px] font-bold bg-amber-50 dark:bg-amber-500/10 text-amber-700 dark:text-amber-400 border border-amber-100 dark:border-amber-500/20">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span> Akan Datang
                        </span>
                    @endif
                </div>
                <h1 class="text-[22px] sm:text-[26px] font-black text-slate-900 dark:text-white leading-tight tracking-tight mb-3">
                    {{ $kegiatan->judul }}
                </h1>
                <div class="flex items-center gap-2 text-[14px] text-slate-500 dark:text-slate-400 mb-5">
                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <span class="font-medium">{{ \Carbon\Carbon::parse($kegiatan->tanggal_mulai)->translatedFormat('d F Y') }} — {{ \Carbon\Carbon::parse($kegiatan->tanggal_selesai)->translatedFormat('d F Y') }}</span>
                </div>
                @if($kegiatan->deskripsi)
                    <p class="text-[14.5px] text-slate-600 dark:text-slate-400 leading-relaxed mb-5">
                        {{ $kegiatan->deskripsi }}
                    </p>
                @endif
                {{-- Progress --}}
                <div>
                    <div class="flex items-center justify-between text-[13px] mb-2">
                        <span class="font-medium text-slate-500 dark:text-slate-400">Progress Kegiatan</span>
                        <span class="font-bold text-blue-500">{{ (int)($kegiatan->progress ?? 0) }}%</span>
                    </div>
                    <div class="w-full rounded-full h-2.5 bg-slate-100 dark:bg-slate-800">
                        <div class="h-2.5 rounded-full transition-all duration-700" style="width:{{ (int)($kegiatan->progress ?? 0) }}%; background:linear-gradient(90deg,#3b82f6,#6366f1);"></div>
                    </div>
                </div>
            </div>
            @if($canManage)
            <a href="{{ route('kegiatan.edit', $kegiatan->id) }}" data-turbo-frame="modal" class="btn-secondary gap-2 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                </svg>
                Edit Program
            </a>
            @endif
        </div>
    </div>
</div>

{{-- Spacer between header and workspace --}}
<div class="h-5 sm:h-8"></div>

{{-- Workspace Tabs --}}
<div class="border border-slate-200 dark:border-slate-700/60 rounded-2xl shadow-sm overflow-hidden">
    {{-- Tab Navigation - scrollable --}}
    <div class="border-b border-slate-200 dark:border-slate-700/50 bg-slate-50/70 dark:bg-slate-800/50 overflow-x-auto scrollbar-hide" style="-webkit-overflow-scrolling: touch;">
        <div class="flex items-center min-w-max border-b-2 border-transparent">
            @foreach([['overview','Overview'],['tugas','Tugas'],['anggota','Panitia'],['keuangan','Keuangan'],['dokumentasi','Dokumentasi'],['arsip','Arsip'],['evaluasi','Evaluasi']] as [$id, $label])
            <button onclick="showTab('{{ $id }}')" data-tab="{{ $id }}"
                    class="tab-button outline-none block flex-shrink-0 whitespace-nowrap px-4 sm:px-5 md:px-6 py-3.5 sm:py-4 text-[13px] sm:text-[14px] font-semibold border-b-2 transition-all -mb-[2px]
                           {{ $id === 'overview' ? 'border-blue-600 dark:border-blue-500 text-blue-600 dark:text-blue-400 bg-white dark:bg-slate-900 border-x border-t border-slate-200 dark:border-slate-700/50 rounded-t-xl first:border-l-0' : 'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-white dark:hover:bg-slate-900/50' }}">
                {{ $label }}
            </button>
            @endforeach
        </div>
    </div>
    
    {{-- Tab Content --}}
    <div class="p-5 sm:p-7 md:p-8 lg:p-10 bg-white dark:bg-slate-900">
        {{-- Overview Tab --}}
        <div id="tab-overview" class="tab-content flex flex-col gap-6 sm:gap-8 md:gap-10">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 sm:gap-5 md:gap-6">
                <div class="p-4 sm:p-5 md:p-6 rounded-xl sm:rounded-2xl bg-blue-50 dark:bg-blue-500/10 border border-blue-100 dark:border-blue-500/20">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-blue-500/15 dark:bg-blue-500/20 flex items-center justify-center mb-2.5 sm:mb-4">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-blue-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                    </div>
                    <p class="text-[22px] sm:text-[26px] md:text-[30px] font-black text-blue-600 dark:text-blue-400 leading-none">{{ $tugasKegiatan->count() }}</p>
                    <p class="text-[10px] sm:text-[11px] font-bold text-blue-500/70 mt-1.5 sm:mt-2 uppercase tracking-[0.08em] sm:tracking-[0.1em]">Total Tugas</p>
                </div>
                <div class="p-4 sm:p-5 md:p-6 rounded-xl sm:rounded-2xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-100 dark:border-emerald-500/20">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-emerald-500/15 dark:bg-emerald-500/20 flex items-center justify-center mb-2.5 sm:mb-4">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                    </div>
                    <p class="text-[22px] sm:text-[26px] md:text-[30px] font-black text-emerald-600 dark:text-emerald-400 leading-none">{{ $tugasKegiatan->where('status','done')->count() }}</p>
                    <p class="text-[10px] sm:text-[11px] font-bold text-emerald-500/70 mt-1.5 sm:mt-2 uppercase tracking-[0.08em] sm:tracking-[0.1em]">Selesai</p>
                </div>
                <div class="p-4 sm:p-5 md:p-6 rounded-xl sm:rounded-2xl bg-slate-50 dark:bg-slate-800 border border-slate-200 dark:border-slate-700/50">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-slate-200/80 dark:bg-slate-700 flex items-center justify-center mb-2.5 sm:mb-4">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-slate-500 dark:text-slate-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </div>
                    <p class="text-[22px] sm:text-[26px] md:text-[30px] font-black text-slate-700 dark:text-slate-200 leading-none">{{ $anggotaKegiatan->count() }}</p>
                    <p class="text-[10px] sm:text-[11px] font-bold text-slate-400 mt-1.5 sm:mt-2 uppercase tracking-[0.08em] sm:tracking-[0.1em]">Panitia</p>
                </div>
                <div class="p-4 sm:p-5 md:p-6 rounded-xl sm:rounded-2xl bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-100 dark:border-indigo-500/20">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-indigo-500/15 dark:bg-indigo-500/20 flex items-center justify-center mb-2.5 sm:mb-4">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-indigo-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    </div>
                    <p class="text-[22px] sm:text-[26px] md:text-[30px] font-black text-indigo-600 dark:text-indigo-400 leading-none">{{ (int)($kegiatan->progress ?? 0) }}%</p>
                    <p class="text-[10px] sm:text-[11px] font-bold text-indigo-500/70 mt-1.5 sm:mt-2 uppercase tracking-[0.08em] sm:tracking-[0.1em]">Progress</p>
                </div>
            </div>
            @if($kegiatan->deskripsi)
                <div class="p-4 sm:p-5 md:p-7 rounded-xl sm:rounded-2xl bg-slate-50 dark:bg-slate-800/50 border border-slate-100 dark:border-slate-700/50">
                    <p class="text-[11px] font-black uppercase tracking-[0.12em] text-slate-400 mb-3">Deskripsi Program</p>
                    <p class="text-[14.5px] text-slate-600 dark:text-slate-300 leading-relaxed">{{ $kegiatan->deskripsi }}</p>
                </div>
            @endif
        </div>
        
        {{-- Tugas Kegiatan Tab --}}
        <div id="tab-tugas" class="tab-content hidden flex flex-col gap-6 sm:gap-8 md:gap-10">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4 pb-5 sm:pb-6 border-b border-slate-100 dark:border-slate-700/40">
                <div>
                    <h2 class="text-base sm:text-lg font-black text-slate-900 dark:text-white tracking-tight">Tugas Kegiatan</h2>
                    <p class="text-[12px] sm:text-[13px] text-slate-500 dark:text-slate-400 mt-0.5">Kelola semua tugas yang terkait dengan program ini</p>
                </div>
                @if($canManage)
                <button type="button" onclick="document.getElementById('modal-tambah-tugas').classList.remove('hidden')" class="btn-primary gap-2 shrink-0 w-full sm:w-auto justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
                    </svg>
                    Tambah Tugas
                </button>
                @endif
            </div>
            @if($tugasKegiatan->count())
                <div class="rounded-xl sm:rounded-2xl border border-slate-200 dark:border-slate-700/60 overflow-hidden overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 dark:bg-slate-800/60">
                            <tr class="border-b border-slate-100 dark:border-slate-700/50">
                                <th class="px-5 py-3.5 text-left text-[12px] font-black uppercase tracking-widest text-slate-400">Judul Tugas</th>
                                <th class="px-5 py-3.5 text-left text-[12px] font-black uppercase tracking-widest text-slate-400">Deadline</th>
                                <th class="px-5 py-3.5 text-left text-[12px] font-black uppercase tracking-widest text-slate-400">Status</th>
                                @if($canManage)<th class="px-5 py-3.5 text-right text-[12px] font-black uppercase tracking-widest text-slate-400">Aksi</th>@endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50 bg-white dark:bg-slate-900">
                            @foreach($tugasKegiatan as $tugas)
                                <tr class="hover:bg-slate-50/70 dark:hover:bg-white/3 transition-colors">
                                    <td class="px-5 py-4">
                                        <p class="text-[14px] font-bold text-slate-900 dark:text-white">{{ $tugas->judul }}</p>
                                        @if($tugas->deskripsi)
                                            <p class="text-[12.5px] text-slate-500 dark:text-slate-400 mt-0.5 line-clamp-1">{{ Str::limit($tugas->deskripsi, 60) }}</p>
                                        @endif
                                    </td>
                                    <td class="px-5 py-4 text-[13.5px] text-slate-600 dark:text-slate-400">{{ $tugas->deadline ? \Carbon\Carbon::parse($tugas->deadline)->translatedFormat('d M Y') : '—' }}</td>
                                    <td class="px-5 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[12px] font-bold
                                            {{ $tugas->status == 'done' ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-500/20' : 'bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-slate-700' }}">
                                            {{ $tugas->status == 'done' ? '✓ Selesai' : 'Pending' }}
                                        </span>
                                    </td>
                                    @if($canManage)
                                    <td class="px-5 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ route('tugas.edit', $tugas->id) }}" data-turbo-frame="modal" class="btn-icon border border-slate-200 dark:border-slate-700 hover:text-blue-500" title="Edit">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </a>
                                            <form action="{{ route('tugas.destroy', $tugas->id) }}" method="POST" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="button" onclick="window.showModalConfirm(this.closest('form'))" class="btn-icon border border-slate-200 dark:border-slate-700 hover:text-red-500" title="Hapus">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="py-10 sm:py-14 rounded-xl sm:rounded-2xl border-2 border-dashed border-slate-200 dark:border-slate-700">
                    <div class="flex flex-col items-center justify-center text-center px-4">
                        <svg class="w-8 h-8 sm:w-10 sm:h-10 text-slate-300 dark:text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2m-6 9l2 2 4-4"/></svg>
                        <p class="text-[13px] sm:text-[14px] text-slate-400 font-medium">Belum ada tugas untuk kegiatan ini</p>
                    </div>
                </div>
            @endif
        </div>
        
        {{-- Anggota Tab --}}
        <div id="tab-anggota" class="tab-content hidden flex flex-col gap-6 sm:gap-8 md:gap-10">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4 pb-5 sm:pb-6 border-b border-slate-100 dark:border-slate-700/40">
                <div>
                    <h2 class="text-base sm:text-lg font-black text-slate-900 dark:text-white tracking-tight">Panitia Kegiatan</h2>
                    <p class="text-[12px] sm:text-[13px] text-slate-500 dark:text-slate-400 mt-0.5">Daftar anggota yang terlibat dalam program ini</p>
                </div>
                @if($canManage)
                <button type="button" onclick="document.getElementById('modal-tambah-anggota').classList.remove('hidden')" class="btn-primary gap-2 shrink-0 w-full sm:w-auto justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Tambah Panitia
                </button>
                @endif
            </div>
            @if($anggotaKegiatan->count())
                <div class="rounded-xl sm:rounded-2xl border border-slate-200 dark:border-slate-700/60 overflow-hidden overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 dark:bg-slate-800/60">
                            <tr class="border-b border-slate-100 dark:border-slate-700/50">
                                <th class="px-5 py-3.5 text-left text-[12px] font-black uppercase tracking-widest text-slate-400">Nama</th>
                                <th class="px-5 py-3.5 text-left text-[12px] font-black uppercase tracking-widest text-slate-400">Jabatan</th>
                                @if($canManage)<th class="px-5 py-3.5 text-right text-[12px] font-black uppercase tracking-widest text-slate-400">Aksi</th>@endif
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50 bg-white dark:bg-slate-900">
                            @foreach($anggotaKegiatan as $a)
                                <tr class="hover:bg-slate-50/70 dark:hover:bg-white/3 transition-colors">
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-[11px] font-bold" style="background:linear-gradient(135deg,#1e40af,#3b82f6);">{{ strtoupper(substr($a->nama, 0, 1)) }}</div>
                                            <span class="text-[14px] font-bold text-slate-900 dark:text-white">{{ $a->nama }}</span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4">
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[12px] font-bold bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400 border border-blue-100 dark:border-blue-500/20">{{ $a->jabatan }}</span>
                                    </td>
                                    @if($canManage)
                                    <td class="px-5 py-4 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <button type="button" onclick="openEditAnggota(this)" data-id="{{ $a->id }}" data-nama="{{ e($a->nama) }}" data-jabatan="{{ e($a->jabatan) }}" class="btn-icon border border-slate-200 dark:border-slate-700 hover:text-blue-500" title="Edit">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </button>
                                            <form action="{{ route('kegiatan.anggota.destroy', [$kegiatan, $a]) }}" method="POST" class="inline">
                                                @csrf @method('DELETE')
                                                <button type="button" onclick="window.showModalConfirm(this.closest('form'), 'Hapus Anggota', 'Anda yakin ingin menghapus anggota ini dari kegiatan?')" class="btn-icon border border-slate-200 dark:border-slate-700 hover:text-red-500" title="Hapus">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                    @endif
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="py-10 sm:py-14 rounded-xl sm:rounded-2xl border-2 border-dashed border-slate-200 dark:border-slate-700">
                    <div class="flex flex-col items-center justify-center text-center px-4">
                        <svg class="w-8 h-8 sm:w-10 sm:h-10 text-slate-300 dark:text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        <p class="text-[13px] sm:text-[14px] text-slate-400 font-medium">Belum ada panitia untuk kegiatan ini</p>
                    </div>
                </div>
            @endif
        </div>
        
        {{-- Keuangan Tab --}}
        <div id="tab-keuangan" class="tab-content hidden flex flex-col gap-6 sm:gap-8 md:gap-10">
            <div class="pb-5 sm:pb-6 border-b border-slate-100 dark:border-slate-700/40">
                <h2 class="text-base sm:text-lg font-black text-slate-900 dark:text-white tracking-tight">Keuangan Kegiatan</h2>
                <p class="text-[12px] sm:text-[13px] text-slate-500 dark:text-slate-400 mt-0.5">Ringkasan arus keuangan program ini</p>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 sm:gap-5 md:gap-6">
                <div class="p-4 sm:p-5 md:p-6 bg-emerald-50 dark:bg-emerald-500/10 rounded-xl sm:rounded-2xl border border-emerald-100 dark:border-emerald-500/20">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-emerald-500/15 dark:bg-emerald-500/20 flex items-center justify-center mb-2.5 sm:mb-4">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    <p class="text-[10px] sm:text-[11px] font-black uppercase tracking-[0.1em] text-emerald-400 mb-1.5 sm:mb-2">Pemasukan</p>
                    <p class="text-[18px] sm:text-[20px] md:text-[24px] font-black text-emerald-600 dark:text-emerald-400 leading-none">Rp {{ number_format($totalPemasukan, 0, ',', '.') }}</p>
                </div>
                <div class="p-4 sm:p-5 md:p-6 bg-red-50 dark:bg-red-500/10 rounded-xl sm:rounded-2xl border border-red-100 dark:border-red-500/20">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl bg-red-500/15 dark:bg-red-500/20 flex items-center justify-center mb-2.5 sm:mb-4">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 text-red-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M20 12H4"/></svg>
                    </div>
                    <p class="text-[10px] sm:text-[11px] font-black uppercase tracking-[0.1em] text-red-400 mb-1.5 sm:mb-2">Pengeluaran</p>
                    <p class="text-[18px] sm:text-[20px] md:text-[24px] font-black text-red-600 dark:text-red-400 leading-none">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</p>
                </div>
                <div class="p-4 sm:p-5 md:p-6 {{ $saldo >= 0 ? 'bg-blue-50 dark:bg-blue-500/10 border-blue-100 dark:border-blue-500/20' : 'bg-red-50 dark:bg-red-500/10 border-red-100 dark:border-red-500/20' }} rounded-xl sm:rounded-2xl border">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg sm:rounded-xl {{ $saldo >= 0 ? 'bg-blue-500/15 dark:bg-blue-500/20' : 'bg-red-500/15 dark:bg-red-500/20' }} flex items-center justify-center mb-2.5 sm:mb-4">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5 {{ $saldo >= 0 ? 'text-blue-500' : 'text-red-500' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <p class="text-[10px] sm:text-[11px] font-black uppercase tracking-[0.1em] {{ $saldo >= 0 ? 'text-blue-400' : 'text-red-400' }} mb-1.5 sm:mb-2">Saldo Bersih</p>
                    <p class="text-[18px] sm:text-[20px] md:text-[24px] font-black {{ $saldo >= 0 ? 'text-blue-600 dark:text-blue-400' : 'text-red-600 dark:text-red-400' }} leading-none">Rp {{ number_format(abs($saldo), 0, ',', '.') }}</p>
                </div>
            </div>
            @if($keuanganKegiatan->count())
                <div class="rounded-xl sm:rounded-2xl border border-slate-200 dark:border-slate-700/60 overflow-hidden overflow-x-auto">
                    <div class="overflow-y-auto max-h-[450px] custom-scrollbar">
                        <table class="w-full">
                            <thead class="bg-slate-50 dark:bg-slate-800/60 sticky top-0 z-10">
                                <tr class="border-b border-slate-100 dark:border-slate-700/50">
                                    <th class="px-5 py-3.5 text-left text-[12px] font-black uppercase tracking-widest text-slate-400">Tanggal</th>
                                    <th class="px-5 py-3.5 text-left text-[12px] font-black uppercase tracking-widest text-slate-400">Judul</th>
                                    <th class="px-5 py-3.5 text-left text-[12px] font-black uppercase tracking-widest text-slate-400">Jenis</th>
                                    <th class="px-5 py-3.5 text-right text-[12px] font-black uppercase tracking-widest text-slate-400">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50 bg-white dark:bg-slate-900">
                                @foreach($keuanganKegiatan as $item)
                                    <tr class="hover:bg-slate-50/70 dark:hover:bg-white/3 transition-colors">
                                        <td class="px-5 py-4 text-[13.5px] text-slate-600 dark:text-slate-400 whitespace-nowrap">{{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}</td>
                                        <td class="px-5 py-4 text-[14px] font-bold text-slate-900 dark:text-white">{{ $item->judul }}</td>
                                        <td class="px-5 py-4">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[12px] font-bold {{ $item->jenis == 'masuk' ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-500/20' : 'bg-red-50 dark:bg-red-500/10 text-red-700 dark:text-red-400 border border-red-100 dark:border-red-500/20' }}">
                                                {{ ucfirst($item->jenis) }}
                                            </span>
                                        </td>
                                        <td class="px-5 py-4 text-[14px] text-right font-bold {{ $item->jenis == 'masuk' ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }} whitespace-nowrap">
                                            {{ $item->jenis == 'masuk' ? '+' : '-' }}Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="py-10 sm:py-14 rounded-xl sm:rounded-2xl border-2 border-dashed border-slate-200 dark:border-slate-700">
                    <div class="flex flex-col items-center justify-center text-center px-4">
                        <svg class="w-8 h-8 sm:w-10 sm:h-10 text-slate-300 dark:text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        <p class="text-[13px] sm:text-[14px] text-slate-400 font-medium">Belum ada transaksi keuangan</p>
                    </div>
                </div>
            @endif
        </div>
        
        {{-- Dokumentasi Tab --}}
        <div id="tab-dokumentasi" class="tab-content hidden flex flex-col gap-6 sm:gap-8 md:gap-10">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4 pb-5 sm:pb-6 border-b border-slate-100 dark:border-slate-700/40">
                <div>
                    <h2 class="text-base sm:text-lg font-black text-slate-900 dark:text-white tracking-tight">Dokumentasi Kegiatan</h2>
                    <p class="text-[12px] sm:text-[13px] text-slate-500 dark:text-slate-400 mt-0.5">Galeri foto dan momen dari program ini</p>
                </div>
                @if($canManage)
                <button type="button" onclick="document.getElementById('modal-tambah-dokumentasi').classList.remove('hidden')" class="btn-primary gap-2 shrink-0 w-full sm:w-auto justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Upload Foto
                </button>
                @endif
            </div>
            @if($dokumentasiKegiatan->count())
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-2.5 sm:gap-3 md:gap-4">
                    @foreach($dokumentasiKegiatan as $doc)
                        <div class="aspect-square rounded-xl sm:rounded-2xl overflow-hidden bg-slate-100 dark:bg-slate-800 group cursor-pointer relative border border-slate-200 dark:border-slate-700/60 shadow-sm hover:shadow-lg hover:scale-105 transition-all duration-200">
                            <img src="{{ asset('storage/'.$doc->file) }}" alt="{{ $doc->judul }}" class="w-full h-full object-cover">
                            <div class="absolute inset-0 bg-black/30 opacity-0 group-hover:opacity-100 transition-opacity flex items-end p-2">
                                <p class="text-white text-[11px] font-bold truncate">{{ $doc->judul }}</p>
                            </div>
                            @if($doc->highlight)
                                <div class="absolute top-2 right-2 bg-amber-500 rounded-full w-5 h-5 flex items-center justify-center shadow">
                                    <svg class="h-3 w-3 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            @else
                <div class="py-10 sm:py-14 rounded-xl sm:rounded-2xl border-2 border-dashed border-slate-200 dark:border-slate-700">
                    <div class="flex flex-col items-center justify-center text-center px-4">
                        <svg class="w-8 h-8 sm:w-10 sm:h-10 text-slate-300 dark:text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        <p class="text-[13px] sm:text-[14px] text-slate-400 font-medium">Belum ada dokumentasi</p>
                    </div>
                </div>
            @endif
        </div>
        
        {{-- Arsip Tab --}}
        <div id="tab-arsip" class="tab-content hidden flex flex-col gap-6 sm:gap-8 md:gap-10">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-3 sm:gap-4 pb-5 sm:pb-6 border-b border-slate-100 dark:border-slate-700/40">
                <div>
                    <h2 class="text-base sm:text-lg font-black text-slate-900 dark:text-white tracking-tight">Arsip Kegiatan</h2>
                    <p class="text-[12px] sm:text-[13px] text-slate-500 dark:text-slate-400 mt-0.5">Dokumen dan berkas penting terkait program ini</p>
                </div>
                @if($canManage)
                <button type="button" onclick="document.getElementById('modal-tambah-arsip').classList.remove('hidden')" class="btn-primary gap-2 shrink-0 w-full sm:w-auto justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    Upload Arsip
                </button>
                @endif
            </div>
            
            @if($kegiatan->arsips->count())
                <div class="rounded-xl sm:rounded-2xl border border-slate-200 dark:border-slate-700/60 overflow-hidden overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-slate-50 dark:bg-slate-800/60">
                            <tr class="border-b border-slate-100 dark:border-slate-700/50">
                                <th class="px-5 py-3.5 text-left text-[12px] font-black uppercase tracking-widest text-slate-400">Nama Dokumen</th>
                                <th class="px-5 py-3.5 text-left text-[12px] font-black uppercase tracking-widest text-slate-400">Deskripsi</th>
                                <th class="px-5 py-3.5 text-right text-[12px] font-black uppercase tracking-widest text-slate-400">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50 bg-white dark:bg-slate-900">
                            @foreach($kegiatan->arsips->sortByDesc('created_at') as $arsip)
                                @php
                                    $extension = strtolower(pathinfo($arsip->file, PATHINFO_EXTENSION));
                                    $iconColor = match($extension) {
                                        'pdf' => 'text-red-500',
                                        'doc', 'docx' => 'text-blue-500',
                                        'xls', 'xlsx', 'csv' => 'text-emerald-500',
                                        'ppt', 'pptx' => 'text-orange-500',
                                        'zip', 'rar', '7z' => 'text-amber-500',
                                        'jpg', 'jpeg', 'png', 'gif', 'svg', 'webp' => 'text-purple-500',
                                        default => 'text-slate-400'
                                    };
                                    
                                    $iconPath = match($extension) {
                                        'pdf' => 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z M9 13h6 M9 17h3', // PDF lines
                                        'doc', 'docx' => 'M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z M9 13h6 M9 17h6', // Word lines
                                        'xls', 'xlsx', 'csv' => 'M12 3v18 M3 12h18 M3 6h18 M3 18h18 M3 6a3 3 0 013-3h12a3 3 0 013 3v12a3 3 0 01-3 3H6a3 3 0 01-3-3V6z', // Unified Grid for Spreadsheet
                                        'ppt', 'pptx' => 'M7 3h10a2 2 0 012 2v10a2 2 0 01-2 2H7a2 2 0 01-2-2V5a2 2 0 012-2z M8 21h8 M12 17v4', // Presentation Screen
                                        'zip', 'rar', '7z' => 'M5 19V5a2 2 0 012-2h10a2 2 0 012 2v14a2 2 0 01-2 2H7a2 2 0 01-2-2z M12 3v10 M10 6h4 M10 9h4 M10 12h4', // Box with Zipper effect
                                        'jpg', 'jpeg', 'png', 'gif', 'svg', 'webp' => 'M15 8l-3 3l-1-1l-5 5 M8 6a2 2 0 100 4 2 2 0 000-4z M5 21h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v14a2 2 0 002 2z', // Photo
                                        default => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'
                                    };
                                @endphp
                                <tr class="hover:bg-slate-50/70 dark:hover:bg-white/3 transition-colors">
                                    <td class="px-5 py-4">
                                        <div class="flex items-center gap-3">
                                            <svg class="h-5 w-5 {{ $iconColor }} shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $iconPath }}" />
                                            </svg>
                                            <span class="text-[14px] font-bold text-slate-900 dark:text-white">{{ $arsip->judul }}</span>
                                        </div>
                                    </td>
                                    <td class="px-5 py-4 text-[13.5px] text-slate-500 dark:text-slate-400">{{ $arsip->deskripsi ?: '-' }}</td>
                                    <td class="px-4 py-2 text-right">
                                        <div class="flex items-center justify-end gap-2">
                                            <a href="{{ asset('storage/' . $arsip->file) }}" target="_blank" class="p-1 text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-700 rounded transition-colors" title="Download / Lihat">
                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                                </svg>
                                            </a>
                                            @if($canManage)
                                            <button type="button" onclick="openEditArsip(this)" data-id="{{ $arsip->id }}" data-judul="{{ e($arsip->judul) }}" data-deskripsi="{{ e($arsip->deskripsi) }}" class="p-1 text-blue-600 dark:text-blue-400 hover:bg-blue-50 dark:hover:bg-blue-400/10 rounded transition-colors" title="Edit">
                                                <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                                </svg>
                                            </button>
                                            <form action="{{ route('arsip.destroy', $arsip->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" onclick="window.showModalConfirm(this.closest('form'))" class="p-1 text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-400/10 rounded transition-colors" title="Hapus">
                                                    <svg class="h-3.5 w-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="py-10 sm:py-14 rounded-xl sm:rounded-2xl border-2 border-dashed border-slate-200 dark:border-slate-700">
                    <div class="flex flex-col items-center justify-center text-center px-4">
                        <svg class="w-8 h-8 sm:w-10 sm:h-10 text-slate-300 dark:text-slate-600 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <p class="text-[13px] sm:text-[14px] text-slate-400 font-medium">Belum ada arsip untuk kegiatan ini</p>
                    </div>
                </div>
            @endif
        </div>
        
        {{-- Evaluasi Tab --}}
        <div id="tab-evaluasi" class="tab-content hidden flex flex-col gap-6 sm:gap-8 md:gap-10">
            <div class="pb-5 sm:pb-6 border-b border-slate-100 dark:border-slate-700/40">
                <h2 class="text-base sm:text-lg font-black text-slate-900 dark:text-white tracking-tight">Evaluasi Kegiatan</h2>
                <p class="text-[12px] sm:text-[13px] text-slate-500 dark:text-slate-400 mt-0.5">Laporan akhir dan penilaian kegiatan</p>
            </div>
            <div class="py-12 sm:py-16 md:py-20 rounded-xl sm:rounded-2xl border-2 border-dashed border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-white/[0.02]">
                <div class="flex flex-col items-center justify-center text-center px-4 w-full">
                    <div class="w-12 h-12 sm:w-14 sm:h-14 md:w-16 md:h-16 rounded-xl sm:rounded-2xl bg-gradient-to-br from-purple-100 to-indigo-100 dark:from-purple-500/15 dark:to-indigo-500/15 flex items-center justify-center mb-4 sm:mb-5 shadow-sm">
                        <svg class="w-6 h-6 sm:w-7 sm:h-7 md:w-8 md:h-8 text-purple-500 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                    </div>
                    <p class="text-[15px] sm:text-[17px] font-black text-slate-700 dark:text-slate-300 mb-2 tracking-tight">Fitur Evaluasi Segera Hadir</p>
                    <p class="text-[13px] sm:text-[14px] text-slate-400 max-w-sm leading-relaxed">Laporan akhir, catatan keberhasilan, dan rekomendasi untuk meningkatkan program ke depannya</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('turbo:load', function() {
    const urlParams = new URLSearchParams(window.location.search);
    const tab = urlParams.get('tab') || 'overview';
    showTab(tab);
});

// Update showTab calls to pass 'event' or use the tabId logic correctly
window.showTab = function(tabId) {
    // Hide all tabs with a subtle fade
    document.querySelectorAll('.tab-content').forEach(el => {
        el.classList.add('hidden');
        el.classList.remove('animate-fade-in');
    });
    
    const activeTab = document.getElementById('tab-' + tabId);
    if (activeTab) {
        activeTab.classList.remove('hidden');
        activeTab.classList.add('animate-fade-in');
    }
    
    // Update tab buttons
    const activeClasses = 'tab-button outline-none block flex-shrink-0 whitespace-nowrap px-4 sm:px-5 md:px-6 py-3.5 sm:py-4 text-[13px] sm:text-[14px] font-semibold border-b-2 transition-all -mb-[2px] border-blue-600 dark:border-blue-500 text-blue-600 dark:text-blue-400 bg-white dark:bg-slate-900 border-x border-t border-slate-200 dark:border-slate-700/50 rounded-t-xl first:border-l-0';
    
    const inactiveClasses = 'tab-button outline-none block flex-shrink-0 whitespace-nowrap px-4 sm:px-5 md:px-6 py-3.5 sm:py-4 text-[13px] sm:text-[14px] font-semibold border-b-2 transition-all -mb-[2px] border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-800 dark:hover:text-slate-200 hover:bg-white dark:hover:bg-slate-900/50';
    
    document.querySelectorAll('.tab-button').forEach(btn => {
        const isActive = btn.getAttribute('data-tab') === tabId;
        btn.className = isActive ? activeClasses : inactiveClasses;
    });

    // Update URL without refreshing
    const url = new URL(window.location);
    url.searchParams.set('tab', tabId);
    window.history.replaceState({}, '', url);
}
</script>

{{-- Modal Tambah Tugas (internal workspace) --}}
@if($canManage)
{{-- Modal Tambah Anggota (manual nama + jabatan) --}}
<div id="modal-tambah-anggota" class="hidden fixed inset-0 z-[60] flex items-center justify-center p-4 sm:p-6 bg-slate-900/60 dark:bg-slate-950/70 backdrop-blur-xl animate-fade-in" aria-modal="true"
     x-data="{}" @keydown.escape.window="document.getElementById('modal-tambah-anggota').classList.add('hidden')">
    <div class="fixed inset-0" @click="document.getElementById('modal-tambah-anggota').classList.add('hidden')"></div>
    <div class="relative w-full max-w-md bg-white dark:bg-[#151c2c] border border-slate-200 dark:border-white/10 rounded-2xl shadow-2xl overflow-hidden animate-scale-in flex flex-col">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-white/5 flex items-center justify-between">
            <h3 class="text-lg font-black text-slate-800 dark:text-white tracking-tight">Tambah Panitia</h3>
            <button type="button" @click="document.getElementById('modal-tambah-anggota').classList.add('hidden')" class="text-slate-400 hover:text-rose-500 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="p-6">
            <form action="{{ route('kegiatan.anggota.store', $kegiatan) }}" method="POST" class="space-y-3">
                @csrf
                <div>
                    <label for="anggota_nama" class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Nama <span class="text-red-500">*</span></label>
                    <input type="text" id="anggota_nama" name="nama" required class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-sm text-slate-900 dark:text-white">
                    @error('nama')<p class="mt-1 text-xs text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="anggota_jabatan" class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Jabatan <span class="text-red-500">*</span></label>
                    <select id="anggota_jabatan" name="jabatan" required class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-sm text-slate-900 dark:text-white">
                        <option value="Ketua">Ketua</option>
                        <option value="Wakil">Wakil</option>
                        <option value="Sekretaris">Sekretaris</option>
                        <option value="Bendahara">Bendahara</option>
                        <option value="Anggota">Anggota</option>
                    </select>
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100 dark:border-white/5">
                    <button type="button" @click="document.getElementById('modal-tambah-anggota').classList.add('hidden')" class="px-5 py-2 rounded-xl text-[13px] font-bold text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-white/5 hover:bg-slate-200 dark:hover:bg-white/10 transition-all">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-[13px] rounded-xl shadow-lg shadow-blue-500/30 transition-all active:scale-95">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- Modal Edit Anggota --}}
<div id="modal-edit-anggota" class="hidden fixed inset-0 z-[60] flex items-center justify-center p-4 sm:p-6 bg-slate-900/60 dark:bg-slate-950/70 backdrop-blur-xl animate-fade-in" aria-modal="true"
     x-data="{}" @keydown.escape.window="document.getElementById('modal-edit-anggota').classList.add('hidden')">
    <div class="fixed inset-0" @click="document.getElementById('modal-edit-anggota').classList.add('hidden')"></div>
    <div class="relative w-full max-w-md bg-white dark:bg-[#151c2c] border border-slate-200 dark:border-white/10 rounded-2xl shadow-2xl overflow-hidden animate-scale-in flex flex-col">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-white/5 flex items-center justify-between">
            <h3 class="text-lg font-black text-slate-800 dark:text-white tracking-tight">Edit Panitia</h3>
            <button type="button" @click="document.getElementById('modal-edit-anggota').classList.add('hidden')" class="text-slate-400 hover:text-rose-500 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="p-6">
            <form id="form-edit-anggota" method="POST" class="space-y-3">
                @csrf
                @method('PUT')
                <div>
                    <label for="edit_anggota_nama" class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Nama <span class="text-red-500">*</span></label>
                    <input type="text" id="edit_anggota_nama" name="nama" required class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-sm text-slate-900 dark:text-white">
                </div>
                <div>
                    <label for="edit_anggota_jabatan" class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-1">Jabatan <span class="text-red-500">*</span></label>
                    <select id="edit_anggota_jabatan" name="jabatan" required class="w-full px-3 py-2 bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600 rounded-lg text-sm text-slate-900 dark:text-white">
                        <option value="Ketua">Ketua</option>
                        <option value="Wakil">Wakil</option>
                        <option value="Sekretaris">Sekretaris</option>
                        <option value="Bendahara">Bendahara</option>
                        <option value="Anggota">Anggota</option>
                    </select>
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100 dark:border-white/5">
                    <button type="button" @click="document.getElementById('modal-edit-anggota').classList.add('hidden')" class="px-5 py-2 rounded-xl text-[13px] font-bold text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-white/5 hover:bg-slate-200 dark:hover:bg-white/10 transition-all">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-[13px] rounded-xl shadow-lg shadow-blue-500/30 transition-all active:scale-95">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
function openEditAnggota(btn) {
    var id = btn.getAttribute('data-id');
    document.getElementById('form-edit-anggota').action = '{{ url("dashboard/kegiatan/{$kegiatan->id}/anggota") }}/' + id;
    document.getElementById('edit_anggota_nama').value = btn.getAttribute('data-nama') || '';
    document.getElementById('edit_anggota_jabatan').value = btn.getAttribute('data-jabatan') || 'Anggota';
    document.getElementById('modal-edit-anggota').classList.remove('hidden');
}

function openEditArsip(btn) {
    var id = btn.getAttribute('data-id');
    document.getElementById('form-edit-arsip').action = '{{ url("dashboard/arsip") }}/' + id;
    document.getElementById('edit_arsip_judul').value = btn.getAttribute('data-judul') || '';
    document.getElementById('edit_arsip_deskripsi').value = btn.getAttribute('data-deskripsi') || '';
    document.getElementById('modal-edit-arsip').classList.remove('hidden');
}
</script>
<div id="modal-tambah-tugas" class="hidden fixed inset-0 z-[60] flex items-center justify-center p-4 sm:p-6 bg-slate-900/60 dark:bg-slate-950/70 backdrop-blur-xl animate-fade-in" aria-modal="true"
     x-data="{}" @keydown.escape.window="document.getElementById('modal-tambah-tugas').classList.add('hidden')">
    <div class="fixed inset-0" @click="document.getElementById('modal-tambah-tugas').classList.add('hidden')"></div>
    <div class="relative w-full max-w-md bg-white dark:bg-[#151c2c] border border-slate-200 dark:border-white/10 rounded-2xl shadow-2xl overflow-hidden animate-scale-in flex flex-col">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-white/5 flex items-center justify-between">
            <h3 class="text-lg font-black text-slate-800 dark:text-white tracking-tight">Tambah Tugas Kegiatan</h3>
            <button type="button" @click="document.getElementById('modal-tambah-tugas').classList.add('hidden')" class="text-slate-400 hover:text-rose-500 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="p-6">
            <form action="{{ route('kegiatan.tugas.store', $kegiatan) }}" method="POST" class="space-y-4">
                @csrf
                <div>
                    <label for="modal_judul" class="block text-[13px] font-bold text-slate-700 dark:text-slate-300 mb-2 pl-1">Judul <span class="text-rose-500">*</span></label>
                    <input type="text" id="modal_judul" name="judul" required class="w-full h-11 px-4 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-transparent focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 rounded-xl text-sm text-slate-900 dark:text-white transition-all">
                    @error('judul')<p class="mt-2 text-[12px] font-bold text-rose-500 pl-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="modal_deskripsi" class="block text-[13px] font-bold text-slate-700 dark:text-slate-300 mb-2 pl-1">Deskripsi</label>
                    <textarea id="modal_deskripsi" name="deskripsi" rows="3" class="w-full p-4 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-transparent focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 rounded-xl text-sm text-slate-900 dark:text-white transition-all resize-none"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="modal_deadline" class="block text-[13px] font-bold text-slate-700 dark:text-slate-300 mb-2 pl-1">Deadline</label>
                        <input type="date" id="modal_deadline" name="deadline" class="w-full h-11 px-4 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-transparent focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 rounded-xl text-sm text-slate-900 dark:text-white transition-all">
                    </div>
                    <div>
                        <label for="modal_status" class="block text-[13px] font-bold text-slate-700 dark:text-slate-300 mb-2 pl-1">Status</label>
                        <select id="modal_status" name="status" class="w-full h-11 px-4 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-transparent focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 rounded-xl text-sm text-slate-900 dark:text-white transition-all appearance-none">
                            <option value="todo">Belum</option>
                            <option value="done">Sudah</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100 dark:border-white/5">
                    <button type="button" @click="document.getElementById('modal-tambah-tugas').classList.add('hidden')" class="px-5 py-2 rounded-xl text-[13px] font-bold text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-white/5 hover:bg-slate-200 dark:hover:bg-white/10 transition-all">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-[13px] rounded-xl shadow-lg shadow-blue-500/30 transition-all active:scale-95">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal Tambah Dokumentasi (internal workspace) --}}
<div id="modal-tambah-dokumentasi" class="hidden fixed inset-0 z-[60] flex items-center justify-center p-4 sm:p-6 bg-slate-900/60 dark:bg-slate-950/70 backdrop-blur-xl animate-fade-in" aria-modal="true"
     x-data="{}" @keydown.escape.window="document.getElementById('modal-tambah-dokumentasi').classList.add('hidden')">
    <div class="fixed inset-0" @click="document.getElementById('modal-tambah-dokumentasi').classList.add('hidden')"></div>
    <div class="relative w-full max-w-md bg-white dark:bg-[#151c2c] border border-slate-200 dark:border-white/10 rounded-2xl shadow-2xl overflow-hidden animate-scale-in flex flex-col">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-white/5 flex items-center justify-between">
            <h3 class="text-lg font-black text-slate-800 dark:text-white tracking-tight">Upload Dokumentasi</h3>
            <button type="button" @click="document.getElementById('modal-tambah-dokumentasi').classList.add('hidden')" class="text-slate-400 hover:text-rose-500 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="p-6">
            <form action="{{ route('kegiatan.dokumentasi.store', $kegiatan) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label for="doc_judul" class="block text-[13px] font-bold text-slate-700 dark:text-slate-300 mb-2 pl-1">Judul <span class="text-rose-500">*</span></label>
                    <input type="text" id="doc_judul" name="judul" required class="w-full h-11 px-4 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-transparent focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 rounded-xl text-sm text-slate-900 dark:text-white transition-all">
                    @error('judul')<p class="mt-2 text-[12px] font-bold text-rose-500 pl-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="doc_deskripsi" class="block text-[13px] font-bold text-slate-700 dark:text-slate-300 mb-2 pl-1">Deskripsi</label>
                    <textarea id="doc_deskripsi" name="deskripsi" rows="2" class="w-full p-4 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-transparent focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 rounded-xl text-sm text-slate-900 dark:text-white transition-all resize-none"></textarea>
                </div>
                <div>
                    <label for="doc_file" class="block text-[13px] font-bold text-slate-700 dark:text-slate-300 mb-2 pl-1">File Foto <span class="text-rose-500">*</span></label>
                    <input type="file" id="doc_file" name="file" accept="image/*" required class="w-full px-4 py-2 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-transparent focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 rounded-xl text-sm text-slate-900 dark:text-white transition-all">
                    @error('file')<p class="mt-2 text-[12px] font-bold text-rose-500 pl-1">{{ $message }}</p>@enderror
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100 dark:border-white/5">
                    <button type="button" @click="document.getElementById('modal-tambah-dokumentasi').classList.add('hidden')" class="px-5 py-2 rounded-xl text-[13px] font-bold text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-white/5 hover:bg-slate-200 dark:hover:bg-white/10 transition-all">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-[13px] rounded-xl shadow-lg shadow-blue-500/30 transition-all active:scale-95">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- Modal Tambah Arsip (internal workspace) --}}
<div id="modal-tambah-arsip" class="hidden fixed inset-0 z-[60] flex items-center justify-center p-4 sm:p-6 bg-slate-900/60 dark:bg-slate-950/70 backdrop-blur-xl animate-fade-in" aria-modal="true"
     x-data="{}" @keydown.escape.window="document.getElementById('modal-tambah-arsip').classList.add('hidden')">
    <div class="fixed inset-0" @click="document.getElementById('modal-tambah-arsip').classList.add('hidden')"></div>
    <div class="relative w-full max-w-md bg-white dark:bg-[#151c2c] border border-slate-200 dark:border-white/10 rounded-2xl shadow-2xl overflow-hidden animate-scale-in flex flex-col">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-white/5 flex items-center justify-between">
            <h3 class="text-lg font-black text-slate-800 dark:text-white tracking-tight">Upload Arsip</h3>
            <button type="button" @click="document.getElementById('modal-tambah-arsip').classList.add('hidden')" class="text-slate-400 hover:text-rose-500 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="p-6">
            <form action="{{ route('kegiatan.arsip.store', $kegiatan) }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div>
                    <label for="arsip_judul" class="block text-[13px] font-bold text-slate-700 dark:text-slate-300 mb-2 pl-1">Nama Dokumen <span class="text-rose-500">*</span></label>
                    <input type="text" id="arsip_judul" name="judul" required placeholder="Contoh: Proposal Kegiatan" class="w-full h-11 px-4 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-transparent focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 rounded-xl text-sm text-slate-900 dark:text-white transition-all placeholder:text-slate-400">
                    @error('judul')<p class="mt-2 text-[12px] font-bold text-rose-500 pl-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label for="arsip_deskripsi" class="block text-[13px] font-bold text-slate-700 dark:text-slate-300 mb-2 pl-1">Deskripsi Singkat</label>
                    <textarea id="arsip_deskripsi" name="deskripsi" rows="2" placeholder="Penjelasan singkat..." class="w-full p-4 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-transparent focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 rounded-xl text-sm text-slate-900 dark:text-white transition-all resize-none placeholder:text-slate-400"></textarea>
                </div>
                <div>
                    <label for="arsip_file" class="block text-[13px] font-bold text-slate-700 dark:text-slate-300 mb-2 pl-1">File Dokumen <span class="text-rose-500">*</span></label>
                    <input type="file" id="arsip_file" name="file" required class="w-full px-4 py-2 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-transparent focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 rounded-xl text-sm text-slate-900 dark:text-white transition-all file:mr-4 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-[11px] file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700 cursor-pointer">
                    <p class="mt-2 text-[10px] text-slate-500 dark:text-slate-400 pl-1 tracking-wide">Maks. 20MB (PDF, DOCX, ZIP, dll)</p>
                    @error('file')<p class="mt-2 text-[12px] font-bold text-rose-500 pl-1">{{ $message }}</p>@enderror
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100 dark:border-white/5">
                    <button type="button" @click="document.getElementById('modal-tambah-arsip').classList.add('hidden')" class="px-5 py-2 rounded-xl text-[13px] font-bold text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-white/5 hover:bg-slate-200 dark:hover:bg-white/10 transition-all">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-[13px] rounded-xl shadow-lg shadow-blue-500/30 transition-all active:scale-95">Simpan Arsip</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- Modal Edit Arsip --}}
<div id="modal-edit-arsip" class="hidden fixed inset-0 z-[60] flex items-center justify-center p-4 sm:p-6 bg-slate-900/60 dark:bg-slate-950/70 backdrop-blur-xl animate-fade-in" aria-modal="true"
     x-data="{}" @keydown.escape.window="document.getElementById('modal-edit-arsip').classList.add('hidden')">
    <div class="fixed inset-0" @click="document.getElementById('modal-edit-arsip').classList.add('hidden')"></div>
    <div class="relative w-full max-w-md bg-white dark:bg-[#151c2c] border border-slate-200 dark:border-white/10 rounded-2xl shadow-2xl overflow-hidden animate-scale-in flex flex-col">
        <div class="px-6 py-4 border-b border-slate-100 dark:border-white/5 flex items-center justify-between">
            <h3 class="text-lg font-black text-slate-800 dark:text-white tracking-tight">Edit Arsip</h3>
            <button type="button" @click="document.getElementById('modal-edit-arsip').classList.add('hidden')" class="text-slate-400 hover:text-rose-500 transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="p-6">
            <form id="form-edit-arsip" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf
                @method('PUT')
                <div>
                    <label for="edit_arsip_judul" class="block text-[13px] font-bold text-slate-700 dark:text-slate-300 mb-2 pl-1">Nama Dokumen <span class="text-rose-500">*</span></label>
                    <input type="text" id="edit_arsip_judul" name="judul" required class="w-full h-11 px-4 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-transparent focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 rounded-xl text-sm text-slate-900 dark:text-white transition-all">
                </div>
                <div>
                    <label for="edit_arsip_deskripsi" class="block text-[13px] font-bold text-slate-700 dark:text-slate-300 mb-2 pl-1">Deskripsi Singkat</label>
                    <textarea id="edit_arsip_deskripsi" name="deskripsi" rows="2" class="w-full p-4 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-transparent focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 rounded-xl text-sm text-slate-900 dark:text-white transition-all resize-none"></textarea>
                </div>
                <div>
                    <label for="edit_arsip_file" class="block text-[13px] font-bold text-slate-700 dark:text-slate-300 mb-2 pl-1">Update File (Opsional)</label>
                    <input type="file" id="edit_arsip_file" name="file" class="w-full px-4 py-2 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-transparent focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 rounded-xl text-sm text-slate-900 dark:text-white transition-all file:mr-4 file:py-1 file:px-2 file:rounded-md file:border-0 file:text-[11px] file:font-bold file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                    <p class="mt-2 text-[10px] text-slate-500 dark:text-slate-400 pl-1 tracking-wide">Kosongkan jika tidak ingin mengganti file.</p>
                </div>
                <div class="flex justify-end gap-3 pt-4 border-t border-slate-100 dark:border-white/5">
                    <button type="button" @click="document.getElementById('modal-edit-arsip').classList.add('hidden')" class="px-5 py-2 rounded-xl text-[13px] font-bold text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-white/5 hover:bg-slate-200 dark:hover:bg-white/10 transition-all">Batal</button>
                    <button type="submit" class="px-5 py-2 bg-blue-600 hover:bg-blue-700 text-white font-bold text-[13px] rounded-xl shadow-lg shadow-blue-500/30 transition-all active:scale-95">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endif
</x-admin-layout>

