<x-admin-layout title="Detail Transaksi">

<a href="{{ route('keuangan.index') }}" class="back-link">
    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
    Kembali ke Keuangan
</a>

<div class="max-w-xl">
    <div class="card-premium p-6">
        {{-- Amount Header --}}
        <div class="text-center mb-6 pb-5 border-b border-slate-100 dark:border-slate-800">
            <span class="inline-flex items-center gap-1.5 mb-2">
                @if($keuangan->jenis === 'masuk')
                    <span class="badge badge-green">Pemasukan</span>
                @else
                    <span class="badge badge-red">Pengeluaran</span>
                @endif
            </span>
            <p class="text-3xl font-black {{ $keuangan->jenis === 'masuk' ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">
                {{ $keuangan->jenis === 'masuk' ? '+' : '-' }}Rp {{ number_format($keuangan->jumlah, 0, ',', '.') }}
            </p>
            <p class="text-sm font-semibold text-slate-700 dark:text-slate-300 mt-1">{{ $keuangan->judul }}</p>
        </div>

        {{-- Detail Grid --}}
        <div class="space-y-3">
            <div class="flex justify-between items-center py-2 border-b border-slate-50 dark:border-slate-800/60">
                <span class="form-label !mb-0">Tanggal</span>
                <span class="text-sm font-semibold text-slate-800 dark:text-slate-200">
                    {{ \Carbon\Carbon::parse($keuangan->tanggal)->translatedFormat('d F Y') }}
                </span>
            </div>
            <div class="flex justify-between items-center py-2 border-b border-slate-50 dark:border-slate-800/60">
                <span class="form-label !mb-0">Deskripsi</span>
                <span class="text-sm text-slate-600 dark:text-slate-400 max-w-xs text-right">{{ $keuangan->deskripsi ?: '—' }}</span>
            </div>
            <div class="flex justify-between items-center py-2">
                <span class="form-label !mb-0">Kegiatan</span>
                <span class="text-sm font-semibold text-slate-800 dark:text-slate-200">
                    {{ optional($keuangan->kegiatan)->judul ?? 'Umum' }}
                </span>
            </div>
        </div>

        <div class="divider"></div>
        <div class="flex items-center justify-end gap-3">
            <a href="{{ route('keuangan.edit', $keuangan->id) }}" class="btn-secondary">Edit</a>
            <form action="{{ route('keuangan.destroy', $keuangan->id) }}" method="POST" class="inline">
                @csrf @method('DELETE')
                <button type="button"
                        onclick="window.showModalConfirm(this.closest('form'), 'Hapus Transaksi?', 'Data transaksi ini akan dihapus permanen.', 'Hapus', 'Batal')"
                        class="btn-danger">
                    Hapus
                </button>
            </form>
        </div>
    </div>
</div>

</x-admin-layout>
