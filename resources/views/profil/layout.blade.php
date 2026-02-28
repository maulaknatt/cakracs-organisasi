<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Organisasi') }} @yield('title')</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Material Icons & Symbols -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    
    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script> -->
    
    <script>
        tailwind.config = {
          darkMode: "class",
          theme: {
            extend: {
              colors: {
                "primary": "#256af4",
                "background-light": "#f8f9fa",
                "background-dark": "#0a0a0c",
              },
              fontFamily: {
                "display": ["Space Grotesk", "sans-serif"],
                "sans": ["Space Grotesk", "sans-serif"]
              },
              borderRadius: {"DEFAULT": "0px", "lg": "0px", "xl": "0px", "2xl": "0px", "3xl": "0px", "full": "9999px"},
            },
          },
        }
    </script>

    <style type="text/tailwindcss">
        @layer base {
            body {
                font-family: 'Space Grotesk', sans-serif;
            }
        }
        @layer utilities {
            .bg-dot-pattern {
                background-image: radial-gradient(#256af4 0.5px, transparent 0.5px);
                background-size: 24px 24px;
            }
            .bg-grid-pattern {
                background-image: linear-gradient(to right, rgba(37, 106, 244, 0.05) 1px, transparent 1px),
                                  linear-gradient(to bottom, rgba(37, 106, 244, 0.05) 1px, transparent 1px);
                background-size: 40px 40px;
            }
            .text-stroke {
                -webkit-text-stroke: 1px rgba(37, 106, 244, 0.3);
                color: transparent;
            }
            .animate-marquee {
                animation: marquee 30s linear infinite;
            }

            /* Scroll Reveal System */
            .reveal {
                opacity: 0;
                transform: translateY(40px);
                transition: all 1s cubic-bezier(0.16, 1, 0.3, 1);
                filter: blur(8px);
            }
            .reveal.active {
                opacity: 1;
                transform: translateY(0);
                filter: blur(0);
            }
            .reveal-scale { transform: scale(0.9) translateY(40px); }
            .reveal-scale.active { transform: scale(1) translateY(0); }
            
            .reveal-left { transform: translateX(-40px); opacity: 0; }
            .reveal-left.active { transform: translateX(0); opacity: 1; }
            
            .reveal-right { transform: translateX(40px); opacity: 0; }
            .reveal-right.active { transform: translateX(0); opacity: 1; }
        }

        @keyframes marquee {
            0% { transform: translateX(0); }
            100% { transform: translateX(-50%); }
        }

        .glass-nav { 
            @apply bg-white/90 dark:bg-background-dark/90 backdrop-blur-xl border-b border-transparent transition-all duration-500;
        }
        .nav-scrolled .glass-nav {
            @apply border-slate-200 dark:border-white/5 shadow-2xl;
        }
        
        /* Editorial Reveal */
        @keyframes editorialReveal {
            0% { opacity: 0; transform: translateY(40px); filter: blur(10px); }
            100% { opacity: 1; transform: translateY(0); filter: blur(0); }
        }
        .animate-reveal { 
            opacity: 0; 
            animation: editorialReveal 1.2s cubic-bezier(0.16, 1, 0.3, 1) forwards; 
        }

        .text-gradient { 
            @apply bg-clip-text text-transparent bg-gradient-to-br from-slate-900 via-slate-700 to-slate-500 dark:from-white dark:via-slate-200 dark:to-slate-400; 
        }

        /* Custom Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { @apply bg-transparent; }
        ::-webkit-scrollbar-thumb { @apply bg-primary/20 hover:bg-primary/40 rounded-full; }
    </style>
</head>
<body class="bg-background-light dark:bg-background-dark font-display text-slate-900 dark:text-slate-50 antialiased overflow-x-hidden selection:bg-primary selection:text-white">
    
    <!-- Navbar -->
    <nav class="fixed top-0 left-0 right-0 z-50 w-full transition-all duration-300">
        <div class="absolute inset-0 glass-nav"></div>
        <div class="relative mx-auto max-w-[1200px] px-4 sm:px-6 lg:px-8">
            <div class="flex h-20 items-center justify-between">
                <!-- Logo -->
                <a href="{{ route('profil.beranda') }}" class="flex items-center gap-3">
                    <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-primary text-white shadow-lg shadow-primary/30">
                        <span class="material-symbols-outlined text-[24px]">diversity_3</span>
                    </div>
                    <span class="text-xl font-bold tracking-tight text-slate-900 dark:text-white">CAKRA CS</span>
                </a>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center gap-8">
                    <a href="{{ route('profil.beranda') }}" class="text-sm font-semibold {{ request()->is('/') ? 'text-primary' : 'text-slate-600 dark:text-slate-300' }} hover:text-primary dark:hover:text-primary transition-colors">Beranda</a>
                    <a href="{{ route('profil.tentang') }}" class="text-sm font-semibold {{ request()->is('tentang') ? 'text-primary' : 'text-slate-600 dark:text-slate-300' }} hover:text-primary dark:hover:text-primary transition-colors">Tentang</a>
                    <a href="{{ route('profil.kegiatan') }}" class="text-sm font-semibold {{ request()->is('kegiatan*') ? 'text-primary' : 'text-slate-600 dark:text-slate-300' }} hover:text-primary dark:hover:text-primary transition-colors">Kegiatan</a>
                    <a href="{{ route('profil.galeri') }}" class="text-sm font-semibold {{ request()->is('galeri*') ? 'text-primary' : 'text-slate-600 dark:text-slate-300' }} hover:text-primary dark:hover:text-primary transition-colors">Galeri</a>
                    <a href="{{ route('profil.kontak') }}" class="text-sm font-semibold {{ request()->is('kontak') ? 'text-primary' : 'text-slate-600 dark:text-slate-300' }} hover:text-primary dark:hover:text-primary transition-colors">Kontak</a>
                </div>

                <!-- Nav Right: CTA + Theme Toggle -->
                <div class="hidden md:flex items-center gap-6">
                    <button onclick="toggleDarkMode()" class="p-2 text-slate-600 dark:text-slate-300 hover:text-primary transition-colors" title="Toggle Dark Mode">
                        <span id="dark-icon" class="material-symbols-outlined text-sm">light_mode</span>
                    </button>
                    <a href="{{ route('login') }}" class="px-6 py-2.5 bg-primary text-white font-bold text-xs tracking-widest uppercase hover:bg-slate-900 transition-all">
                        Join Movement
                    </a>
                </div>

                <!-- Mobile Menu Button (simplified) -->
                <button class="md:hidden p-2 text-slate-600 dark:text-slate-300" onclick="toggleMobileMenu()">
                    <span class="material-symbols-outlined">menu</span>
                </button>
            </div>
        </div>
        
        <!-- Mobile Nav Menu (hidden by default) -->
        <div id="mobile-menu" class="hidden md:hidden absolute w-full bg-white dark:bg-slate-900 border-b border-slate-200 dark:border-slate-800 px-4 py-4 space-y-3">
            <a href="{{ route('profil.beranda') }}" class="block text-sm font-semibold text-slate-600 dark:text-slate-300">Beranda</a>
            <a href="{{ route('profil.tentang') }}" class="block text-sm font-semibold text-slate-600 dark:text-slate-300">Tentang</a>
            <a href="{{ route('profil.kegiatan') }}" class="block text-sm font-semibold text-slate-600 dark:text-slate-300">Kegiatan</a>
            <a href="{{ route('profil.galeri') }}" class="block text-sm font-semibold text-slate-600 dark:text-slate-300">Galeri</a>
            <a href="{{ route('profil.kontak') }}" class="block text-sm font-semibold text-slate-600 dark:text-slate-300">Kontak</a>
        </div>
    </nav>

    <!-- Background Background Pattern -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none -z-10 bg-grid-pattern opacity-50 dark:opacity-20"></div>

    <!-- Main Content -->
    <main class="relative w-full pt-20">
        @yield('content')@include('components.footer')
    </main>

    <script>
        // Reveal on scroll logic
        const revealCallback = (entries, observer) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                    // observer.unobserve(entry.target); // Optional: animate only once
                }
            });
        };

        const revealObserver = new IntersectionObserver(revealCallback, {
            threshold: 0.15
        });

        document.addEventListener('turbo:load', () => {
            const revealElements = document.querySelectorAll('.reveal');
            revealElements.forEach(el => revealObserver.observe(el));
        });

        function toggleMobileMenu() {
            const menu = document.getElementById('mobile-menu');
            menu.classList.toggle('hidden');
        }

        function toggleDarkMode() {
            const html = document.documentElement;
            const icon = document.getElementById('dark-icon');
            if (html.classList.contains('dark')) {
                html.classList.remove('dark');
                html.classList.add('light');
                icon.innerText = 'light_mode';
                localStorage.theme = 'light';
            } else {
                html.classList.remove('light');
                html.classList.add('dark');
                icon.innerText = 'dark_mode';
                localStorage.theme = 'dark';
            }
        }
        
        // Initial theme check
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
            document.getElementById('dark-icon').innerText = 'dark_mode';
        } else {
            document.documentElement.classList.remove('dark');
            document.getElementById('dark-icon').innerText = 'light_mode';
        }
        
        // Scroll behavior for navbar
        window.addEventListener('scroll', () => {
            const nav = document.querySelector('nav');
            if (window.scrollY > 50) {
                document.body.classList.add('nav-scrolled');
            } else {
                document.body.classList.remove('nav-scrolled');
            }
        });
    </script>
</body>
</html>
