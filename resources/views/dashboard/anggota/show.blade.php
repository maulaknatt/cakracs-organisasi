<x-admin-layout title="Profil Anggota">

{{-- Back --}}
<div class="mb-6">
    <a href="{{ route('anggota.index') }}" class="inline-flex items-center gap-2 text-[14px] font-semibold text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/>
        </svg>
        Kembali ke Daftar Anggota
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

    {{-- Left Column: Profile Overview --}}
    <div class="lg:col-span-1 flex flex-col gap-6">
        <div class="bg-white dark:bg-[#11131D] rounded-[40px] border border-slate-200 dark:border-white/5 shadow-2xl overflow-hidden relative">
            
            {{-- Header Decor --}}
            <div class="h-32 bg-gradient-to-br from-blue-600 via-indigo-600 to-violet-700 relative overflow-hidden">
                <div class="absolute inset-0 opacity-10" style="background-image: url('data:image/svg+xml,%3Csvg width=\"20\" height=\"20\" viewBox=\"0 0 20 20\" xmlns=\"http://www.w3.org/2000/svg\"%3E%3Cpath d=\"M0 0h20v20H0V0zm10 17a7 7 0 1 0 0-14 7 7 0 0 0 0 14zm0-1a6 6 0 1 1 0-12 6 6 0 0 1 0 12z\" fill=\"%23ffffff\" fill-opacity=\"0.4\" fill-rule=\"evenodd\"%3E%3C/path%3E%3C/svg%3E');"></div>
                <div class="absolute -right-10 -top-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
            </div>

            <div class="px-8 pb-10 -mt-16 relative z-10 text-center flex flex-col items-center">
                {{-- Round Avatar with Glow --}}
                <div class="w-32 h-32 rounded-full p-1.5 bg-white dark:bg-[#0b0c14] shadow-2xl mb-6 relative group">
                    <div class="w-full h-full rounded-full flex items-center justify-center text-4xl font-black text-white overflow-hidden bg-gradient-to-br from-blue-500 to-indigo-700 shadow-inner">
                        @if($anggota->foto_profil)
                            <img src="{{ asset('storage/'.$anggota->foto_profil) }}" class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr($anggota->name, 0, 1)) }}
                        @endif
                    </div>
                    <div class="absolute bottom-2 right-2 w-6 h-6 rounded-full border-4 border-white dark:border-[#0b0c14] {{ $anggota->is_active ? 'bg-green-500 shadow-[0_0_10px_rgba(34,197,94,0.6)]' : 'bg-slate-400' }}"></div>
                </div>

                <h2 class="text-2xl font-black text-slate-800 dark:text-white tracking-tight mb-1">{{ $anggota->name }}</h2>
                <p class="text-[13px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-6">
                    {{ $anggota->email }}
                </p>

                {{-- Status Badges --}}
                <div class="flex flex-wrap justify-center gap-2 mb-8">
                    <div class="px-4 py-1.5 rounded-full bg-blue-500/10 text-blue-500 border border-blue-500/20 text-[10px] font-black uppercase tracking-[0.2em]">
                        {{ $anggota->jabatan ?? 'Anggota Biasa' }}
                    </div>
                    <div class="px-4 py-1.5 rounded-full {{ $anggota->is_active ? 'bg-green-500/10 text-green-500 border-green-500/20' : 'bg-slate-500/10 text-slate-400 border-slate-500/20' }} text-[10px] font-black uppercase tracking-[0.2em]">
                        {{ $anggota->is_active ? 'Active Account' : 'Inactive' }}
                    </div>
                </div>

                {{-- Actions --}}
                @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
                <div class="w-full grid grid-cols-2 gap-3 pt-8 border-t border-slate-100 dark:border-white/5">
                    <a href="{{ route('anggota.edit', $anggota->id) }}" data-turbo-frame="modal" 
                       class="flex items-center justify-center gap-2 px-6 py-3 rounded-2xl bg-slate-50 dark:bg-white/[0.03] text-slate-600 dark:text-slate-300 font-black text-[11px] uppercase tracking-widest hover:bg-blue-600 hover:text-white transition-all duration-300 border border-slate-100 dark:border-white/5">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        Edit
                    </a>
                    <form action="{{ route('anggota.destroy', $anggota->id) }}" method="POST" class="w-full">
                        @csrf @method('DELETE')
                        <button type="button"
                                onclick="window.showModalConfirm(this.closest('form'), 'Hapus Anggota?', 'Data permanen akan terhapus.', 'Hapus Pelan-pelan', 'Batal')"
                                class="w-full flex items-center justify-center gap-2 px-6 py-3 rounded-2xl bg-red-500/10 text-red-500 font-black text-[11px] uppercase tracking-widest hover:bg-red-500 hover:text-white transition-all duration-300 border border-red-500/20">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            Delete
                        </button>
                    </form>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Right Column: Detailed Info --}}
    <div class="lg:col-span-2 flex flex-col gap-6">
        
        {{-- Member Information --}}
        <div class="bg-white dark:bg-[#11131D] rounded-[40px] border border-slate-200 dark:border-white/5 shadow-2xl p-8 lg:p-10 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-8 opacity-[0.02] dark:opacity-[0.05] pointer-events-none">
                <svg class="w-48 h-48" fill="currentColor" viewBox="0 0 24 24"><path d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
            </div>

            <div class="flex items-center gap-4 mb-10">
                <div class="w-12 h-12 rounded-2xl bg-blue-600 flex items-center justify-center text-white shadow-lg shadow-blue-500/20">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"/></svg>
                </div>
                <div>
                    <h3 class="text-xl font-black text-slate-800 dark:text-white tracking-tight uppercase">Detail Personel</h3>
                    <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.3em]">Institutional ID & Records</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach([
                    ['FullName', $anggota->name, 'text-slate-800 dark:text-white font-black', 'M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z'],
                    ['Communication', $anggota->email, 'text-slate-600 dark:text-slate-400 font-bold', 'M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                    ['Position', $anggota->jabatan ?? 'Anggota Biasa', 'text-blue-600 dark:text-blue-400 font-black', 'M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                ] as [$label, $value, $class, $svg])
                    <div class="group/info bg-slate-50 dark:bg-white/[0.02] rounded-[28px] p-6 border border-slate-100 dark:border-white/5 shadow-sm hover:border-blue-500/30 transition-all duration-300">
                        <div class="flex items-start gap-4">
                            <div class="w-10 h-10 rounded-2xl bg-white dark:bg-white/5 flex items-center justify-center text-slate-400 group-hover/info:text-blue-500 transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $svg }}"/></svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-slate-500 mb-1.5">{{ $label }}</p>
                                <p class="text-[15px] {{ $class }} tracking-tight leading-none break-all">{{ $value }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach

                <div class="bg-slate-50 dark:bg-white/[0.02] rounded-[28px] p-6 border border-slate-100 dark:border-white/5 shadow-sm">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-2xl bg-white dark:bg-white/5 flex items-center justify-center text-slate-400">
                             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        </div>
                        <div>
                            <p class="text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-slate-500 mb-1.5">Established At</p>
                            <p class="text-[15px] text-slate-800 dark:text-slate-200 font-black tracking-tight">
                                {{ $anggota->created_at?->translatedFormat('d F Y') ?? 'Unknown' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="bg-slate-50 dark:bg-white/[0.02] rounded-[28px] p-6 border border-slate-100 dark:border-white/5 shadow-sm">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 rounded-2xl bg-white dark:bg-white/5 flex items-center justify-center text-slate-400">
                             <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div>
                            <p class="text-[9px] font-black uppercase tracking-[0.25em] text-slate-400 dark:text-slate-500 mb-1.5">Availability</p>
                            <div class="flex items-center gap-2">
                                <div class="w-2.5 h-2.5 rounded-full {{ $anggota->is_active ? 'bg-green-500' : 'bg-slate-500' }}"></div>
                                <p class="text-[15px] font-black tracking-tight {{ $anggota->is_active ? 'text-green-500' : 'text-slate-500' }}">
                                    {{ $anggota->is_active ? 'Fully Active' : 'Restricted' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Contribution Dashboard (Placeholder) --}}
        <div class="bg-white dark:bg-[#11131D] rounded-[40px] border border-slate-200 dark:border-white/5 shadow-2xl p-8 lg:p-10">
            <div class="flex items-center justify-between mb-10">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-emerald-500 flex items-center justify-center text-white shadow-lg shadow-emerald-500/20">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/></svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-black text-slate-800 dark:text-white tracking-tight uppercase">Riwayat Kontribusi</h3>
                        <p class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-[0.3em]">Institutional Metrics</p>
                    </div>
                </div>
            </div>

            <div class="relative group p-12 text-center rounded-[32px] bg-slate-50 dark:bg-white/[0.01] border border-dashed border-slate-200 dark:border-white/5 overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-blue-500/5 opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                
                <div class="relative z-10">
                    <div class="w-20 h-20 rounded-3xl bg-white dark:bg-white/5 border border-slate-100 dark:border-white/5 flex items-center justify-center mx-auto mb-6 transform group-hover:rotate-12 transition-transform duration-700 shadow-xl">
                        <svg class="w-10 h-10 text-slate-200 dark:text-white/10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                    </div>
                    <h4 class="text-[13px] font-black text-slate-400 dark:text-white/30 uppercase tracking-[0.4em] mb-2 leading-none">Awaiting Data</h4>
                    <p class="text-[11px] font-bold text-slate-300 dark:text-slate-600 uppercase tracking-[0.2em]">Institutional logs for this personnel are currently being aggregated</p>
                </div>
            </div>
        </div>
    </div>
</div>

<turbo-frame id="modal"></turbo-frame>
</x-admin-layout>
