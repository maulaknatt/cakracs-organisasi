{{-- Layout utama dashboard admin --}}
<!DOCTYPE html>
<html lang="id" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - Organisasi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Initialize theme before page renders - Default to Light Mode
        (function() {
            const theme = localStorage.getItem('theme');
            const isDark = theme === 'dark';
            if (isDark) {
                document.documentElement.classList.add('dark');
            } else {
                document.documentElement.classList.remove('dark');
            }
        })();
        
        // Theme toggle function
        window.toggleTheme = function(event) {
            // Get button center coordinates or default to center screen
            let x = window.innerWidth / 2;
            let y = window.innerHeight / 2;

            if (event && event.currentTarget) {
                const rect = event.currentTarget.getBoundingClientRect();
                x = rect.left + rect.width / 2;
                y = rect.top + rect.height / 2;
            }
            
            const performToggle = () => {
                const html = document.documentElement;
                const isDark = html.classList.contains('dark');
                
                if (isDark) {
                    html.classList.remove('dark');
                    localStorage.setItem('theme', 'light');
                } else {
                    html.classList.add('dark');
                    localStorage.setItem('theme', 'dark');
                }
                
                window.dispatchEvent(new CustomEvent('theme-changed', { 
                    detail: { dark: !isDark } 
                }));
            };

            // Check if View Transition API is supported
            if (!document.startViewTransition) {
                performToggle();
                return;
            }

            // Set coordinates for the animation
            document.documentElement.style.setProperty('--x', x + 'px');
            document.documentElement.style.setProperty('--y', y + 'px');

            document.startViewTransition(() => {
                performToggle();
            });
        };
        
        // Global Confirmation Modal (SweetAlert2 - Custom UI)
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
        
        window.isDarkMode = function() {
            return document.documentElement.classList.contains('dark');
        };
    </script>
    <style>
        .custom-scrollbar::-webkit-scrollbar {
            width: 5px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: transparent;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, 0.1);
            border-radius: 10px;
            transition: all 0.3s;
        }
        .custom-scrollbar:hover::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, 0.3);
        }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, 0.05);
        }
        .dark .custom-scrollbar:hover::-webkit-scrollbar-thumb {
            background: rgba(148, 163, 184, 0.2);
        }
    </style>
</head>
<body class="bg-slate-50 dark:bg-slate-950 h-screen h-[100dvh] overflow-hidden font-sans antialiased">
    <div x-data="{ sidebarOpen: false }" 
         x-init="$store.voice.setUser({
            id: {{ auth()->id() }},
            name: {{ \Illuminate\Support\Js::from(auth()->user()->name ?? 'User') }},
            avatar: {{ \Illuminate\Support\Js::from(auth()->user()->foto_profil ?? null) }}
         })"
         class="flex h-full">
        {{-- Sidebar kiri --}}
        @include('components.sidebar')
        
        {{-- Main content area --}}
        <main class="flex-1 flex flex-col min-w-0 overflow-hidden">
            {{-- Header/Topbar --}}
            <header class="sticky top-0 z-40 bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800">
                <div class="px-6 py-4">
                    <div class="flex items-center justify-between gap-4">
                        <div class="flex items-center gap-4 flex-1 min-w-0">
                            <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 -ml-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                                <svg class="h-6 w-6 text-slate-700 dark:text-slate-300" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                                </svg>
                            </button>
                            
                            {{-- Global search --}}
                            <div x-data="{ 
                                q: '', 
                                open: false, 
                                results: [], 
                                loading: false,
                                searchTimeout: null,
                                async search() {
                                    // Clear previous timeout
                                    if (this.searchTimeout) {
                                        clearTimeout(this.searchTimeout);
                                    }
                                    
                                    if (this.q.length < 2) {
                                        this.results = [];
                                        this.open = false;
                                        return;
                                    }
                                    
                                    // Debounce search
                                    this.searchTimeout = setTimeout(async () => {
                                        this.loading = true;
                                        this.open = true; // Open dropdown when searching
                                        
                                        try {
                                            const url = `{{ route('dashboard.search') }}?q=${encodeURIComponent(this.q)}`;
                                            console.log('Searching:', url);
                                            
                                            const response = await fetch(url, {
                                                method: 'GET',
                                                headers: {
                                                    'Accept': 'application/json',
                                                    'X-Requested-With': 'XMLHttpRequest',
                                                },
                                                credentials: 'same-origin'
                                            });
                                            
                                            if (!response.ok) {
                                                throw new Error(`HTTP error! status: ${response.status}`);
                                            }
                                            
                                            const data = await response.json();
                                            console.log('Search results:', data);
                                            console.log('Results count:', data.results?.length || 0);
                                            
                                            this.results = data.results || [];
                                            console.log('this.results after assignment:', this.results);
                                            
                                            // Always keep dropdown open when we have results or are still loading
                                            this.open = true;
                                        } catch (error) {
                                            console.error('Search error:', error);
                                            this.results = [];
                                            // Keep dropdown open to show error state
                                            this.open = true;
                                        } finally {
                                            this.loading = false;
                                        }
                                    }, 300); // 300ms debounce
                                }
                            }" 
                            x-init="
                                $watch('q', value => {
                                    if (value && value.length >= 2) {
                                        search();
                                    } else {
                                        results = [];
                                        if (value.length === 0) {
                                            open = false;
                                        }
                                    }
                                });
                            "
                            @click.away="if (q.length < 2 && results.length === 0) open = false"
                            class="relative flex-1 max-w-xl">
                                <div class="relative">
                                    <input 
                                        type="text" 
                                        x-model="q" 
                                        @focus="if (q.length >= 2 || results.length > 0) open = true"
                                        @keydown.escape="open = false; q = ''"
                                        placeholder="Cari..." 
                                        class="w-full pl-9 pr-4 py-2 bg-slate-50 dark:bg-slate-800/50 border border-slate-200 dark:border-slate-700 rounded-xl text-sm text-slate-900 dark:text-slate-300 placeholder-slate-500 focus:outline-none focus:ring-2 focus:ring-blue-600/20 dark:focus:ring-blue-500/20 focus:border-blue-600 transition-all shadow-sm"
                                        autocomplete="off"
                                    >
                                    <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 h-4 w-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    <div x-show="loading" class="absolute right-3 top-1/2 transform -translate-y-1/2">
                                        <svg class="animate-spin h-4 w-4 text-slate-500" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                    </div>
                                </div>
                                
                                {{-- Search results dropdown --}}
                                <div x-show="open" 
                                     x-transition
                                     x-cloak
                                     class="absolute left-0 right-0 mt-2 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-lg shadow-xl z-50 max-h-96 overflow-y-auto">
                                    <div x-show="loading" class="p-4 text-center text-sm text-slate-600 dark:text-slate-400">
                                        <svg class="animate-spin h-5 w-5 mx-auto mb-2 text-blue-600 dark:text-blue-400" fill="none" viewBox="0 0 24 24">
                                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                        </svg>
                                        Mencari...
                                    </div>
                                    
                                    <div x-show="!loading && results.length === 0 && q.length >= 2" class="p-4 text-center text-sm text-slate-600 dark:text-slate-400">
                                        <svg class="mx-auto h-8 w-8 text-slate-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                        </svg>
                                        Tidak ada hasil ditemukan
                                    </div>
                                    
                                    <div x-show="!loading && results.length > 0">
                                        <div class="px-4 py-2 text-xs font-medium text-slate-500 dark:text-slate-400 bg-slate-50 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-700">
                                            Menampilkan <span x-text="results.length"></span> hasil
                                        </div>
                                        <template x-for="(item, index) in results" :key="index">
                                            <a :href="item.url" 
                                               @click="open = false; q = '';" 
                                               class="block px-4 py-3 hover:bg-slate-50 dark:hover:bg-slate-700/50 text-sm text-slate-900 dark:text-slate-300 transition-colors border-b border-slate-200 dark:border-slate-700 last:border-0">
                                                <div class="font-medium" x-text="item.label"></div>
                                                <div class="text-xs text-slate-500 dark:text-slate-400 mt-0.5" x-text="item.type"></div>
                                            </a>
                                        </template>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Right side actions --}}
                        <div class="flex items-center gap-4">
                            
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
                                        class="p-2.5 rounded-xl border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all shadow-sm relative group">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="text-slate-600 dark:text-slate-400 group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors" viewBox="0 0 16 16">
                                        <path d="M8 16a2 2 0 0 0 2-2H6a2 2 0 0 0 2 2M8 1.918l-.797.161A4 4 0 0 0 4 6c0 .628-.134 2.197-.459 3.742-.16.767-.376 1.566-.663 2.258h10.244c-.287-.692-.502-1.49-.663-2.258C12.134 8.197 12 6.628 12 6a4 4 0 0 0-3.203-3.92zM14.22 12c.223.447.481.801.78 1H1c.299-.199.557-.553.78-1C2.68 10.2 3 6.88 3 6c0-2.42 1.72-4.44 4.005-4.901a1 1 0 1 1 1.99 0A5 5 0 0 1 13 6c0 .88.32 4.2 1.22 6"/>
                                    </svg>
                                    <template x-if="unreadCount > 0">
                                        <span class="absolute top-2 right-2 flex h-2.5 w-2.5">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500 border-2 border-white dark:border-slate-800"></span>
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
                                     class="absolute right-0 mt-3 w-80 sm:w-96 bg-white dark:bg-slate-900 border border-slate-200 dark:border-slate-800 rounded-2xl shadow-2xl z-50 overflow-hidden transform origin-top-right">
                                    
                                    <div class="px-5 py-4 border-b border-slate-100 dark:border-slate-800 bg-slate-50/50 dark:bg-slate-900/50 flex items-center justify-between">
                                        <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">Notifikasi</h3>
                                        <button @click="markAllRead()" x-show="unreadCount > 0" class="text-[11px] font-bold text-blue-600 dark:text-blue-400 hover:underline">Tandai semua dibaca</button>
                                    </div>

                                    <div class="max-h-[400px] overflow-y-auto custom-scrollbar">
                                        <template x-if="notifications.length === 0">
                                            <div class="p-10 text-center">
                                                <div class="w-16 h-16 bg-slate-100 dark:bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-4">
                                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                                    </svg>
                                                </div>
                                                <p class="text-sm text-slate-500 dark:text-slate-400 font-medium">Belum ada notifikasi baru</p>
                                            </div>
                                        </template>

                                        <template x-for="n in notifications" :key="n.id">
                                            <div @click="markRead(n.id, n.data.url)" 
                                                 class="px-5 py-4 border-b border-slate-50 dark:border-slate-800 hover:bg-slate-50 dark:hover:bg-slate-800/50 cursor-pointer transition-colors relative group">
                                                <div class="flex gap-4">
                                                    <div class="shrink-0 h-10 w-10 rounded-xl flex items-center justify-center shadow-sm"
                                                         :class="n.data.type === 'announcement' ? 'bg-amber-100 text-amber-600 dark:bg-amber-900/30' : 'bg-blue-100 text-blue-600 dark:bg-blue-900/30'">
                                                        <template x-if="n.data.type === 'announcement'">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"/></svg>
                                                        </template>
                                                        <template x-if="n.data.type === 'mention'">
                                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                                        </template>
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <p class="text-[13px] font-bold text-slate-900 dark:text-white leading-tight mb-1" x-text="n.data.sender_name || n.data.author_name"></p>
                                                        <p class="text-xs text-slate-600 dark:text-slate-400 line-clamp-2" x-text="n.data.message"></p>
                                                        <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-2 font-medium" x-text="new Date(n.created_at).toLocaleString('id-ID', {hour:'2-digit', minute:'2-digit', day:'2-digit', month:'short'})"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </template>
                                    </div>
                                    
                                    <div class="p-3 bg-slate-50/50 dark:bg-slate-900/50 border-t border-slate-100 dark:border-slate-800">
                                        <a href="{{ route('notifications.index') }}" class="block w-full py-2 text-center text-xs font-bold text-slate-500 dark:text-slate-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors uppercase tracking-widest">Lihat Semua Notifikasi</a>
                                    </div>
                                </div>
                            </div>

                            {{-- Theme toggle --}}
                            <div x-data="{ 
                                dark: document.documentElement.classList.contains('dark'),
                                init() {
                                    // Update state when theme changes
                                    window.addEventListener('theme-changed', (e) => {
                                        // Always read the truth from the DOM
                                        this.dark = document.documentElement.classList.contains('dark');
                                    });
                                },
                                toggle(e) {
                                    window.toggleTheme(e);
                                    // Do NOT update immediately here, wait for event
                                }
                            }" class="flex items-center">
                                <button
                                    @click="toggle($event)"
                                    class="p-2 rounded-xl border border-slate-200 dark:border-slate-700 hover:bg-slate-100 dark:hover:bg-slate-800 transition-all shadow-sm"
                                    :aria-label="dark ? 'Aktifkan light mode' : 'Aktifkan dark mode'"
                                    title="Toggle theme"
                                >
                                    {{-- Moon icon (light mode) --}}
                                    <svg x-show="!dark" class="h-5 w-5 text-slate-700 dark:text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                                    </svg>
                                    {{-- Sun icon (dark mode) --}}
                                    <svg x-show="dark" x-cloak class="h-5 w-5 text-slate-700 dark:text-slate-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                </button>
                            </div>
                            
                            {{-- User menu --}}
                            @php
                                $user = Auth::user();
                            @endphp
                            <div class="flex items-center gap-3 pl-3 lg:pl-4 border-l border-slate-200 dark:border-slate-700 shrink-0">
                                <div class="text-right hidden md:block">
                                    <div class="text-[14.5px] font-semibold text-slate-900 dark:text-slate-300">{{ $user->name ?? 'Admin' }}</div>
                                    <div class="text-[12.5px] text-slate-600 dark:text-slate-500">
                                        {{ $user->jabatan ?? 'Anggota' }}
                                    </div>
                                </div>
                                @if($user->foto_profil)
                                    <img src="{{ asset('storage/'.$user->foto_profil) }}" class="h-9 w-9 object-cover rounded-full border border-slate-200 dark:border-slate-700">
                                @else
                                    <div class="h-9 w-9 rounded-full bg-blue-600 dark:bg-blue-500 text-white flex items-center justify-center font-bold text-[14px]">
                                        {{ strtoupper(substr($user->name ?? 'A', 0, 1)) }}
                                    </div>
                                @endif
                             </div>
                         </div>
                        </div>
                    </div>
                </div>
            </header>
            
            <div class="relative flex-1 min-h-0 @if(!Request::is('dashboard/voice*') && !Request::is('dashboard/chat*')) px-4 pt-4 pb-16 lg:px-6 lg:py-6 space-y-4 overflow-y-auto custom-scrollbar @endif">
                @yield('content')
            </div>
            
        </main>
    {{-- Root div closes later --}}
    
    {{-- Global modal confirm (Replaced by SweetAlert2) --}}
    {{-- <x-modal-confirm id="modal-confirm" /> --}}
    
    {{-- Toast notification global --}}
    @if(session('toast'))
        @php $toast = session('toast'); @endphp
        <x-toast :type="$toast['type'] ?? 'success'" :message="$toast['message'] ?? ''" />
    @endif
    
    </div> {{-- Close root x-data container --}}

    <script>
       // Logic moved to stores/voiceStore.js
    </script>
    <style>
        [x-cloak] { display: none !important; }

        /* 
         * View Transition: Circular Reveal
         * Durasi 3.5s sesuai permintaan.
         */
        ::view-transition-group(root),
        ::view-transition-old(root),
        ::view-transition-new(root) {
            animation: none;
            mix-blend-mode: normal;
        }

        ::view-transition-old(root) {
            z-index: 1;
        }

        ::view-transition-new(root) {
            z-index: 9999;
            clip-path: circle(0% at var(--x) var(--y));
            animation: 3.5s cubic-bezier(0.4, 0, 0.2, 1) circle-expand forwards;
        }

        @keyframes circle-expand {
            from { clip-path: circle(0% at var(--x) var(--y)); }
            to { clip-path: circle(150% at var(--x) var(--y)); }
        }

        /* Social Lounge Animations */
        @keyframes soundwave {
            0%, 100% { transform: scaleY(0.3); }
            50% { transform: scaleY(1); }
        }
        .animate-soundwave {
            animation: soundwave 1s ease-in-out infinite;
            transform-origin: bottom;
        }

        @keyframes log-entry {
            0% { opacity: 0; transform: translateY(20px) scale(0.9); }
            100% { opacity: 1; transform: translateY(0) scale(1); }
        }
        .animate-log-entry {
            animation: log-entry 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards;
        }

        @keyframes scale-up {
            0% { opacity: 0; transform: scale(0.8); }
            100% { opacity: 1; transform: scale(1); }
        }
        .animate-scale-up {
            animation: scale-up 0.4s cubic-bezier(0.34, 1.56, 0.64, 1) forwards;
        }

        .speaker-glow {
            box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.4);
            animation: speaker-pulse 1.5s infinite;
        }
        @keyframes speaker-pulse {
            0% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0.6); }
            70% { box-shadow: 0 0 0 15px rgba(34, 197, 94, 0); }
            100% { box-shadow: 0 0 0 0 rgba(34, 197, 94, 0); }
        }

        /* Memastikan elemen transisi tidak menghalangi klik jika durasi lama */
        ::view-transition,
        ::view-transition-group(root),
        ::view-transition-image-pair(root),
        ::view-transition-old(root),
        ::view-transition-new(root) {
            pointer-events: none !important;
        }
    </style>
</body>
</html>
