<!DOCTYPE html>
<html lang="id" id="html-root">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akses Ditolak - Organisasi</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        (function() {
            const theme = localStorage.getItem('theme');
            const prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            const isDark = theme === 'dark' || (!theme && prefersDark);
            if (isDark) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
</head>
<body class="bg-slate-50 dark:bg-slate-950 min-h-screen font-sans antialiased">
    <div class="min-h-screen flex items-center justify-center px-6 py-12">
        <div class="w-full max-w-md text-center">
            <div class="rounded-xl bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 p-8 shadow-sm">
                {{-- Icon --}}
                <div class="flex justify-center mb-6">
                    <div class="h-16 w-16 rounded-full bg-red-100 dark:bg-red-500/20 flex items-center justify-center">
                        <svg class="h-8 w-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                </div>

                {{-- Title --}}
                <h1 class="text-2xl font-semibold text-slate-900 dark:text-white mb-2">Akses Ditolak</h1>
                <p class="text-slate-600 dark:text-slate-400 mb-6">
                    Anda tidak memiliki izin untuk mengakses halaman ini.
                </p>

                {{-- Info --}}
                <div class="bg-slate-50 dark:bg-slate-700/50 rounded-lg p-4 mb-6">
                    <p class="text-sm text-slate-600 dark:text-slate-400">
                        Jika Anda memerlukan akses ke halaman ini, silakan hubungi administrator.
                    </p>
                </div>

                {{-- Actions --}}
                <div class="flex flex-col sm:flex-row gap-3">
                    <a 
                        href="{{ route('dashboard') }}" 
                        class="flex-1 bg-blue-600 dark:bg-blue-500 text-white font-medium py-2.5 px-4 rounded-lg hover:bg-blue-700 dark:hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-600 dark:focus:ring-blue-500 focus:ring-offset-2 dark:focus:ring-offset-slate-800 transition-colors text-center"
                    >
                        Kembali ke Dashboard
                    </a>
                    <button 
                        onclick="window.history.back()" 
                        class="flex-1 bg-slate-200 dark:bg-slate-700 text-slate-700 dark:text-slate-300 font-medium py-2.5 px-4 rounded-lg hover:bg-slate-300 dark:hover:bg-slate-600 focus:outline-none focus:ring-2 focus:ring-slate-500 focus:ring-offset-2 dark:focus:ring-offset-slate-800 transition-colors"
                    >
                        Kembali
                    </button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
