{{-- Sidebar navigation --}}
@php
    $pengaturan = \App\Models\Pengaturan::first();
    $appName    = $pengaturan->nama_organisasi ?? 'Organisasi';
    $appLogo    = $pengaturan->logo ?? null;
    $user       = Auth::user();
    if ($user && !$user->relationLoaded('role')) $user->load('role');
    $current    = request()->route()?->getName();
@endphp

{{-- ── Workspace header ──────────────────────────────────── --}}
<div class="flex-shrink-0 px-4 pt-5 pb-3">
    <div class="flex items-center gap-3 px-2 py-2 rounded-xl cursor-default select-none hover:bg-black/4 dark:hover:bg-white/5 transition-colors">
        @if($appLogo)
            <img src="{{ asset('storage/'.$appLogo) }}"
                 class="h-8 w-8 rounded-xl object-contain flex-shrink-0 ring-1 ring-black/10 dark:ring-white/10" alt="">
        @else
            <div class="h-8 w-8 rounded-xl flex items-center justify-center text-white text-[13px] font-bold flex-shrink-0"
                 style="background: linear-gradient(135deg, #1e40af, #3b82f6); box-shadow: 0 2px 8px rgba(59,130,246,0.30);">
                {{ strtoupper(substr($appName, 0, 1)) }}
            </div>
        @endif
        <div class="flex-1 min-w-0">
            <div class="text-[15px] font-bold leading-tight truncate text-slate-900 dark:text-slate-100"
                 style="letter-spacing: -.01em;">{{ $appName }}</div>
            <p class="text-[12px] text-slate-400 dark:text-slate-500 font-medium">Workspace</p>
        </div>
        {{-- Mobile close button --}}
        <button @click="sidebarOpen = false"
                class="lg:hidden flex-shrink-0 w-7 h-7 flex items-center justify-center rounded-lg text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 hover:bg-black/5 dark:hover:bg-white/8 transition-colors">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>
</div>

{{-- ── Navigation ──────────────────────────────────────────── --}}
<nav class="flex-1 overflow-y-auto px-3 py-1 space-y-0.5 custom-scrollbar" aria-label="Navigasi Utama">

    {{-- Overview --}}
    <a href="{{ route('dashboard') }}"
       class="nav-item {{ $current === 'dashboard' ? 'active' : '' }}"
       @click="if(isMobile) sidebarOpen = false">
        <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
            <rect x="3" y="3" width="7" height="7" rx="1.5"/>
            <rect x="14" y="3" width="7" height="7" rx="1.5"/>
            <rect x="14" y="14" width="7" height="7" rx="1.5"/>
            <rect x="3" y="14" width="7" height="7" rx="1.5"/>
        </svg>
        Overview
    </a>

    @if($user->canAccess('pengumuman'))
    <a href="{{ route('pengumuman.index') }}"
       class="nav-item {{ str_starts_with($current ?? '', 'pengumuman') ? 'active' : '' }}"
       @click="if(isMobile) sidebarOpen = false">
        <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
            <path d="M6 8a6 6 0 0 1 12 0c0 7 3 9 3 9H3s3-2 3-9" />
            <path d="M10.3 21a1.94 1.94 0 0 0 3.4 0" />
        </svg>
        Pengumuman
    </a>
    @endif

    {{-- Produktivitas section --}}
    <p class="nav-section">Produktivitas</p>

    <a href="{{ route('kegiatan.index') }}"
       class="nav-item {{ str_starts_with($current ?? '', 'kegiatan') ? 'active' : '' }}"
       @click="if(isMobile) sidebarOpen = false">
        <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
            <rect x="2" y="7" width="20" height="14" rx="2" ry="2"/>
            <path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/>
        </svg>
        Program Kerja
    </a>

    <a href="{{ route('attendance.index') }}"
       class="nav-item {{ str_starts_with($current ?? '', 'attendance') ? 'active' : '' }}"
       @click="if(isMobile) sidebarOpen = false">
        <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10" />
            <path d="M12 6v6l4 2" />
        </svg>
        Presensi
    </a>

    @if($user->canAccess('tugas'))
    <a href="{{ route('tugas.index') }}"
       class="nav-item {{ str_starts_with($current ?? '', 'tugas') ? 'active' : '' }}"
       @click="if(isMobile) sidebarOpen = false">
        <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
            <rect x="8" y="2" width="8" height="4" rx="1" ry="1"/>
            <path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/>
            <path d="m9 14 2 2 4-4"/>
        </svg>
        Task Manager
    </a>
    @endif

    @if($user->canAccess('keuangan'))
    <a href="{{ route('keuangan.index') }}"
       class="nav-item {{ str_starts_with($current ?? '', 'keuangan') ? 'active' : '' }}"
       @click="if(isMobile) sidebarOpen = false">
        <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="12" cy="12" r="10" />
            <path d="M16 8h-6a2 2 0 1 0 0 4h4a2 2 0 1 1 0 4H8" />
            <path d="M12 18V6" />
        </svg>
        Keuangan
    </a>
    @endif

    {{-- Kolaborasi section --}}
    <p class="nav-section">Kolaborasi</p>

    <a href="{{ route('chat.index') }}"
       class="nav-item {{ str_starts_with($current ?? '', 'chat') ? 'active' : '' }}"
       @click="if(isMobile) sidebarOpen = false">
        <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
            <path d="M21 15v4a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>
        </svg>
        Chat
    </a>

    <a href="{{ route('voice.index') }}"
       class="nav-item {{ str_starts_with($current ?? '', 'voice') ? 'active' : '' }}"
       @click="if(isMobile) sidebarOpen = false">
        <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
            <path d="M12 2a3 3 0 0 0-3 3v7a3 3 0 0 0 6 0V5a3 3 0 0 0-3-3Z"/>
            <path d="M19 10v2a7 7 0 0 1-14 0v-2"/>
            <line x1="12" y1="19" x2="12" y2="22"/>
        </svg>
        Voice
    </a>



    {{-- Data section --}}
    <p class="nav-section">Data</p>

    @if($user->canAccess('anggota') || $user->hasPermission('manage_users'))
    <a href="{{ route('anggota.index') }}"
       class="nav-item {{ str_starts_with($current ?? '', 'anggota') ? 'active' : '' }}"
       @click="if(isMobile) sidebarOpen = false">
        <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
            <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
            <circle cx="9" cy="7" r="4" />
            <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
            <path d="M16 3.13a4 4 0 0 1 0 7.75" />
        </svg>
        Anggota
    </a>
    @endif

    @if($user->canAccess('dokumentasi'))
    <a href="{{ route('dokumentasi.index') }}"
       class="nav-item {{ str_starts_with($current ?? '', 'dokumentasi') ? 'active' : '' }}"
       @click="if(isMobile) sidebarOpen = false">
        <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/>
            <polyline points="14 2 14 8 20 8"/>
            <line x1="16" y1="13" x2="8" y2="13"/>
            <line x1="16" y1="17" x2="8" y2="17"/>
            <polyline points="10 9 9 9 8 9"/>
        </svg>
        Dokumentasi
    </a>
    @endif

    @if($user->canAccess('arsip'))
    <a href="{{ route('arsip.index') }}"
       class="nav-item {{ str_starts_with($current ?? '', 'arsip') ? 'active' : '' }}"
       @click="if(isMobile) sidebarOpen = false">
        <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
            <rect x="2" y="4" width="20" height="5" rx="1" ry="1"/>
            <path d="M4 9v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9"/>
            <path d="M10 13h4"/>
        </svg>
        Arsip
    </a>
    @endif

    @if($user->isSuperAdmin() || $user->isAdmin())
    <p class="nav-section">Sistem</p>
    <a href="{{ route('activity-log.index') }}"
       class="nav-item {{ str_starts_with($current ?? '', 'activity-log') ? 'active' : '' }}"
       @click="if(isMobile) sidebarOpen = false">
        <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
            <polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/>
        </svg>
        Riwayat Aktivitas
    </a>
    @endif

</nav>

{{-- ── Voice indicator (minimal) ───────────────────────────── --}}
<div x-show="$store.voice.connectedChannel && !$store.voice.isVoicePage"
     x-cloak
     x-transition:enter="transition ease-out duration-200"
     x-transition:enter-start="opacity-0 translate-y-1"
     x-transition:enter-end="opacity-100 translate-y-0"
     class="flex-shrink-0 mx-3 mb-2">
    <div class="flex items-center justify-between gap-2 px-3 py-2.5 rounded-xl"
         style="background: linear-gradient(135deg, rgba(37,99,235,.85), rgba(14,165,233,.85)); border: 1px solid rgba(59,130,246,.3);">
        <div class="flex items-center gap-2 min-w-0">
            <span class="w-2 h-2 rounded-full bg-green-400 flex-shrink-0 animate-pulse shadow-[0_0_6px_rgba(74,222,128,.9)]"></span>
            <div class="min-w-0">
                <p class="text-[13px] font-bold text-white truncate leading-none" x-text="$store.voice.connectedChannel?.name"></p>
                <p class="text-[11px] text-white/60 font-medium mt-0.5">Live · Voice</p>
            </div>
        </div>
        <div class="flex items-center gap-1 flex-shrink-0">
            <button @click="$store.voice.toggleMute()" class="w-6 h-6 rounded-lg bg-white/10 hover:bg-white/20 transition-colors flex items-center justify-center">
                <svg x-show="!$store.voice.muted" class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11a7 7 0 01-7 7m0 0a7 7 0 01-7-7m7 7v4m0 0H8m4 0h4m-4-8a3 3 0 01-3-3V5a3 3 0 116 0v6a3 3 0 01-3 3z"/>
                </svg>
                <svg x-show="$store.voice.muted" class="w-3 h-3 text-white/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15zM17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2"/>
                </svg>
            </button>
            <button @click="$store.voice.disconnect()" class="w-6 h-6 rounded-lg bg-red-500/80 hover:bg-red-500 transition-colors flex items-center justify-center">
                <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>
</div>

{{-- ── Bottom: Settings + Logout + User chip ───────────────── --}}
<div class="flex-shrink-0 px-3 pb-4 pt-1">
    <div class="border-t border-slate-100 dark:border-white/5 pt-2 space-y-0.5">
        <a href="{{ route('pengaturan.index') }}"
           class="nav-item {{ str_starts_with($current ?? '', 'pengaturan') ? 'active' : '' }}"
           @click="if(isMobile) sidebarOpen = false">
            <svg class="nav-icon" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                <path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/>
                <circle cx="12" cy="12" r="3"/>
            </svg>
            Pengaturan
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="button"
                    onclick="window.showModalConfirm(this.closest('form'), 'Keluar?', 'Sesi kamu akan diakhiri.', 'Keluar', 'Batal')"
                    class="nav-item w-full text-left text-red-500 hover:text-red-600 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-500/10">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/>
                    <polyline points="16 17 21 12 16 7"/>
                    <line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
                Keluar
            </button>
        </form>
    </div>

    {{-- User chip --}}
    <div class="flex items-center gap-2.5 px-3 py-3 mt-2 rounded-xl bg-slate-50 dark:bg-white/4 border border-slate-100 dark:border-white/5">
        @if(Auth::user()->foto_profil)
            <img src="{{ asset('storage/'.Auth::user()->foto_profil) }}" alt="Profil" class="w-9 h-9 object-cover rounded-full flex-shrink-0 ring-2 ring-blue-500/20 dark:ring-blue-400/20">
        @else
            <div class="w-9 h-9 rounded-full flex items-center justify-center text-white text-[13px] font-bold flex-shrink-0"
                 style="background: linear-gradient(135deg, #1e40af, #3b82f6);">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
        @endif
        <div class="flex-1 min-w-0">
            <p class="text-[13.5px] font-semibold truncate leading-none text-slate-900 dark:text-slate-100">
                {{ Auth::user()->name }}
            </p>
            <p class="text-[12px] mt-0.5 leading-none text-slate-400 dark:text-slate-500">
            {{ Auth::user()->jabatan ?? 'Anggota' }}
            </p>
        </div>
        <span class="status-dot status-dot-online flex-shrink-0"></span>
    </div>
</div>
