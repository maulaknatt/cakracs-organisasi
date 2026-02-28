<x-admin-layout title="Task Manager">
<div class="flex flex-col gap-6">

{{-- Page Header --}}
<div class="page-header">
    <div>
        <h1 class="page-header-title">Task Manager</h1>
        <p class="page-header-sub">Kelola dan pantau progress tugas organisasi</p>
    </div>
    @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin() || Auth::user()->isPengurus())
    <a href="{{ route('tugas.create') }}" data-turbo-frame="modal" class="btn-primary gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4.5v15m7.5-7.5h-15"/>
        </svg>
        Tambah Task
    </a>
    @endif
</div>

{{-- Filter Bar --}}
<div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/5 shadow-sm overflow-hidden" x-data="{ open: {{ request()->anyFilled(['status','kegiatan_id','deadline']) ? 'true' : 'false' }} }">
    <form method="GET" action="{{ route('tugas.index') }}" class="p-3">
        <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
            <div class="relative flex-1 group">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400 group-focus-within:text-blue-500 transition-colors pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari tugas..." 
                    class="w-full pl-10 pr-4 h-10 bg-slate-50 dark:bg-slate-800/50 border-0 rounded-xl text-sm focus:ring-2 focus:ring-blue-500/20 transition-all outline-none text-slate-700 dark:text-slate-300">
            </div>
            <div class="flex items-center gap-2">
                <button type="button" @click="open = !open" 
                    :class="open ? 'bg-blue-50 text-blue-600 dark:bg-blue-500/10 dark:text-blue-400' : 'bg-slate-50 text-slate-600 dark:bg-slate-800 dark:text-slate-400'"
                    class="flex-1 sm:flex-none flex items-center justify-center gap-2 h-10 px-4 rounded-xl text-[13px] font-bold transition-all border border-transparent">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4h18M7 9h10m-5 5h5"/></svg>
                    <span>Filter</span>
                </button>
                <button type="submit" class="flex-1 sm:flex-none bg-blue-600 hover:bg-blue-700 text-white h-10 px-6 rounded-xl text-[13px] font-bold transition shadow-lg shadow-blue-500/20">Cari</button>
            </div>
        </div>
        <div x-show="open" x-cloak x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0 -translate-y-2" x-transition:enter-end="opacity-100 translate-y-0" class="mt-3 pt-3 border-t border-slate-100 dark:border-white/5 grid grid-cols-1 sm:grid-cols-3 gap-2">
            <select name="status" class="bg-slate-50 dark:bg-slate-800/50 border-0 rounded-xl text-[13px] h-10 px-3 outline-none focus:ring-2 focus:ring-blue-500/20 dark:text-slate-300">
                <option value="">Semua Status</option>
                <option value="open" {{ request('status') == 'open' ? 'selected' : '' }}>Belum Selesai</option>
                <option value="done" {{ request('status') == 'done' ? 'selected' : '' }}>Sudah Selesai</option>
            </select>
            <select name="kegiatan_id" class="bg-slate-50 dark:bg-slate-800/50 border-0 rounded-xl text-[13px] h-10 px-3 outline-none focus:ring-2 focus:ring-blue-500/20 dark:text-slate-300">
                <option value="">Semua Program</option>
                @foreach(\App\Models\Kegiatan::orderBy('judul')->get() as $k)
                    <option value="{{ $k->id }}" {{ request('kegiatan_id') == $k->id ? 'selected' : '' }}>{{ $k->judul }}</option>
                @endforeach
            </select>
            <select name="deadline" class="bg-slate-50 dark:bg-slate-800/50 border-0 rounded-xl text-[13px] h-10 px-3 outline-none focus:ring-2 focus:ring-blue-500/20 dark:text-slate-300">
                <option value="">Semua Deadline</option>
                <option value="overdue" {{ request('deadline') == 'overdue' ? 'selected' : '' }}>Terlambat</option>
                <option value="due_soon" {{ request('deadline') == 'due_soon' ? 'selected' : '' }}>Segera Berakhir</option>
            </select>
        </div>
    </form>
</div>

{{-- Task Table --}}
<div class="bg-white dark:bg-slate-900 rounded-2xl border border-slate-200 dark:border-white/5 shadow-sm overflow-hidden flex flex-col">
    <div class="max-h-[620px] overflow-y-auto overflow-x-auto custom-scrollbar flex-1 relative">
        <table class="w-full text-left text-sm border-separate border-spacing-0">
            <thead class="bg-slate-50 dark:bg-slate-800/50 border-b border-slate-100 dark:border-white/5 text-slate-500 dark:text-slate-400 sticky top-0 z-10">
                <tr>
                    <th class="px-5 py-4 font-bold text-[12px] uppercase tracking-wider">Detail Tugas</th>
                    <th class="px-5 py-4 font-bold text-[12px] uppercase tracking-wider">Status</th>
                    <th class="px-5 py-4 font-bold text-[12px] uppercase tracking-wider">Deadline</th>
                    <th class="px-5 py-4 font-bold text-[12px] uppercase tracking-wider">Program Kerja</th>
                    @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin() || Auth::user()->isPengurus())
                    <th class="px-5 py-4 font-bold text-[12px] uppercase tracking-wider text-center">Aksi</th>
                    @endif
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                @forelse($tugas as $task)
                    @php
                        $isDone   = $task->status === 'done';
                        $isLate   = $task->deadline && \Carbon\Carbon::parse($task->deadline)->isPast() && !$isDone;
                        $isDueSoon = $task->deadline && \Carbon\Carbon::parse($task->deadline)->isFuture() && \Carbon\Carbon::parse($task->deadline)->diffInDays() <= 3 && !$isDone;
                    @endphp
                    <tr class="hover:bg-blue-50/30 dark:hover:bg-blue-500/[0.02] transition-all cursor-pointer group"
                        onclick="if(!event.target.closest('a') && !event.target.closest('button')) window.location='{{ route('tugas.show', $task->id) }}'">
                        
                        <td class="px-5 py-4">
                            <div class="flex flex-col gap-0.5 max-w-[300px]">
                                <span class="font-bold text-slate-800 dark:text-slate-200 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors line-clamp-1">{{ $task->judul }}</span>
                                @if($task->deskripsi)
                                <span class="text-[12px] text-slate-500 dark:text-slate-400 line-clamp-1">{{ Str::limit($task->deskripsi, 60) }}</span>
                                @endif
                            </div>
                        </td>
                        
                        <td class="px-5 py-4">
                            @if($isDone)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-100 dark:border-emerald-500/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                    Selesai
                                </span>
                            @elseif($isLate)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 border border-red-100 dark:border-red-500/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span>
                                    Terlambat
                                </span>
                            @elseif($isDueSoon)
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-100 dark:border-amber-500/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                    Segera
                                </span>
                            @else
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[11px] font-black uppercase tracking-wider bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 border border-blue-100 dark:border-blue-500/20">
                                    <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                                    Open
                                </span>
                            @endif
                        </td>
                        
                        <td class="px-5 py-4 whitespace-nowrap">
                            @if($task->deadline)
                            <div class="flex items-center gap-2 {{ $isLate ? 'text-red-500 font-bold' : 'text-slate-500 dark:text-slate-400' }}">
                                <svg class="w-3.5 h-3.5 opacity-60" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" stroke-width="2"/></svg>
                                <span class="text-[13px]">{{ \Carbon\Carbon::parse($task->deadline)->translatedFormat('d M Y') }}</span>
                            </div>
                            @else
                            <span class="text-[13px] text-slate-400">-</span>
                            @endif
                        </td>
                        
                        <td class="px-5 py-4">
                            @if($task->kegiatan)
                                <span class="inline-flex px-2 px-1 text-[12px] font-medium text-slate-600 dark:text-slate-400 bg-slate-100 dark:bg-slate-800 rounded-md border border-slate-200 dark:border-white/5 truncate max-w-[150px]" title="{{ $task->kegiatan->judul }}">
                                    {{ Str::limit($task->kegiatan->judul, 20) }}
                                </span>
                            @else
                                <span class="text-xs text-slate-400">-</span>
                            @endif
                        </td>
                        
                        @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin() || Auth::user()->isPengurus())
                        <td class="px-5 py-4 text-center">
                            <div class="flex items-center justify-center gap-1">
                                <a href="{{ route('tugas.edit', $task->id) }}" data-turbo-frame="modal" class="p-2 text-slate-400 hover:text-blue-500 hover:bg-blue-50 dark:hover:bg-blue-500/10 rounded-lg transition-all" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                </a>
                                <form method="POST" action="{{ route('tugas.destroy', $task->id) }}" class="inline-block" onsubmit="event.preventDefault()">
                                    @csrf @method('DELETE')
                                    <button type="button" onclick="window.showModalConfirm(this.closest('form'), 'Hapus Tugas', 'Yakin hapus tugas ini?')" class="p-2 text-slate-400 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/10 rounded-lg transition-all" title="Hapus">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                        @endif
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-5 py-20 text-center">
                            <div class="flex flex-col items-center">
                                <div class="w-16 h-16 rounded-2xl bg-slate-50 dark:bg-slate-800 flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2m-6 9l2 2 4-4" stroke-width="1.5"/></svg>
                                </div>
                                <h3 class="font-bold text-slate-800 dark:text-slate-200">Belum Ada Tugas</h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400">Tidak ada tugas yang ditemukan</p>
                            </div>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($tugas instanceof \Illuminate\Pagination\LengthAwarePaginator && $tugas->hasPages())
        <div class="p-5 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-100 dark:border-white/5">
            {{ $tugas->withQueryString()->links() }}
        </div>
    @endif
</div>

</div>{{-- end flex flex-col gap-6 --}}
</x-admin-layout>
