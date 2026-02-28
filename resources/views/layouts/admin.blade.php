@props(['title' => 'Dashboard', 'noPadding' => false])
<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $title }} - Organisasi</title>

    <script>
        // Global User Data for Stores - MUST BE FIRST
        window.userData = {
            id: {{ auth()->id() }},
            name: {!! json_encode(auth()->user()->name ?? 'User') !!},
            avatar: {!! json_encode(auth()->user()->avatar ?? null) !!}
        };
    </script>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script>
        // Theme initialization
        (function() {
            const theme = localStorage.getItem('theme') || 'light';
            if (theme === 'dark') document.documentElement.classList.add('dark');
        })();

        // Global Theme Toggle
        window.toggleTheme = function() {
            const isDark = document.documentElement.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
            window.dispatchEvent(new CustomEvent('theme-changed', { detail: { dark: isDark } }));
        };

        // Global Confirmation Modal
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
                reverseButtons: false, // Standards: Batal Left, Ya Right
                buttonsStyling: false,
                customClass: {
                    container: 'z-[99999]', // Force above sidebar (z-40/50)
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
    </script>
    <style>
        [x-cloak] { display: none !important; }
        /* Smooth Scrollbar for Premium Feel */
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { 
            @apply bg-slate-200 dark:bg-slate-800 rounded-full; 
        }
        .custom-scrollbar:hover::-webkit-scrollbar-thumb { 
            @apply bg-slate-300 dark:bg-slate-700; 
        }
    </style>
    @stack('styles')
</head>
<body class="h-full bg-slate-50 dark:bg-slate-950 font-sans antialiased text-slate-900 dark:text-slate-100 dashboard-zoom">
    
    <div x-data="{ 
            sidebarOpen: false, 
            isMobile: window.innerWidth < 1024,
            init() {
                window.addEventListener('resize', () => this.isMobile = window.innerWidth < 1024);
                $store.voice.setUser({
                    id: {{ auth()->id() }},
                    name: {{ Illuminate\Support\Js::from(auth()->user()->name ?? 'User') }},
                    avatar: {{ Illuminate\Support\Js::from(auth()->user()->avatar ?? null) }}
                });
            }
         }" 
         class="flex h-full overflow-hidden">

        {{-- Mobile Overlay --}}
        <div x-show="sidebarOpen" 
             @click="sidebarOpen = false" 
             x-cloak
             x-transition:enter="transition-opacity ease-linear duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition-opacity ease-linear duration-300"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="sidebar-overlay lg:hidden"></div>

        {{-- Sidebar --}}
        <aside :class="{ '-translate-x-full': isMobile && !sidebarOpen, 'translate-x-0': !isMobile || sidebarOpen }"
               class="sidebar-rail shadow-xl lg:shadow-none">
            @include('components.sidebar')
        </aside>

        {{-- Content Wrapper --}}
        <div class="flex-1 flex flex-col min-w-0 h-full overflow-hidden bg-white dark:bg-slate-900 lg:rounded-tl-premium lg:border-l lg:border-t lg:border-slate-200 lg:dark:border-slate-800 shadow-premium">
            
            {{-- Top Header --}}
            <header class="h-16 flex-shrink-0 flex items-center justify-between px-6 border-b border-slate-100 dark:border-slate-800 bg-white/80 dark:bg-slate-900/80 backdrop-blur-md sticky top-0 z-30">
                <div class="flex items-center gap-4 flex-1">
                    <button @click="sidebarOpen = true" class="lg:hidden p-2 -ml-2 text-slate-500 hover:text-slate-900 dark:hover:text-white transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <x-global-search />
                </div>

                <div class="flex items-center gap-3">
                    {{-- Theme Toggle --}}
                    <button @click="toggleTheme()" class="p-2 text-slate-500 hover:bg-slate-100 dark:hover:bg-slate-800 rounded-lg transition-colors" title="Ganti Tema">
                        <svg class="w-5 h-5 dark:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"></path></svg>
                        <svg class="w-5 h-5 hidden dark:block" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    </button>

                    <div class="h-6 w-px bg-slate-200 dark:bg-slate-800 mx-1"></div>

                    {{-- Profile Mini --}}
                    <div class="flex items-center gap-3 pl-1">
                        <div class="text-right hidden sm:block">
                            <p class="text-xs font-bold">{{ Auth::user()->name }}</p>
                            <p class="text-[10px] text-slate-500 font-medium tracking-tight">{{ Auth::user()->jabatan ?? 'Anggota' }}</p>
                        </div>
                        <div class="w-8 h-8 rounded-full bg-brand flex items-center justify-center text-white text-xs font-bold shadow-sm">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    </div>
                </div>
            </header>

            {{-- Main Content --}}
            <main @class([
                'flex-1 min-h-0 relative',
                'p-6 overflow-y-auto custom-scrollbar' => !$noPadding,
                'overflow-hidden' => $noPadding
            ])>
                {{ $slot }}
            </main>

            {{-- Footer Area (Optional/Compact) --}}
            <footer class="h-10 flex-shrink-0 flex items-center justify-between px-6 border-t border-slate-50 dark:border-slate-800/50 bg-slate-50/10 dark:bg-slate-900/10 backdrop-blur-sm">
                <p class="text-[10px] font-medium text-slate-400">&copy; {{ date('Y') }} Organisasi App. Professional Version.</p>
                <div class="flex items-center gap-4 text-[10px] font-bold text-slate-400 tracking-wider uppercase">
                    <span class="flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-green-500"></span> Sistem Berjalan Optimal</span>
                </div>
            </footer>
        </div>
    </div>
    
    {{-- Global Modal Frame --}}
    <turbo-frame id="modal"></turbo-frame>
    @stack('modals')
    <x-ai-assistant />

    @if(session('toast'))
        <x-toast :type="session('toast.type', 'info')" :message="session('toast.message')" />
    @endif

    @stack('scripts')
</body>
</html>
