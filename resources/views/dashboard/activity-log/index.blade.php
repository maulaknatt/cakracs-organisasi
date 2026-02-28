<x-admin-layout title="Log Aktivitas">
<div class="flex flex-col gap-6">

    {{-- Header --}}
    <div class="page-header">
        <div>
            <h1 class="page-header-title">Riwayat Aktivitas</h1>
            <p class="page-header-sub">Audit log seluruh aktivitas dan perubahan data sistem</p>
        </div>
        @if(request()->anyFilled(['search','user_id','module','action','date_from','date_to']))
            <a href="{{ route('activity-log.index') }}" class="btn-secondary gap-2 text-red-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Reset Filter
            </a>
        @endif
    </div>

    {{-- Filter Card --}}
    <div class="card mb-6">
        <form method="GET" action="{{ route('activity-log.index') }}"
              x-data="{ expanded: {{ request()->anyFilled(['user_id','module','action','date_from','date_to']) ? 'true' : 'false' }} }">
            <div class="p-4 flex flex-wrap items-center gap-3">
                {{-- Search --}}
                <div class="relative flex-1 min-w-[200px]">
                    <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                           placeholder="Cari aktivitas..." class="form-input pl-10">
                </div>
                {{-- Per page --}}
                <select name="per_page" onchange="this.form.submit()" class="form-select w-36">
                    <option value="15" {{ request('per_page', 15) == 15 ? 'selected' : '' }}>15 per halaman</option>
                    <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25 per halaman</option>
                    <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50 per halaman</option>
                </select>
                <button type="button" @click="expanded = !expanded" class="btn-secondary gap-2">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"/>
                    </svg>
                    Filter
                </button>
                <button type="submit" class="btn-primary">Terapkan</button>
            </div>

            {{-- Expanded Filters --}}
            <div x-show="expanded" x-collapse x-cloak
                 class="px-4 pb-4 pt-0 border-t border-slate-100 dark:border-slate-700/50 mt-0 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 pt-4">
                <div>
                    <label class="form-label mb-1">Pengguna</label>
                    <select name="user_id" class="form-select">
                        <option value="">Semua Pengguna</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>{{ $user->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label mb-1">Modul</label>
                    <select name="module" class="form-select">
                        <option value="">Semua Modul</option>
                        @foreach($modules as $module)
                            <option value="{{ $module }}" {{ request('module') == $module ? 'selected' : '' }}>{{ ucfirst($module) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label mb-1">Aksi</label>
                    <select name="action" class="form-select">
                        <option value="">Semua Aksi</option>
                        @php
                            $actionLabels = [
                                'create' => 'Tambah',
                                'update' => 'Edit',
                                'delete' => 'Hapus',
                                'login' => 'Login',
                                'logout' => 'Logout',
                                'upload' => 'Upload'
                            ];
                        @endphp
                        @foreach($actions as $action)
                            <option value="{{ $action }}" {{ request('action') == $action ? 'selected' : '' }}>{{ $actionLabels[$action] ?? ucfirst($action) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="form-label mb-1">Tanggal Dari</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}" class="form-input">
                </div>
            </div>
        </form>
    </div>

    {{-- Summary Badges --}}
    @if($logs->total() > 0)
        <div class="flex items-center gap-3 mb-4 flex-wrap">
            <span class="text-[13.5px] text-slate-500 dark:text-slate-400 font-medium">
                Menampilkan <strong class="text-slate-900 dark:text-white">{{ $logs->firstItem() }}–{{ $logs->lastItem() }}</strong> dari <strong class="text-slate-900 dark:text-white">{{ $logs->total() }}</strong> aktivitas
            </span>
        </div>
    @endif

    {{-- Table --}}
    <div class="bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-700/60 rounded-2xl overflow-hidden shadow-sm">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-slate-100 dark:border-slate-700/50 bg-slate-50/70 dark:bg-slate-800/50">
                        <th class="px-5 py-3.5 text-[12px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 whitespace-nowrap">Waktu</th>
                        <th class="px-5 py-3.5 text-[12px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500">Pengguna</th>
                        <th class="px-5 py-3.5 text-[12px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500">Aksi</th>
                        <th class="px-5 py-3.5 text-[12px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500">Modul</th>
                        <th class="px-5 py-3.5 text-[12px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500">Deskripsi</th>
                        <th class="px-5 py-3.5 text-[12px] font-black uppercase tracking-widest text-slate-400 dark:text-slate-500 text-right">Detail</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100 dark:divide-slate-700/50">
                    @forelse($logs as $log)
                        @php
                            $actionColors = [
                                'create' => 'bg-emerald-50 text-emerald-700 border-emerald-100 dark:bg-emerald-500/10 dark:text-emerald-400 dark:border-emerald-500/20',
                                'update' => 'bg-blue-50 text-blue-700 border-blue-100 dark:bg-blue-500/10 dark:text-blue-400 dark:border-blue-500/20',
                                'delete' => 'bg-red-50 text-red-700 border-red-100 dark:bg-red-500/10 dark:text-red-400 dark:border-red-500/20',
                                'login'  => 'bg-amber-50 text-amber-700 border-amber-100 dark:bg-amber-500/10 dark:text-amber-400 dark:border-amber-500/20',
                                'logout' => 'bg-slate-100 text-slate-600 border-slate-200 dark:bg-slate-800 dark:text-slate-400 dark:border-slate-700',
                            ];
                            $colorClass = $actionColors[$log->action] ?? $actionColors['logout'];
                        @endphp
                        <tr class="hover:bg-slate-50/80 dark:hover:bg-white/3 transition-colors">
                            <td class="px-5 py-4 whitespace-nowrap">
                                <div class="text-[13px] font-bold text-slate-800 dark:text-slate-200">
                                    {{ $log->created_at->translatedFormat('d M Y') }}
                                </div>
                                <div class="text-[12px] text-slate-400 dark:text-slate-500 font-medium">
                                    {{ $log->created_at->format('H:i:s') }}
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-2.5">
                                    @if($log->user && $log->user->foto_profil)
                                        <img src="{{ asset('storage/'.$log->user->foto_profil) }}" class="w-8 h-8 rounded-full object-cover shrink-0">
                                    @else
                                        <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center text-blue-600 dark:text-blue-400 font-bold text-[13px] shrink-0">
                                            {{ strtoupper(substr($log->user_name ?? 'S', 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="text-[13.5px] font-bold text-slate-800 dark:text-white leading-tight">{{ $log->user_name ?? 'System' }}</p>
                                        <p class="text-[12px] text-slate-400 dark:text-slate-500">{{ $log->user?->jabatan ?? 'Core Engine' }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-[12px] font-black uppercase tracking-wide border {{ $colorClass }}">
                                    {{ $log->action_label ?? $log->action }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <span class="px-2.5 py-1 rounded-lg text-[12.5px] font-bold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700">
                                    {{ ucfirst($log->module ?? '-') }}
                                </span>
                            </td>
                            <td class="px-5 py-4 max-w-xs">
                                <p class="text-[13.5px] text-slate-600 dark:text-slate-300 leading-relaxed line-clamp-2">
                                    {{ $log->description }}
                                </p>
                            </td>
                            <td class="px-5 py-4 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <a href="{{ route('activity-log.show', $log->id) }}"
                                       class="inline-flex items-center gap-1.5 text-[13px] font-semibold text-blue-500 hover:text-blue-600 transition-colors">
                                        Lihat
                                    </a>
                                    <form action="{{ route('activity-log.destroy', $log->id) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" 
                                                onclick="window.showModalConfirm(this.closest('form'), 'Hapus Log Aktivitas', 'Apakah Anda yakin ingin menghapus catatan log ini secara permanen?')" 
                                                class="inline-flex items-center gap-1.5 text-[13px] font-semibold text-red-500 hover:text-red-600 transition-colors">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-5 py-20 text-center">
                                <div class="w-16 h-16 rounded-3xl bg-slate-50 dark:bg-slate-800 flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-slate-300 dark:text-slate-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <p class="text-[15px] font-bold text-slate-900 dark:text-white mb-1">Tidak Ada Aktivitas</p>
                                <p class="text-[13.5px] text-slate-400">Belum ada log aktivitas yang tersimpan</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($logs->hasPages())
            <div class="px-5 py-4 border-t border-slate-100 dark:border-slate-700/50 bg-slate-50/50 dark:bg-white/2">
                {{ $logs->withQueryString()->links() }}
            </div>
        @endif
    </div>

</div>{{-- end flex flex-col gap-6 --}}
</x-admin-layout>
