<x-admin-layout title="Keuangan">
<div class="flex flex-col gap-6">

<div class="page-header">
    <div>
        <h1 class="page-header-title">Laporan Keuangan</h1>
        <p class="page-header-sub">Pantau arus kas global dan rincian dana per program</p>
    </div>
    <div class="flex items-center gap-2.5">
        <a href="{{ route('keuangan.export', request()->all()) }}" class="btn-secondary gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            Export
        </a>
        <a href="{{ route('keuangan.create') }}" data-turbo-frame="modal" class="btn-primary gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
            </svg>
            Catat Transaksi
        </a>
    </div>
</div>

{{-- Summary Stats --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
    <div class="card-premium">
        <p class="form-label mb-3">Total Pemasukan</p>
        <p class="text-[24px] font-bold leading-none text-green-500 dark:text-green-400" style="letter-spacing:-.025em;">
            +Rp {{ number_format($globalPemasukan, 0, ',', '.') }}
        </p>
    </div>
    <div class="card-premium">
        <p class="form-label mb-3">Total Pengeluaran</p>
        <p class="text-[24px] font-bold leading-none text-red-500 dark:text-red-400" style="letter-spacing:-.025em;">
            -Rp {{ number_format($globalPengeluaran, 0, ',', '.') }}
        </p>
    </div>
    <div class="card-premium">
        <p class="form-label mb-3">Saldo Bersih</p>
        <p class="text-[24px] font-bold leading-none {{ $globalSaldo >= 0 ? 'text-green-500 dark:text-green-400' : 'text-red-500 dark:text-red-400' }}" style="letter-spacing:-.025em;">
            {{ $globalSaldo >= 0 ? '' : '-' }}Rp {{ number_format(abs($globalSaldo), 0, ',', '.') }}
        </p>
    </div>
</div>

{{-- Tabs --}}
<div x-data="{ tab: 'global' }">
    <div class="flex mb-14">
        <div class="bg-white dark:bg-slate-900 rounded-[20px] border border-slate-200 dark:border-white/5 p-1 shadow-sm inline-flex items-center gap-1">
            <button @click="tab = 'global'" 
                    :class="tab === 'global' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-white/5'" 
                    class="px-5 py-2 rounded-[16px] text-[13px] font-bold transition-all duration-200">
                Riwayat Global
            </button>
            <button @click="tab = 'perkegiatan'" 
                    :class="tab === 'perkegiatan' ? 'bg-blue-600 text-white shadow-md shadow-blue-500/20' : 'text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200 hover:bg-slate-50 dark:hover:bg-white/5'" 
                    class="px-5 py-2 rounded-[16px] text-[13px] font-bold transition-all duration-200">
                Per Program
            </button>
        </div>
    </div>

    {{-- Global tab --}}
    <div x-show="tab === 'global'" x-transition>
        <div class="h-3 w-full"></div>
        {{-- Filter area - Compact & Static --}}
        <div class="bg-white dark:bg-slate-900 rounded-[22px] border border-slate-200 dark:border-white/5 p-4 shadow-sm relative z-20">
            <form method="GET" action="{{ route('keuangan.index') }}"
                  x-data="{ open: {{ request()->anyFilled(['kegiatan_id','jenis','bulan','tahun']) ? 'true' : 'false' }} }">
                <div class="flex items-center gap-3">
                    <div class="relative flex-1">
                        <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" name="search" value="{{ request('search') }}"
                               placeholder="Cari deskripsi transaksi..." class="form-input pl-10 h-10 text-[13px]">
                    </div>
                    <button type="button" @click="open = !open" class="btn-secondary h-10 px-4 gap-2 shrink-0 text-[13px]">
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h18M7 9h10m-5 5h5"/>
                        </svg>
                        Filter
                    </button>
                    <button type="submit" class="btn-primary h-10 px-6 shrink-0 text-[13px] font-bold">Cari</button>
                </div>
                <div x-show="open" x-transition class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-4">
                    <select name="kegiatan_id" class="form-select">
                        <option value="">Semua Program</option>
                        @foreach($kegiatanList as $kegiatan)
                            <option value="{{ $kegiatan->id }}" {{ request('kegiatan_id') == $kegiatan->id ? 'selected' : '' }}>{{ $kegiatan->judul }}</option>
                        @endforeach
                    </select>
                    <select name="jenis" class="form-select">
                        <option value="">Semua Arus</option>
                        <option value="masuk" {{ request('jenis') == 'masuk' ? 'selected' : '' }}>Masuk (+)</option>
                        <option value="keluar" {{ request('jenis') == 'keluar' ? 'selected' : '' }}>Keluar (-)</option>
                    </select>
                    <select name="bulan" class="form-select">
                        <option value="">Bulan</option>
                        @for($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ request('bulan') == (string)$i ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($i)->locale('id')->monthName }}</option>
                        @endfor
                    </select>
                    <select name="tahun" class="form-select">
                        <option value="">Tahun</option>
                        @foreach($years as $year)
                            <option value="{{ $year }}" {{ request('tahun') == $year ? 'selected' : '' }}>{{ $year }}</option>
                        @endforeach
                    </select>
                </div>
            </form>
        </div>
        <div class="h-4 w-full"></div>
        {{-- Table Card --}}
        <div class="bg-white dark:bg-slate-900 rounded-[24px] border border-slate-200 dark:border-white/5 shadow-sm overflow-hidden flex flex-col">
            <div class="max-h-[600px] overflow-y-auto overflow-x-auto custom-scrollbar flex-1 relative">
                <table class="w-full text-left text-sm border-separate border-spacing-0">
                    <thead class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-white/5 text-slate-500 dark:text-slate-400 sticky top-0 z-10">
                        <tr>
                            <th class="px-5 py-4 font-bold text-[12px] uppercase tracking-wider">Tanggal</th>
                            <th class="px-5 py-4 font-bold text-[12px] uppercase tracking-wider">Deskripsi</th>
                            <th class="px-5 py-4 font-bold text-[12px] uppercase tracking-wider">Program / Sumber</th>
                            <th class="px-5 py-4 font-bold text-[12px] uppercase tracking-wider">Arus</th>
                            <th class="px-5 py-4 font-bold text-[12px] uppercase tracking-wider">Jumlah</th>
                            <th class="px-5 py-4 font-bold text-[12px] uppercase tracking-wider text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                        @forelse($keuangan as $item)
                        <tr class="transition-colors group">
                            <td class="px-5 py-4 whitespace-nowrap text-[13px] font-medium text-slate-500 dark:text-slate-400">
                                {{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}
                            </td>
                            <td class="px-5 py-4">
                                <span class="font-bold text-slate-800 dark:text-slate-200 line-clamp-1 truncate max-w-[250px]" title="{{ $item->judul }}">{{ $item->judul }}</span>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <span class="inline-flex px-2.5 py-1 text-[11px] font-bold text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 rounded-lg border border-slate-200 dark:border-white/5">
                                    {{ optional($item->kegiatan)->judul ?? 'Kas Umum' }}
                                </span>
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                @if($item->jenis == 'masuk')
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Masuk (+)
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 border border-red-100 dark:border-red-500/20">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                        Keluar (-)
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap font-black text-[15px] {{ $item->jenis == 'masuk' ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                                {{ $item->jenis == 'masuk' ? '+' : '-' }}Rp {{ number_format($item->jumlah, 0, ',', '.') }}
                            </td>
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('keuangan.edit', $item->id) }}" data-turbo-frame="modal" class="btn-icon p-1.5 hover:bg-blue-50 text-slate-400 hover:text-blue-500 transition-all rounded-lg">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                    </a>
                                    <form method="POST" action="{{ route('keuangan.destroy', $item->id) }}" onsubmit="event.preventDefault(); window.showModalConfirm(this, 'Hapus Transaksi', 'Apakah Anda yakin ingin menghapus transaksi ini?')">
                                        @csrf @method('DELETE')
                                        <button type="button" onclick="window.showModalConfirm(this.closest('form'), 'Hapus Transaksi', 'Transaksi akan dihapus permanen.')" class="btn-icon p-1.5 text-slate-400 hover:text-red-500 hover:bg-red-50 transition-all rounded-lg">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="py-12">
                                <div class="empty-state">
                                    <div class="empty-state-icon">
                                        <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" stroke-width="1.5"/></svg>
                                    </div>
                                    <p class="empty-state-title">Belum ada transaksi</p>
                                    <p class="empty-state-sub">Catat pemasukan atau pengeluaran pertama</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if($keuangan->hasPages())
            <div class="mt-6">{{ $keuangan->links() }}</div>
        @endif
    </div>

    {{-- Per Program tab --}}
    <div x-show="tab === 'perkegiatan'" x-transition class="pt-2">
        <div class="grid-cards">
            @forelse($keuanganPerKegiatan as $item)
                <div class="card-premium flex flex-col group transition-all hover:-translate-y-0.5">
                    <p class="text-[15px] font-bold mb-5 line-clamp-2 text-slate-800 dark:text-slate-100">{{ $item['kegiatan']->judul }}</p>
                    <div class="space-y-3.5 mb-6">
                        <div class="flex items-center justify-between">
                            <span class="text-[14px] font-medium text-slate-500 dark:text-slate-400">Pemasukan</span>
                            <span class="text-[15px] font-bold text-green-500">+Rp {{ number_format($item['pemasukan'], 0, ',', '.') }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-[14px] font-medium text-slate-500 dark:text-slate-400">Pengeluaran</span>
                            <span class="text-[15px] font-bold text-red-400">-Rp {{ number_format($item['pengeluaran'], 0, ',', '.') }}</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between pt-3.5 border-t border-slate-100 dark:border-white/6 mt-auto">
                        <span class="text-[13px] font-medium text-slate-400">{{ $item['transaksi'] }} Transaksi</span>
                        <a href="{{ route('kegiatan.show', $item['kegiatan']->id) }}?tab=keuangan"
                           class="text-[13.5px] font-semibold text-blue-500 hover:text-blue-600 dark:text-blue-400 inline-flex items-center gap-1.5">
                            Detail
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    </div>
                </div>
            @empty
                <div class="col-span-full">
                    <div class="card">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <p class="empty-state-title">Belum ada data</p>
                            <p class="empty-state-sub">Berdasarkan program belum ada transaksi</p>
                        </div>
                    </div>
                </div>
            @endforelse
        </div>
    </div>
</div>
</x-admin-layout>

