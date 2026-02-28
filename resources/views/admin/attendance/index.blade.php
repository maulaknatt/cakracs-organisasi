<x-admin-layout title="Manajemen Absensi">
@php
    $user = auth()->user();
    $canManage = $user->isSuperAdmin() || $user->isPengurus() || $user->jabatan === 'Ketua';
@endphp

<div class="flex flex-col gap-8">
    <div class="flex flex-col lg:flex-row lg:items-center justify-between gap-6 animate-fade-in">
        <div class="space-y-1">
            <h1 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white tracking-tight">Manajemen Absensi</h1>
            <p class="text-[13px] md:text-sm text-slate-500 dark:text-slate-400 font-medium">Kelola kehadiran peserta di setiap kegiatan</p>
        </div>
        
        <div class="flex flex-col sm:flex-row gap-3 items-stretch sm:items-center">
            <form action="{{ route('attendance.index') }}" method="GET" class="relative group w-full sm:w-[260px] lg:w-[320px]">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari sesi..." 
                    class="w-full pl-10 pr-4 h-11 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl text-sm focus:ring-4 focus:ring-blue-500/10 focus:border-blue-500 transition-all outline-none text-slate-700 dark:text-slate-300 shadow-sm">
                <div class="absolute inset-y-0 left-0 pl-3.5 flex items-center pointer-events-none text-slate-400 group-focus-within:text-blue-500 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
            </form>
            
            <div class="flex items-center gap-2 sm:gap-3">
                <a href="{{ route('scan.index') }}" class="flex-1 sm:flex-none flex items-center justify-center gap-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-600 text-slate-700 dark:text-slate-300 h-11 px-4 rounded-xl text-sm font-bold hover:bg-slate-50 dark:hover:bg-slate-700 transition shadow-sm whitespace-nowrap">
                    <svg class="w-5 h-5 opacity-70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                    <span>Scan QR</span>
                </a>
                @if($canManage)
                <button onclick="openModal('create')" class="flex-1 sm:flex-none flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white h-11 px-4 rounded-xl text-sm font-bold transition shadow-lg shadow-blue-500/20 whitespace-nowrap">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"></path></svg>
                    <span>Sesi Baru</span>
                </button>
                @endif
            </div>
        </div>
    </div>



<div>
    <div class="max-h-[640px] overflow-y-auto px-1 -mx-1 custom-scrollbar scroll-smooth">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 pb-6">
            @forelse($sessions as $session)
            @php
                if ($session->is_active) {
                    $statusColor = 'bg-blue-600';
                    $borderColor = 'border-t-blue-600';
                    $badgeBg     = 'bg-blue-50 dark:bg-blue-500/10 text-blue-700 dark:text-blue-400 border-blue-200 dark:border-blue-500/20';
                } else {
                    $statusColor = 'bg-blue-500';
                    $borderColor = 'border-t-blue-400';
                    $badgeBg     = 'bg-blue-50/80 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 border-blue-100 dark:border-blue-500/10';
                }
            @endphp
            <div class="card !p-0 overflow-hidden h-full flex flex-col group !bg-white dark:!bg-[#171e30]/60 !border-0 !border-t-4 {{ $borderColor }} !rounded-2xl !shadow-md hover:!shadow-xl transition-all duration-300 relative cursor-pointer"
                 onclick="if(!event.target.closest('button') && !event.target.closest('a') && !event.target.closest('form')) window.location='{{ route('attendance.show', $session) }}'">
                
                <div class="p-4 md:p-6 flex flex-col flex-1">
                    {{-- Top: Icon + Status --}}
                    <div class="flex items-start justify-between mb-4">
                        <div class="w-10 h-10 md:w-11 md:h-11 rounded-xl flex items-center justify-center {{ $statusColor }}/10 border border-current/10">
                            <svg class="w-4.5 h-4.5 md:w-5 md:h-5 text-current {{ str_replace('bg-', 'text-', $statusColor) }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path>
                            </svg>
                        </div>
                        <span class="inline-flex items-center gap-1 px-2.5 py-1.5 rounded-full text-[10px] md:text-[11px] font-bold uppercase tracking-wider border {{ $badgeBg }}">
                            @if($session->is_active)
                                <span class="w-1 h-1 md:w-1.5 md:h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                            @endif
                            <span class="hidden sm:inline">{{ $session->is_active ? 'Sesi Aktif' : 'Sesi Ditutup' }}</span>
                            <span class="sm:hidden">{{ $session->is_active ? 'Aktif' : 'Tutup' }}</span>
                        </span>
                    </div>

                    {{-- Title --}}
                    <div class="flex items-start gap-3 justify-between">
                        <h3 class="flex-1 text-base md:text-lg font-black text-blue-900 dark:text-white leading-tight mb-2 group-hover:text-blue-600 transition-colors line-clamp-2 min-w-0">
                            {{ $session->title }}
                        </h3>
                        
                        {{-- Edit & Delete Actions --}}
                        @if($canManage)
                        <div class="flex gap-1 shrink-0 -mt-1 -mr-1">
                            <button onclick="openModal('edit', {{ $session }})" class="p-1.5 text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors rounded-lg bg-white/50 dark:bg-slate-800/50 hover:bg-blue-50 dark:hover:bg-blue-500/10" title="Edit">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </button>
                            <form action="{{ route('attendance.destroy', $session) }}" method="POST" class="inline-block">
                                @csrf @method('DELETE')
                                <button type="button" onclick="window.showModalConfirm(this.closest('form'), 'Hapus Sesi?', 'Hapus sesi ini? Semua data kehadiran akan hilang secara permanen.')" class="p-1.5 text-slate-400 hover:text-rose-600 dark:hover:text-rose-400 transition-colors rounded-lg bg-white/50 dark:bg-slate-800/50 hover:bg-rose-50 dark:hover:bg-rose-500/10" title="Hapus">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                </button>
                            </form>
                        </div>
                        @endif
                    </div>

                    {{-- Date & Stats --}}
                    <div class="flex flex-wrap items-center gap-x-4 gap-y-2 text-[12px] md:text-[13px] text-blue-600/70 dark:text-blue-400/70 mb-5 md:mb-6 font-medium">
                        <div class="flex items-center gap-1.5 whitespace-nowrap">
                            <svg class="w-4 h-4 opacity-70" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span>{{ $session->date->translatedFormat('d F Y') }}</span>
                        </div>
                        <div class="flex items-center gap-1.5 whitespace-nowrap">
                            <svg class="w-4 h-4 text-blue-400 opacity-70" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span class="font-bold text-blue-600 dark:text-blue-300">{{ $session->logs->unique('user_id')->count() }}</span> Hadir
                        </div>
                    </div>

                    {{-- Footer: Actions --}}
                    <div class="mt-auto pt-4 border-t border-blue-100 dark:border-white/5 flex flex-col gap-2.5">
                        <a href="{{ route('attendance.show', $session) }}" class="flex items-center justify-center gap-2 border border-blue-200 dark:border-blue-500/20 text-blue-700 dark:text-blue-300 h-11 bg-blue-50 dark:bg-blue-500/10 hover:bg-blue-100 dark:hover:bg-blue-500/20 rounded-xl text-[13px] font-bold transition w-full">
                            Detail Absensi
                        </a>
                        
                        @if($canManage)
                            <div class="grid {{ $session->is_active ? 'grid-cols-2' : 'grid-cols-1' }} gap-2.5">
                                @if($session->is_active)
                                    <a href="{{ route('attendance.show', $session) }}?open_qr=true" class="flex items-center justify-center gap-2 bg-blue-600 hover:bg-blue-700 text-white shadow-lg shadow-blue-500/20 h-11 rounded-xl text-[12px] md:text-[13px] font-bold transition active:scale-95 px-2 text-center">
                                        <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"></path></svg>
                                        <span>Show QR</span> 
                                    </a>
                                    <form action="{{ route('attendance.toggle', $session) }}" method="POST" class="w-full">
                                        @csrf
                                        <button type="submit" class="w-full h-11 flex justify-center items-center rounded-xl text-[12px] md:text-[13px] font-bold transition bg-rose-50 text-rose-600 hover:bg-rose-100 border border-transparent hover:border-rose-200 dark:bg-rose-500/10 dark:text-rose-400 dark:hover:bg-rose-500/20 active:scale-95 px-2 text-center">
                                            Akhiri Sesi
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('attendance.toggle', $session) }}" method="POST" class="w-full">
                                        @csrf
                                        <button type="submit" class="w-full h-11 py-2.5 rounded-xl text-[13px] font-bold shadow-lg shadow-blue-500/20 transition active:scale-95 bg-blue-600 text-white hover:bg-blue-700 px-4">
                                            Buka Sesi Kembali
                                        </button>
                                    </form>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full py-20 text-center animate-fade-in">
                <div class="w-20 h-20 bg-slate-50 dark:bg-white/5 rounded-3xl flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/></svg>
                </div>
                <h3 class="text-xl font-black text-slate-900 dark:text-white tracking-tight">Belum Ada Sesi Absensi</h3>
                <p class="text-slate-500 dark:text-slate-400 font-medium mt-1">Sesi absensi akan tampil di sini.</p>
                @if($canManage)
                <button onclick="openModal('create')" class="btn-primary mt-6 inline-flex items-center gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 5v14M5 12h14"/></svg>
                    Buat Sesi Pertama
                </button>
                @endif
            </div>
            @endforelse
        </div>
    </div>
</div>

@if($sessions->hasPages())
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 p-4 rounded-xl shadow-sm">
        <div class="text-xs text-slate-600 dark:text-slate-400">
            Menampilkan <span class="font-medium text-slate-900 dark:text-white">{{ $sessions->firstItem() }}</span> 
            sampai <span class="font-medium text-slate-900 dark:text-white">{{ $sessions->lastItem() }}</span> 
            dari <span class="font-medium text-slate-900 dark:text-white">{{ $sessions->total() }}</span> sesi
        </div>
        <div class="pagination-container">
            {{ $sessions->links() }}
        </div>
    </div>
@endif

</div>

@push('modals')
{{-- Create/Edit Modal (Centered Fixed) --}}
<div id="modal-form" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6 bg-slate-950/80 backdrop-blur-lg transition-opacity duration-300 opacity-0 pointer-events-none">
    <div class="w-full max-w-xl bg-white dark:bg-[#111827] border border-slate-200 dark:border-white/5 rounded-2xl shadow-2xl relative transform scale-95 transition-transform duration-300 flex flex-col max-h-[90vh]">
        
        <div class="px-6 py-5 border-b border-slate-100 dark:border-white/5 flex items-center justify-between bg-slate-50/50 dark:bg-transparent rounded-t-2xl">
            <div>
                <h3 id="modal-title" class="text-xl font-black text-slate-800 dark:text-white tracking-tight">Buat Sesi Baru</h3>
                <p class="text-[13px] font-medium text-slate-500 dark:text-slate-400 mt-1">Kelola absensi untuk kegiatan</p>
            </div>
            <button type="button" onclick="closeModal()" class="w-9 h-9 rounded-full bg-slate-100 dark:bg-white/5 text-slate-500 dark:text-slate-400 hover:text-rose-500 dark:hover:text-rose-400 hover:bg-rose-50 dark:hover:bg-rose-500/10 flex items-center justify-center transition-all">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <div class="p-6 sm:p-8 overflow-y-auto custom-scrollbar flex-1">

        <form id="attendance-form" method="POST" action="{{ route('attendance.store') }}" class="space-y-6">
            @csrf
            <div id="method-field"></div> {{-- For PUT method --}}
            
            <div>
                <label class="block text-[13px] font-bold text-slate-700 dark:text-slate-300 mb-2 pl-1">Judul Sesi</label>
                <input type="text" id="input-title" name="title" required class="w-full h-12 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-transparent focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:focus:border-blue-500/50 rounded-xl px-4 text-[14px] font-medium text-slate-900 dark:text-white transition-all placeholder:text-slate-400 dark:placeholder:text-slate-500/70" placeholder="Contoh: Rapat Bulanan">
            </div>
            
            <div>
                <label class="block text-[13px] font-bold text-slate-700 dark:text-slate-300 mb-2 pl-1">Tanggal</label>
                <input type="date" id="input-date" name="date" required value="{{ date('Y-m-d') }}" class="w-full h-12 bg-slate-50 dark:bg-white/5 border border-slate-200 dark:border-transparent focus:border-blue-500 focus:ring-4 focus:ring-blue-500/10 dark:focus:border-blue-500/50 rounded-xl px-4 text-[14px] font-medium text-slate-900 dark:text-white transition-all color-scheme-dark dark:color-scheme-dark">
            </div>

            <div class="pt-6 flex items-center justify-end gap-3 border-t border-slate-100 dark:border-white/5">
                <button type="button" onclick="closeModal()" class="flex items-center justify-center px-6 h-11 rounded-xl text-[13px] font-bold text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-white/5 hover:bg-slate-200 dark:hover:bg-white/10 dark:hover:text-white transition-all">Batal</button>
                <button type="submit" class="px-6 h-11 bg-blue-600 hover:bg-blue-700 text-white font-bold text-[13px] rounded-xl shadow-lg shadow-blue-500/30 dark:shadow-blue-500/20 active:scale-95 transition-all">Simpan</button>
            </div>
        </form>
        </div>
    </div>
</div>
@endpush

@push('scripts')
<script>
    function openModal(mode, data = null) {
        const modal = document.getElementById('modal-form');
        const form = document.getElementById('attendance-form');
        const title = document.getElementById('modal-title');
        const inputTitle = document.getElementById('input-title');
        const inputDate = document.getElementById('input-date');
        const methodField = document.getElementById('method-field');

        // Animation
        modal.classList.remove('opacity-0', 'pointer-events-none');
        modal.classList.add('opacity-100', 'pointer-events-auto');
        modal.firstElementChild.classList.remove('scale-95');
        modal.firstElementChild.classList.add('scale-100');

        if (mode === 'create') {
            title.innerText = 'Buat Sesi Baru';
            form.action = "{{ route('attendance.store') }}";
            methodField.innerHTML = '';
            inputTitle.value = '';
            inputDate.value = "{{ date('Y-m-d') }}";
        } else if (mode === 'edit' && data) {
            title.innerText = 'Edit Sesi';
            form.action = `/dashboard/attendance/${data.id}`;
            methodField.innerHTML = '@method("PUT")';
            inputTitle.value = data.title;
            // Format date YYYY-MM-DD
            inputDate.value = data.date.split('T')[0];
        }
    }

    function closeModal() {
        const modal = document.getElementById('modal-form');
        modal.classList.remove('opacity-100', 'pointer-events-auto');
        modal.classList.add('opacity-0', 'pointer-events-none');
        modal.firstElementChild.classList.remove('scale-100');
        modal.firstElementChild.classList.add('scale-95');
    }
</script>
@endpush
</x-admin-layout>

