@props(['title' => 'Dashboard', 'noPadding' => false])
<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} — {{ optional(\App\Models\Pengaturan::first())->nama_organisasi ?? 'Organisasi' }}</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        window.userData = {
            id: "{{ auth()->id() }}",
            name: "{{ auth()->user()->name }}",
            avatar: "{{ auth()->user()->foto_profil ? asset('storage/'.auth()->user()->foto_profil) : '' }}"
        };
    </script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/marked/marked.min.js"></script>

    <script>
        // Theme init — run before paint to prevent flash
        (function() {
            const t = localStorage.getItem('theme') || 'dark';
            if (t === 'dark') document.documentElement.classList.add('dark');
        })();

        window.toggleTheme = function(event) {
            const x = event?.clientX ?? window.innerWidth / 2;
            const y = event?.clientY ?? window.innerHeight / 2;
            document.documentElement.style.setProperty('--toggle-x', x + 'px');
            document.documentElement.style.setProperty('--toggle-y', y + 'px');

            if (!document.startViewTransition) {
                toggleDark();
                return;
            }

            document.documentElement.classList.add('theme-switching');
            const transition = document.startViewTransition(() => { toggleDark(); });
            transition.finished.finally(() => {
                document.documentElement.classList.remove('theme-switching');
            });
        };

        function toggleDark() {
            const d = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', d ? 'dark' : 'light');
            window.dispatchEvent(new CustomEvent('theme-changed', { detail: { dark: d } }));
        }

        window.showModalConfirm = function(form, title = 'Konfirmasi', message = 'Apakah Anda yakin?', confirmText = 'Ya', cancelText = 'Batal') {
            const isDark = document.documentElement.classList.contains('dark');
            Swal.fire({
                title: `<span style="color:${isDark?'#f1f5f9':'#0f172a'};font-weight:700;font-family:'Inter',sans-serif;">${title}</span>`,
                html: `<p style="color:${isDark?'#94a3b8':'#64748b'};font-family:'Inter',sans-serif;">${message}</p>`,
                background: isDark ? 'rgba(15, 23, 42, 0.9)' : 'rgba(255, 255, 255, 0.95)',
                backdrop: isDark ? 'rgba(0,0,0,0.5)' : 'rgba(15,23,42,0.4)',
                showCancelButton: true,
                confirmButtonText: confirmText,
                cancelButtonText: cancelText,
                reverseButtons: false,
                buttonsStyling: false,
                customClass: {
                    container: 'z-[99999]',
                    popup: `rounded-2xl border shadow-2xl backdrop-blur-xl ${isDark?'border-white/10 shadow-black/50':'border-black/5 shadow-slate-200/50'}`,
                    actions: 'gap-3 px-6 pb-4',
                    confirmButton: 'px-5 py-2.5 bg-red-500 hover:bg-red-600 outline-none text-white rounded-xl shadow-sm transition-all font-medium text-sm',
                    cancelButton: `px-5 py-2.5 outline-none rounded-xl transition-all font-medium border text-sm ${isDark?'bg-white/5 text-slate-300 border-white/10 hover:bg-white/10 hover:text-white':'bg-slate-50 text-slate-700 border-slate-200 hover:bg-slate-100 hover:text-slate-900'}`
                },
                didOpen: () => {
                    const bd = document.querySelector('.swal2-container');
                    if (bd) { 
                        bd.style.backdropFilter = 'blur(12px)'; 
                        bd.style.webkitBackdropFilter = 'blur(12px)';
                        bd.style.zIndex = '99999';
                    }
                }
            }).then((r) => { if (r.isConfirmed && form) form.submit(); });
        };

        window.isDarkMode = () => document.documentElement.classList.contains('dark');

        window.userData = {
            id: {{ auth()->id() }},
            name: "{{ auth()->user()->name }}",
            avatar: "{{ auth()->user()->foto_profil ? asset('storage/'.auth()->user()->foto_profil) : '' }}"
        };
    </script>

    @stack('styles')
</head>

<body class="h-full antialiased dashboard-zoom" style="font-family:'Inter',ui-sans-serif,system-ui,sans-serif;">

<div x-data="{
        sidebarOpen: false,
        isMobile: window.innerWidth < 1024
     }"
     x-on:resize.window="isMobile = window.innerWidth < 1024"
     x-on:popstate.window="if (isMobile) sidebarOpen = false"
     class="app-shell relative">

    {{-- ── AMBIENT GLOW EFFECTS (Dark Mode Only) ──────────────── --}}
    <div class="ambient-glow ambient-glow-1"></div>
    <div class="ambient-glow ambient-glow-2"></div>

    {{-- ── MOBILE OVERLAY ─────────────────────────────────────── --}}
    <div x-show="sidebarOpen && isMobile"
         x-cloak
         @click="sidebarOpen = false"
         class="sidebar-overlay"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0">
    </div>

    {{-- ── SIDEBAR ─────────────────────────────────────────────── --}}
    <div x-show="sidebarOpen || !isMobile"
         x-cloak
         x-transition:enter="transition ease-out duration-250"
         x-transition:enter-start="-translate-x-full"
         x-transition:enter-end="translate-x-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="translate-x-0"
         x-transition:leave-end="-translate-x-full"
         @keydown.escape.window="sidebarOpen = false"
         class="sidebar-rail">

        @include('components.sidebar')
    </div>

    {{-- ── MAIN COLUMN ─────────────────────────────────────────── --}}
    <div class="main-column">

        {{-- ── TOPBAR ──────────────────────────────────────────── --}}
        <header class="topbar">
            <div class="flex items-center gap-2 min-w-0 flex-1">
                {{-- Mobile menu toggle --}}
                <button @click="sidebarOpen = !sidebarOpen"
                        id="mobile-menu-btn"
                        class="lg:hidden flex-shrink-0 w-8 h-8 flex items-center justify-center rounded-lg text-slate-500 dark:text-slate-400 hover:bg-black/6 dark:hover:bg-white/8 hover:text-slate-700 dark:hover:text-slate-200 transition-colors">
                    <svg class="w-4.5 h-4.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                {{-- Page title / breadcrumb --}}
                <span class="topbar-title text-sm md:text-base font-bold md:font-semibold truncate">{{ $title }}</span>
            </div>

            <div class="flex items-center gap-1.5 flex-shrink-0">
                {{-- Global Search --}}
                <x-global-search />

                {{-- Notification Bell --}}
                <div x-data="{ 
                    open: false,
                    unreadCount: 0,
                    notifications: [],
                    loading: false,
                    async fetchNotifications() {
                        this.loading = true;
                        try {
                            const res = await fetch('{{ route('notifications.unread') }}');
                            const data = await res.json();
                            this.unreadCount = data.unread_count;
                            this.notifications = data.notifications;
                        } catch (e) { console.error(e); }
                        finally { this.loading = false; }
                    },
                    async markRead(id, url) {
                        try {
                            await fetch(`/dashboard/notifications/${id}/read`, {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            });
                            window.location.href = url;
                        } catch (e) { console.error(e); }
                    },
                    async markAllRead() {
                        try {
                            await fetch('{{ route('notifications.read-all') }}', {
                                method: 'POST',
                                headers: {
                                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                    'Accept': 'application/json'
                                }
                            });
                            this.unreadCount = 0;
                            this.notifications = this.notifications.map(n => ({...n, read_at: new Date()}));
                        } catch (e) { console.error(e); }
                    }
                }" 
                x-init="fetchNotifications(); setInterval(() => fetchNotifications(), 30000)"
                class="relative">
                    <button @click="open = !open; if(open) fetchNotifications()" 
                            class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-white hover:bg-black/5 dark:hover:bg-white/8 transition-colors flex-shrink-0 relative group">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-bell" viewBox="0 0 16 16">
                            <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2M8 1.918l-.797.161A4 4 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4 4 0 0 0-3.203-3.92zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5 5 0 0 1 13 6c0 .88.32 4.2 1.22 6"/>
                        </svg>
                        <template x-if="unreadCount > 0">
                            <span class="absolute -top-1 -right-1 flex h-4 w-4 items-center justify-center">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500 text-[10px] text-white font-bold items-center justify-center border border-white dark:border-slate-800" x-text="unreadCount > 9 ? '9+' : unreadCount"></span>
                            </span>
                        </template>
                    </button>

                    {{-- Dropdown --}}
                    <div x-show="open" 
                         @click.away="open = false"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 scale-95 translate-y-2"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         x-cloak
                         class="absolute right-0 mt-2 w-80 sm:w-96 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-xl shadow-2xl z-50 overflow-hidden transform origin-top-right">
                        
                        <div class="px-4 py-3 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 flex items-center justify-between">
                            <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-wider">Notifikasi</h3>
                            <button @click="markAllRead()" x-show="unreadCount > 0" class="text-[10px] font-bold text-blue-600 dark:text-blue-400 hover:underline">Tandai semua dibaca</button>
                        </div>

                        <div class="max-h-[295px] overflow-y-auto custom-scrollbar">
                            <template x-if="notifications.length === 0">
                                <div class="p-8 text-center">
                                    <svg class="w-8 h-8 text-slate-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                    <p class="text-[11px] text-slate-500 font-bold uppercase tracking-widest">Belum ada notifikasi</p>
                                </div>
                            </template>

                            <template x-for="n in notifications" :key="n.id">
                                <div @click="markRead(n.id, n.data.url)" 
                                     class="px-4 py-3 border-b border-slate-50 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800/50 cursor-pointer transition-colors relative"
                                     :class="n.read_at ? 'opacity-60' : ''">
                                    <template x-if="!n.read_at">
                                        <div class="absolute left-0 top-0 bottom-0 w-0.5 bg-blue-500"></div>
                                    </template>
                                    <div class="flex gap-3">
                                        <div class="shrink-0 h-9 w-9 rounded-lg flex items-center justify-center shadow-sm"
                                             :class="n.data.type === 'announcement' ? 'bg-amber-100 text-amber-600 dark:bg-amber-900/30' : 'bg-blue-100 text-blue-600 dark:bg-blue-900/30'">
                                            <template x-if="n.data.type === 'announcement'">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                                            </template>
                                            <template x-if="n.data.type === 'mention'">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                            </template>
                                        </div>
                                        <div class="flex-1 min-w-0">
                                            <p class="text-[12px] font-bold text-slate-900 dark:text-white leading-tight mb-0.5" x-text="n.data.sender_name || n.data.author_name"></p>
                                            <p class="text-[11px] text-slate-600 dark:text-slate-400 line-clamp-2" x-text="n.data.message"></p>
                                            <p class="text-[9px] text-slate-400 dark:text-slate-500 mt-1.5 font-bold uppercase tracking-tight" x-text="new Date(n.created_at).toLocaleString('id-ID', {hour:'2-digit', minute:'2-digit'})"></p>
                                        </div>
                                    </div>
                                </div>
                            </template>
                        </div>
                        
                        <div class="p-2 bg-slate-50/50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-800">
                            <span class="block w-full py-1.5 text-center text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest">Pusat Notifikasi</span>
                        </div>
                    </div>
                </div>

                {{-- Theme toggle --}}
                <button @click="toggleTheme($event)"
                        id="theme-toggle-btn"
                        class="w-8 h-8 flex items-center justify-center rounded-lg text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-white hover:bg-black/5 dark:hover:bg-white/8 transition-colors flex-shrink-0"
                        title="Toggle theme">
                    <svg class="w-4 h-4 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                    </svg>
                    <svg class="w-4 h-4 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364-.707-.707M6.343 6.343l-.707-.707m12.728 0-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </button>

                {{-- Separator --}}
                <div class="w-px h-4 bg-black/8 dark:bg-white/8 mx-1"></div>

                {{-- User avatar --}}
                <div class="flex items-center gap-2">
                    <div class="hidden sm:block text-right">
                        <p class="text-[12px] font-semibold leading-tight text-slate-700 dark:text-slate-200">{{ Auth::user()->name }}</p>
                        <p class="text-[10.5px] text-slate-400 dark:text-slate-500 leading-tight mt-0.5">{{ Auth::user()->jabatan ?? 'Anggota' }}</p>
                    </div>
                    @if(Auth::user()->foto_profil)
                        <img src="{{ asset('storage/'.Auth::user()->foto_profil) }}" alt="Profil" class="w-8 h-8 object-cover rounded-full flex-shrink-0 ring-2 ring-blue-500/20 dark:ring-blue-400/20">
                    @else
                        <div class="w-8 h-8 rounded-full flex items-center justify-center text-white text-[12px] font-bold flex-shrink-0 ring-2 ring-blue-500/20 dark:ring-blue-400/20"
                             style="background: linear-gradient(135deg, #1e40af, #3b82f6);">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    @endif
                </div>
            </div>
        </header>

        {{-- ── CONTENT ─────────────────────────────────────────── --}}
        <div class="main-scroll content-bg">
            <main @class([
                'content-wrapper',
                'h-full overflow-hidden' => $noPadding,
                '!p-0' => $noPadding,
            ])>
                {{ $slot }}
            </main>
        </div>
    </div>
</div>

{{-- Toast notifications (bottom-right to avoid search bar) --}}
@if(session('toast'))
    <x-toast :type="session('toast.type', 'info')" :message="session('toast.message')" />
@endif

{{-- Turbo Frame Target for Modals --}}
<turbo-frame id="modal"></turbo-frame>

@stack('modals')
<x-ai-assistant />

@if(session('login_announcement'))
    <script>
        (function() {
            const showAnnouncement = () => {
                if (typeof Swal === 'undefined') return;
                
                const isDark = document.documentElement.classList.contains('dark');
                Swal.fire({
                    title: `<span style='color:${isDark?'#f1f5f9':'#0f172a'};font-weight:800;font-family:Inter;text-transform:uppercase;'>PENGUMUMAN PENTING</span>`,
                    html: `<div id="prank-container" style='color:${isDark?'#94a3b8':'#64748b'};font-family:Inter;line-height:1.6;font-size:14px;position:relative;padding-top:10px;'>
                        Jika kamu menemukan <strong style='color:${isDark?'#f1f5f9':'#0f172a'}'>bug, error</strong>, atau fitur yang tidak berjalan dengan baik, mohon segera kabari <strong style='font-size:18px;color:${isDark?'#3b82f6':'#1d4ed8'}'>JN</strong> ya 🙏<br><br>
                        Laporan kamu sangat membantu untuk meningkatkan kualitas sistem.<br>
                        <span style='font-size:14px;font-weight:900;color:${isDark?'#64748b':'#94a3b8'};text-transform:uppercase;margin-top:20px;display:block;letter-spacing:0.1em;'>BTW PEGEL NGODING ANJAYYY</span>
                    </div>`,
                    icon: 'warning',
                    iconColor: '#f59e0b',
                    background: isDark ? '#111827' : '#ffffff',
                    confirmButtonText: 'SIAP, MENGERTI!',
                    buttonsStyling: false,
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    backdrop: `rgba(0,0,0,0.6)`,
                    customClass: {
                        popup: 'rounded-[32px] border border-white/10 shadow-2xl prank-popup-box',
                        confirmButton: 'px-8 py-3.5 bg-blue-600 hover:bg-blue-700 text-white rounded-2xl font-black text-sm transition-all shadow-lg shadow-blue-500/30 mb-2'
                    },
                    didOpen: () => {
                        const blurElements = document.querySelectorAll('body > :not(.swal2-container)');
                        blurElements.forEach(el => {
                            el.style.filter = 'blur(15px)';
                            el.style.transition = 'filter 0.5s ease';
                        });

                        const popup = Swal.getPopup();
                        if (!popup) return;

                        // Tambah tombol X Merah
                        const xBtn = document.createElement('button');
                        xBtn.id = 'prank-x';
                        xBtn.innerHTML = '&times;';
                        xBtn.style.cssText = `
                            position: absolute;
                            top: 20px;
                            right: 20px;
                            width: 32px;
                            height: 32px;
                            background: #ef4444;
                            color: white;
                            border: none;
                            border-radius: 50%;
                            font-size: 20px;
                            font-weight: bold;
                            cursor: pointer;
                            display: flex;
                            align-items: center;
                            justify-content: center;
                            z-index: 100;
                            box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3);
                        `;
                        popup.appendChild(xBtn);

                        // Posisi awal & Animasi SLOWER (2s)
                        popup.style.position = 'fixed';
                        popup.style.top = '50%';
                        popup.style.left = '50%';
                        popup.style.transform = 'translate(-50%, -50%)';
                        popup.style.margin = '0';
                        popup.style.transition = 'all 2s cubic-bezier(0.23, 1, 0.32, 1)'; // Sangat kalem (2 detik)

                        const movePopup = () => {
                            const w = window.innerWidth;
                            const h = window.innerHeight;
                            const pW = popup.offsetWidth;
                            const pH = popup.offsetHeight;
                            
                            const margin = 40;
                            const newTop = margin + (pH/2) + Math.random() * (h - pH - (margin*2));
                            const newLeft = margin + (pW/2) + Math.random() * (w - pW - (margin*2));
                            
                            popup.style.top = newTop + 'px';
                            popup.style.left = newLeft + 'px';
                        };

                        window.onmousemove = (e) => {
                            const rect = popup.getBoundingClientRect();
                            const mouseX = e.clientX;
                            const mouseY = e.clientY;

                            const isNearTop = mouseY < (rect.top + rect.height / 3);
                            const isInsidePopupWidth = mouseX > (rect.left - 20) && mouseX < (rect.right + 20);

                            if (isInsidePopupWidth && isNearTop && mouseY > (rect.top - 80)) {
                                movePopup();
                            }
                        };

                        xBtn.addEventListener('click', (e) => {
                            e.preventDefault();
                            movePopup();
                        });
                    },
                    preConfirm: () => {
                        return Swal.fire({
                            imageUrl: '{{ asset("monyet.png") }}',
                            imageWidth: '100%',
                            imageAlt: 'Monyet',
                            background: 'transparent',
                            showConfirmButton: false,
                            allowOutsideClick: true,
                            allowEscapeKey: true,
                            width: 'auto',
                            padding: '0',
                            customClass: {
                                popup: 'rounded-[32px] overflow-hidden p-0 border-none shadow-none bg-transparent',
                                image: 'm-0 rounded-[32px] shadow-2xl cursor-pointer block w-full h-auto'
                            },
                            backdrop: `rgba(0,0,0,0.9)`,
                            didOpen: () => {
                                // Agar ketika foto DIKLIK juga langsung hilang
                                const popup = Swal.getPopup();
                                if (popup) {
                                    popup.addEventListener('click', () => {
                                        Swal.close();
                                    });
                                }
                            },
                            didClose: () => {
                                // Pembersihan Blur Total
                                const blurElements = document.querySelectorAll('body > :not(.swal2-container)');
                                blurElements.forEach(el => el.style.filter = 'none');
                                window.onmousemove = null;
                            }
                        });
                    },
                    willClose: () => {
                        // Jaga-jaga unblur jika tutup paksa di modal awal
                        const blurElements = document.querySelectorAll('body > :not(.swal2-container)');
                        blurElements.forEach(el => el.style.filter = 'none');
                        window.onmousemove = null;
                    }
                });
            };

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', () => setTimeout(showAnnouncement, 500));
            } else {
                setTimeout(showAnnouncement, 500);
            }
            
            document.addEventListener('turbo:load', showAnnouncement, { once: true });
        })();
    </script>
@endif

{{-- Persistent Voice Sink for WebRTC (survives Turbo transitions) --}}
<div id="voice-track-sink" data-turbo-permanent class="fixed opacity-0 pointer-events-none w-0 h-0 overflow-hidden"></div>

@stack('scripts')

<style>
    /* ── Inline app-shell page styles ──────────── */
    html, body { height: 100%; overflow: hidden; margin: 0; padding: 0; }
    /* .app-shell handles its own scrolling via .main-scroll */
    * { box-sizing: border-box; }
    [x-cloak] { display: none !important; }

    /* Voice lounge animations */
    @keyframes soundwave {
        0%, 100% { transform: scaleY(0.3); }
        50%       { transform: scaleY(1); }
    }
    .animate-soundwave { animation: soundwave 1s ease-in-out infinite; transform-origin: bottom; }

    @keyframes log-entry {
        0%   { opacity: 0; transform: translateY(16px) scale(0.96); }
        100% { opacity: 1; transform: translateY(0) scale(1); }
    }
    .animate-log-entry { animation: log-entry 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; }

    @keyframes scale-up {
        0%   { opacity: 0; transform: scale(0.8); }
        100% { opacity: 1; transform: scale(1); }
    }
    .animate-scale-up { animation: scale-up 0.35s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }

    /* Glow hijau saat berbicara */
    .speaker-glow, .speaking-glow-circle { 
        animation: speaking-glow-pulse 1.5s infinite; 
    }
    @keyframes speaking-glow-pulse {
        0%   { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.7); opacity: 0.7; }
        70%  { box-shadow: 0 0 0 15px rgba(34, 197, 94, 0); opacity: 0; }
        100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); opacity: 0; }
    }

    /* Prevent VT pointer block */
    ::view-transition, ::view-transition-group(root),
    ::view-transition-image-pair(root), ::view-transition-old(root),
    ::view-transition-new(root) { pointer-events: none !important; }
</style>
</body>
</html>
