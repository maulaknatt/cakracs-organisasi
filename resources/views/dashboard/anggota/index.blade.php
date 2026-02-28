<x-admin-layout title="Anggota">
<div class="flex flex-col gap-6">

<div class="page-header">
    <div>
        <h1 class="page-header-title">Direktori Anggota</h1>
        <p class="page-header-sub">Kelola profil, jabatan, dan perizinan anggota</p>
    </div>
    <a href="{{ route('anggota.create') }}" data-turbo-frame="modal" class="btn-primary gap-2 btn-mobile-full">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/>
        </svg>
        Tambah Anggota
    </a>
</div>

{{-- Filters --}}
<div class="card p-5 mb-5">
    <form method="GET" action="{{ route('anggota.index') }}"
          x-data="{ open: {{ request()->anyFilled(['jabatan', 'role_id', 'is_active']) ? 'true' : 'false' }} }">
        <div class="flex items-center gap-3">
            <div class="relative flex-1">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                       placeholder="Cari nama, email, jabatan..."
                       class="form-input pl-10">
            </div>
            <button type="button" @click="open = !open" class="btn-secondary gap-2 shrink-0">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h18M7 9h10m-5 5h5"/>
                </svg>
                Filter
            </button>
            <button type="submit" class="btn-primary shrink-0">Cari</button>
        </div>
        <div x-show="open" x-transition class="grid grid-cols-1 sm:grid-cols-3 gap-3 mt-4">
            <select name="jabatan" class="form-select">
                <option value="">Semua Jabatan</option>
                @foreach(\App\Models\User::distinct()->pluck('jabatan')->filter() as $jabatan)
                    <option value="{{ $jabatan }}" {{ request('jabatan') == $jabatan ? 'selected' : '' }}>{{ $jabatan }}</option>
                @endforeach
            </select>
            <select name="role_id" class="form-select">
                <option value="">Semua Role</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" {{ request('role_id') == $role->id ? 'selected' : '' }}>{{ $role->nama_role }}</option>
                @endforeach
            </select>
            <select name="is_active" class="form-select">
                <option value="">Semua Status</option>
                <option value="1" {{ request('is_active') === '1' ? 'selected' : '' }}>Aktif</option>
                <option value="0" {{ request('is_active') === '0' ? 'selected' : '' }}>Tidak Aktif</option>
            </select>
        </div>
        <div class="mt-4 flex items-center gap-2">
            <div class="h-px bg-slate-100 dark:bg-white/5 flex-1"></div>
            <p class="text-[10px] font-black uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500">
                Total: <span class="text-blue-500 dark:text-blue-400">{{ $anggota->total() }}</span> Anggota Ditemukan
            </p>
            <div class="h-px bg-slate-100 dark:bg-white/5 flex-1"></div>
        </div>
    </form>
</div>

{{-- Scrollable Grid Container --}}
<div class="main-scroll custom-scrollbar max-h-[calc(100vh-320px)] overflow-y-auto pr-2 -mr-2">
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 pb-4">
        @forelse($anggota as $member)
            {{-- Member Card... (keep content) --}}
            <div class="group relative bg-white dark:bg-[#11131D] rounded-[32px] border border-slate-200 dark:border-white/5 shadow-xl transition-all duration-500 hover:-translate-y-2 hover:shadow-2xl overflow-hidden flex flex-col">
                {{-- Header Accent --}}
                <div class="h-20 bg-gradient-to-br from-blue-500/10 via-indigo-500/10 to-transparent dark:from-blue-500/5 dark:via-indigo-500/5 relative overflow-hidden">
                    <div class="absolute inset-0 opacity-20" style="background-image: radial-gradient(circle at 2px 2px, rgba(59,130,246,0.2) 1px, transparent 0); background-size: 16px 16px;"></div>
                    {{-- Status indicator --}}
                    <div class="absolute top-4 right-4 z-20">
                        @if($member->is_active ?? true)
                            <div class="flex items-center gap-1.5 px-2 py-1 bg-green-500/10 backdrop-blur-md rounded-full border border-green-500/20">
                                <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                                <span class="text-[8px] font-black text-green-600 dark:text-green-500 uppercase tracking-widest">Active</span>
                            </div>
                        @else
                            <div class="flex items-center gap-1.5 px-2 py-1 bg-slate-500/10 backdrop-blur-md rounded-full border border-slate-500/20">
                                <span class="w-1.5 h-1.5 rounded-full bg-slate-400"></span>
                                <span class="text-[8px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest">Inactive</span>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Content --}}
                <div class="px-6 pb-6 -mt-10 relative z-10 text-center flex flex-col items-center">
                    <div class="w-20 h-20 rounded-full p-1 bg-white dark:bg-[#11131D] shadow-xl border border-slate-100 dark:border-white/5 mb-4 transition-transform duration-500 group-hover:scale-110">
                        <div class="w-full h-full rounded-full flex items-center justify-center text-xl font-black text-white overflow-hidden bg-gradient-to-br from-blue-500 to-indigo-600 shadow-inner">
                            @if($member->foto_profil)
                                <img src="{{ asset('storage/'.$member->foto_profil) }}" class="w-full h-full object-cover">
                            @else
                                {{ strtoupper(substr($member->name, 0, 1)) }}
                            @endif
                        </div>
                    </div>
                    <div class="mb-5">
                        <h3 class="text-[17px] font-black text-slate-900 dark:text-white tracking-tight line-clamp-1">
                            {{ $member->name }}
                        </h3>
                        <p class="text-[11px] font-bold text-slate-400 dark:text-slate-500 truncate w-full mt-0.5 tracking-wide">
                            {{ $member->email }}
                        </p>
                    </div>
                    <div class="px-4 py-1.5 rounded-2xl bg-slate-50 dark:bg-white/[0.03] border border-slate-100 dark:border-white/5 shadow-sm">
                        <span class="text-[10px] font-black text-slate-600 dark:text-slate-400 uppercase tracking-[0.1em]">
                            {{ $member->jabatan ?? 'Anggota Biasa' }}
                        </span>
                    </div>
                </div>

                {{-- Actions Footer --}}
                <div class="mt-auto px-6 py-5 bg-slate-50/50 dark:bg-white/[0.02] border-t border-slate-100 dark:border-white/5 flex items-center justify-between">
                    <div class="flex-1"></div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('anggota.show', $member->id) }}" 
                           class="w-8 h-8 flex items-center justify-center rounded-xl bg-white dark:bg-white/5 text-slate-400 hover:text-blue-500 transition-all border border-slate-200 dark:border-white/10 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </a>
                        @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
                        <a href="{{ route('anggota.edit', $member->id) }}" data-turbo-frame="modal" 
                           class="w-8 h-8 flex items-center justify-center rounded-xl bg-white dark:bg-white/5 text-slate-400 hover:text-amber-500 transition-all border border-slate-200 dark:border-white/10 shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                        </a>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full">
                <div class="card">
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <svg class="w-7 h-7 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                        </div>
                        <p class="empty-state-title">Tidak ada anggota</p>
                        <p class="empty-state-sub">Pencarian tidak menemukan hasil atau belum ada data anggota.</p>
                    </div>
                </div>
            </div>
        @endforelse
    </div>
</div>

@if($anggota instanceof \Illuminate\Pagination\LengthAwarePaginator && $anggota->hasPages())
    <div class="mt-6">{{ $anggota->withQueryString()->links() }}</div>
@endif

</div>{{-- end flex flex-col gap-6 --}}
</x-admin-layout>
