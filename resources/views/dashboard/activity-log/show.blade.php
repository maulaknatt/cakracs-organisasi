<x-admin-layout title="Detail Aktivitas">
{{-- Page Header --}}
<div class="mb-4 flex items-center justify-between">
    <div>
        <a href="{{ route('activity-log.index') }}" class="inline-flex items-center gap-2 text-xs text-slate-600 dark:text-slate-400 hover:text-slate-900 dark:hover:text-slate-300 mb-3">
            <svg class="h-3 w-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Kembali ke Riwayat
        </a>
        <h1 class="text-lg font-semibold text-slate-900 dark:text-white">Detail Aktivitas</h1>
        <p class="text-xs text-slate-600 dark:text-slate-400">Informasi lengkap aktivitas pengguna</p>
    </div>
    
    <form action="{{ route('activity-log.destroy', $activityLog->id) }}" method="POST" class="inline">
        @csrf
        @method('DELETE')
        <button type="button" 
                onclick="window.showModalConfirm(this.closest('form'), 'Hapus Log Aktivitas', 'Apakah Anda yakin ingin menghapus catatan log ini secara permanen?')" 
                class="inline-flex items-center gap-2 px-4 py-2 bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 rounded-lg hover:bg-red-100 dark:hover:bg-red-500/20 transition-colors text-sm font-medium shadow-sm border border-red-200 dark:border-red-500/30">
            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
            </svg>
            Hapus Log
        </button>
    </form>
</div>

{{-- Activity Detail Card --}}
<div class="rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 p-6 shadow-sm space-y-6">
    {{-- Basic Info --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-2">User</label>
            <div class="text-sm text-slate-900 dark:text-white font-medium">{{ $activityLog->user_name ?? 'System' }}</div>
            <div class="text-xs text-slate-500 dark:text-slate-500 mt-1">{{ $activityLog->role ?? '-' }}</div>
        </div>
        
        <div>
            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-2">Waktu</label>
            <div class="text-sm text-slate-900 dark:text-white">{{ $activityLog->created_at->format('d M Y, H:i:s') }}</div>
            <div class="text-xs text-slate-500 dark:text-slate-500 mt-1">{{ $activityLog->created_at->diffForHumans() }}</div>
        </div>
        
        <div>
            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-2">Aksi</label>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                @if($activityLog->action === 'create') bg-emerald-100 dark:bg-emerald-500/20 text-emerald-700 dark:text-emerald-400
                @elseif($activityLog->action === 'update') bg-blue-100 dark:bg-blue-500/20 text-blue-700 dark:text-blue-400
                @elseif($activityLog->action === 'delete') bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400
                @elseif($activityLog->action === 'login') bg-green-100 dark:bg-green-500/20 text-green-700 dark:text-green-400
                @else bg-slate-100 dark:bg-slate-700 text-slate-700 dark:text-slate-300
                @endif">
                {{ $activityLog->action_label }}
            </span>
        </div>
        
        <div>
            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-2">Modul</label>
            <div class="text-sm text-slate-900 dark:text-white">{{ ucfirst($activityLog->module) }}</div>
        </div>
        
        @if($activityLog->target_id)
        <div>
            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-2">ID Target</label>
            <div class="text-sm text-slate-900 dark:text-white">{{ $activityLog->target_id }}</div>
        </div>
        @endif
        
        @if($activityLog->ip_address)
        <div>
            <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-2">IP Address</label>
            <div class="text-sm text-slate-900 dark:text-white font-mono">{{ $activityLog->ip_address }}</div>
        </div>
        @endif
    </div>
    
    {{-- Description --}}
    <div>
        <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-2">Deskripsi</label>
        <div class="text-sm text-slate-900 dark:text-white bg-slate-50 dark:bg-slate-900 rounded-lg p-3">
            {{ $activityLog->description }}
        </div>
    </div>
    
    {{-- Old Value --}}
    @if($activityLog->old_value)
    <div>
        <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-2">Nilai Sebelumnya</label>
        <div class="bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 rounded-lg p-4">
            <pre class="text-xs text-slate-900 dark:text-slate-300 whitespace-pre-wrap font-mono overflow-x-auto">{{ json_encode($activityLog->old_value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
        </div>
    </div>
    @endif
    
    {{-- New Value --}}
    @if($activityLog->new_value)
    <div>
        <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-2">Nilai Setelahnya</label>
        <div class="bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 rounded-lg p-4">
            <pre class="text-xs text-slate-900 dark:text-slate-300 whitespace-pre-wrap font-mono overflow-x-auto">{{ json_encode($activityLog->new_value, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
        </div>
    </div>
    @endif
    
    @if($activityLog->user_agent)
    <div>
        <label class="block text-xs font-medium text-slate-700 dark:text-slate-300 mb-2">User Agent</label>
        <div class="text-xs text-slate-600 dark:text-slate-400 bg-slate-50 dark:bg-slate-900 rounded-lg p-3 font-mono break-all">
            {{ $activityLog->user_agent }}
        </div>
    </div>
    @endif
</div>
</x-admin-layout>

