<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — {{ App\Models\Pengaturan::first()->nama_organisasi ?? 'Organisasi' }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        (function() {
            const theme = localStorage.getItem('theme');
            // Force dark by default for this design
            if (theme === 'dark' || !theme) document.documentElement.classList.add('dark');
            else document.documentElement.classList.remove('dark');
        })();
    </script>
    <style>
        *, *::before, *::after { box-sizing: border-box; }
        * { font-family: 'Inter', sans-serif; }
        [x-cloak] { display: none !important; }

        html, body { 
            margin: 0; 
            padding: 0; 
            width: 100%;
            height: 100vh;
            overflow: hidden; /* Mengunci scroll */
            zoom: 1 !important; /* Menimpa custom zoom dari app.css agar halaman auth tetap aman */
        }

        /* ── Backgrounds ── */
        .auth-bg {
            height: 100vh;
            width: 100%;
            /* Light mode bg: slightly bluish grey */
            background: linear-gradient(135deg, #f1f5f9 0%, #e2e8f0 100%);
            position: relative;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1.5rem;
            transition: background 0.5s ease;
        }
        
        /* Dark mode bg: requested dark blue navy shades */
        .dark .auth-bg {
            background: linear-gradient(135deg, #0f172a 0%, #0a2540 100%);
        }

        /* ── Center Glow ── */
        .glow-center {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 60vw;
            height: 60vw;
            max-width: 800px;
            max-height: 800px;
            background: radial-gradient(circle, rgba(56, 189, 248, 0.15) 0%, transparent 60%);
            filter: blur(60px);
            pointer-events: none;
            z-index: 0;
            transition: all 0.5s ease;
        }
        .dark .glow-center {
            background: radial-gradient(circle, rgba(14, 165, 233, 0.2) 0%, transparent 60%);
        }

        /* ── Particles ── */
        .particle {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
            filter: blur(20px);
            animation: float 10s infinite ease-in-out alternate;
        }
        .dark .particle-1 { background: rgba(56, 189, 248, 0.1); }
        .dark .particle-2 { background: rgba(59, 130, 246, 0.15); }
        .dark .particle-3 { background: rgba(14, 165, 233, 0.1); }
        
        .particle-1 { background: rgba(56, 189, 248, 0.15); width: 300px; height: 300px; top: -10%; left: -10%; animation-duration: 12s; }
        .particle-2 { background: rgba(37, 99, 235, 0.15); width: 250px; height: 250px; bottom: -5%; right: -5%; animation-duration: 15s; }
        .particle-3 { background: rgba(14, 165, 233, 0.15); width: 200px; height: 200px; top: 40%; left: 70%; animation-duration: 18s; filter: blur(40px); }

        @keyframes float {
            0% { transform: translateY(0) translateX(0) scale(1); opacity: 0.6; }
            100% { transform: translateY(-30px) translateX(20px) scale(1.1); opacity: 1; }
        }

        /* ── Card ── */
        .auth-card {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1px solid rgba(255, 255, 255, 0.4);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(15, 23, 42, 0.05);
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 10;
            padding: 2.5rem;
            animation: fadeIn 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
            transform: translateY(20px);
            transition: all 0.5s ease;
        }
        .dark .auth-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.8), 0 0 40px rgba(56, 189, 248, 0.05);
        }

        @keyframes fadeIn {
            to { opacity: 1; transform: translateY(0); }
        }

        /* ── Logo ── */
        .auth-logo {
            width: 56px;
            height: 56px;
            border-radius: 50%;
            background: linear-gradient(135deg, #2563eb 0%, #06b6d4 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1.5rem auto;
            position: relative;
        }
        .auth-logo::before {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            background: inherit;
            filter: blur(12px);
            opacity: 0.6;
            z-index: -1;
        }
        .auth-logo span {
            color: #ffffff;
            font-size: 1.5rem;
            font-weight: 800;
        }
        .auth-logo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 50%;
            background: #ffffff;
        }

        /* ── Typography ── */
        .auth-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: #0f172a;
            text-align: center;
            letter-spacing: -0.025em;
            margin-bottom: 0.25rem;
        }
        .dark .auth-title { color: #f8fafc; }
        
        .auth-subtitle {
            font-size: 0.875rem;
            color: #64748b;
            text-align: center;
            margin-bottom: 2rem;
            opacity: 0.8;
            font-weight: 500;
        }
        .dark .auth-subtitle { color: #94a3b8; }

        /* ── Inputs ── */
        .auth-label {
            display: block;
            font-size: 0.8125rem;
            font-weight: 600;
            margin-bottom: 0.5rem;
            color: #475569;
        }
        .dark .auth-label { color: #cbd5e1; }
        
        .auth-input-group {
            position: relative;
            margin-bottom: 1.25rem;
        }
        .auth-input {
            width: 100%;
            height: 48px;
            padding: 0 1rem 0 2.75rem;
            background: rgba(255, 255, 255, 0.6);
            border: 1px solid rgba(15, 23, 42, 0.1);
            border-radius: 12px;
            color: #0f172a;
            font-size: 0.875rem;
            outline: none;
            transition: all 0.3s ease;
            box-sizing: border-box;
        }
        .dark .auth-input {
            background: rgba(15, 23, 42, 0.4);
            border: 1px solid rgba(56, 189, 248, 0.15);
            color: #f8fafc;
        }
        .dark .auth-input::placeholder { color: #64748b; }
        
        .auth-input:focus {
            background: #ffffff;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }
        .dark .auth-input:focus {
            background: rgba(15, 23, 42, 0.8);
            border-color: #38bdf8;
            box-shadow: 0 0 0 4px rgba(56, 189, 248, 0.15);
        }

        .auth-icon {
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            width: 1.125rem;
            height: 1.125rem;
            color: #64748b;
            transition: color 0.3s ease;
            pointer-events: none;
        }
        .dark .auth-icon { color: #64748b; }
        
        .auth-input:focus + .auth-icon,
        .auth-input:focus ~ .auth-icon { color: #3b82f6; }
        .dark .auth-input:focus + .auth-icon, 
        .dark .auth-input:focus ~ .auth-icon { color: #38bdf8; }

        .auth-eye {
            position: absolute;
            right: 1rem;
            top: 50%;
            transform: translateY(-50%);
            width: 1.125rem;
            height: 1.125rem;
            color: #64748b;
            cursor: pointer;
            transition: color 0.3s ease;
        }
        .auth-eye:hover { color: #0f172a; }
        .dark .auth-eye { color: #64748b; }
        .dark .auth-eye:hover { color: #f8fafc; }

        /* ── Button ── */
        .auth-btn {
            width: 100%;
            height: 48px;
            border-radius: 12px;
            background: linear-gradient(135deg, #2563eb 0%, #06b6d4 100%);
            color: #ffffff;
            font-weight: 600;
            font-size: 0.875rem;
            border: none;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-top: 1.5rem;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.3);
        }
        .auth-btn::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(255,255,255,0.2) 0%, transparent 100%);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .auth-btn:hover {
            transform: scale(1.02);
            box-shadow: 0 6px 20px rgba(37, 99, 235, 0.4);
        }
        .auth-btn:hover::before { opacity: 1; }
        .auth-btn:active { transform: scale(0.98); }

        /* ── Theme Toggle ── */
        .theme-toggle {
            position: absolute;
            top: 1.25rem;
            right: 1.25rem;
            width: 38px;
            height: 38px;
            border-radius: 50%;
            background: transparent;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            color: #64748b;
            transition: all 0.3s ease;
            z-index: 20;
            border: none;
        }
        .theme-toggle:hover { 
            background: rgba(15, 23, 42, 0.05); 
            color: #0f172a;
        }
        .dark .theme-toggle {
            color: #94a3b8;
        }
        .dark .theme-toggle:hover { 
            background: rgba(255, 255, 255, 0.1); 
            color: #f8fafc;
        }

        /* ── Error Box ── */
        .error-box {
            background: rgba(239, 68, 68, 0.1);
            border: 1px solid rgba(239, 68, 68, 0.2);
            border-radius: 12px;
            padding: 1rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
        }
        .error-icon { color: #ef4444; width: 1.25rem; height: 1.25rem; flex-shrink: 0; margin-top: 0.125rem; }
        .error-text { color: #ef4444; font-size: 0.8125rem; margin: 0; line-height: 1.5; font-weight: 500;}
        .dark .error-box { background: rgba(239, 68, 68, 0.15); border-color: rgba(239, 68, 68, 0.25); }
        .dark .error-text { color: #fca5a5; }

        /* ── Mobile adjustments ── */
        @media (max-width: 480px) {
            .auth-card { padding: 2rem 1.5rem; }
            .auth-bg { padding: 1rem; }
            .theme-toggle { top: 1rem; right: 1rem; width: 38px; height: 38px; }
        }
        
    </style>
</head>
<body x-data="{
        dark: document.documentElement.classList.contains('dark'),
        toggle() {
            this.dark = !this.dark;
            if (this.dark) { document.documentElement.classList.add('dark'); localStorage.setItem('theme','dark'); }
            else { document.documentElement.classList.remove('dark'); localStorage.setItem('theme','light'); }
        }
     }">
     
    <div class="auth-bg">
        <!-- Floating shapes -->
        <div class="particle particle-1"></div>
        <div class="particle particle-2"></div>
        <div class="particle particle-3"></div>
        
        <!-- Center glow -->
        <div class="glow-center"></div>

        <div class="auth-card">
            <!-- Theme Toggle -->
            <button class="theme-toggle" @click="toggle" aria-label="Toggle Theme" title="Toggle Theme">
                <svg x-show="!dark" class="w-5 h-5 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
                <svg x-show="dark" x-cloak class="w-5 h-5 pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
            </button>
            @php $pengaturan = \App\Models\Pengaturan::first(); @endphp
            
            <div class="auth-logo shadow-lg">
                @if($pengaturan && $pengaturan->logo)
                    <img src="{{ asset('storage/'.$pengaturan->logo) }}" alt="Logo">
                @else
                    <span>{{ strtoupper(substr($pengaturan->nama_organisasi ?? 'O', 0, 1)) }}</span>
                @endif
            </div>

            <h1 class="auth-title">Welcome back</h1>
            <p class="auth-subtitle">Sign in to your workspace</p>

            @if ($errors->any())
                <div class="error-box">
                    <svg class="error-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <div>
                        @foreach ($errors->all() as $error)
                            <p class="error-text">{{ $error }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div>
                    <label class="auth-label">Email Address</label>
                    <div class="auth-input-group">
                        <input type="email" name="email" value="{{ old('email') }}" required autofocus
                               placeholder="name@company.com" class="auth-input">
                        <svg class="auth-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                    </div>
                </div>

                <div>
                    <label class="auth-label">Password</label>
                    <div class="auth-input-group" x-data="{ show: false }">
                        <input :type="show ? 'text' : 'password'" name="password" required
                               placeholder="••••••••" class="auth-input" style="padding-right: 2.75rem;">
                        <svg class="auth-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                        </svg>
                        
                        <!-- Toggle Password Visibility -->
                        <div @click="show = !show" class="auth-eye">
                            <!-- Eye Slash (Hidden) -->
                            <svg x-show="!show" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <!-- Eye (Visible) -->
                            <svg x-show="show" x-cloak fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1rem; margin-top: -0.5rem;">
                    <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer;">
                        <input type="checkbox" name="remember" style="accent-color: #3b82f6; width: 1rem; height: 1rem; border-radius: 4px;">
                        <span style="font-size: 0.8125rem; font-weight: 500; color: #64748b;" class="dark:text-slate-400 text-slate-500">Remember me</span>
                    </label>
                </div>

                <button type="submit" class="auth-btn">
                    Sign In
                </button>
            </form>
            
            <p style="text-align: center; font-size: 0.75rem; color: #64748b; margin-top: 2rem; opacity: 0.8;" class="dark:text-slate-500 text-slate-400">
                &copy; {{ date('Y') }} {{ $pengaturan->nama_organisasi ?? 'Organisasi' }}. Secure System.
            </p>
        </div>
    </div>
</body>
</html>
