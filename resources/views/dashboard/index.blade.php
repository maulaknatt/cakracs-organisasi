<x-admin-layout title="Overview">

<div class="flex flex-col gap-5">


    {{-- ═══ STAT CARDS ═══ --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
        @php
            $stats = [
                ['label' => 'Program Kerja', 'value' => \App\Models\Kegiatan::count(), 'icon' => 'briefcase', 'color' => 'indigo', 'delay' => '100ms', 'glow' => 'indigo'],
                ['label' => 'Total Anggota', 'value' => \App\Models\User::count(), 'icon' => 'users', 'color' => 'emerald', 'delay' => '200ms', 'glow' => 'emerald'],
                ['label' => 'Tasks Aktif', 'value' => ($totalTugas - $selesaiTugas), 'icon' => 'check-circle', 'color' => 'amber', 'delay' => '300ms', 'glow' => 'amber'],
                ['label' => 'Total Arsip', 'value' => \App\Models\Arsip::count(), 'icon' => 'archive', 'color' => 'purple', 'delay' => '400ms', 'glow' => 'purple'],
            ];
        @endphp

        @foreach($stats as $stat)
            <div class="card hover-lift !p-5 animate-fade-in-up stats-glow-{{ $stat['glow'] }} group" style="animation-delay: {{ $stat['delay'] }}">
                <div class="flex items-start gap-4 relative z-10">
                    {{-- Left Column: Icon & Label --}}
                    <div class="flex flex-col items-center gap-3">
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center bg-{{ $stat['color'] }}-500/10 text-{{ $stat['color'] }}-600 dark:text-{{ $stat['color'] }}-400 transition-all duration-300 group-hover:scale-110 group-hover:bg-{{ $stat['color'] }}-500/20">
                            @if($stat['icon'] == 'briefcase')
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><rect x="2" y="7" width="20" height="14" rx="2" ry="2"/><path d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                            @elseif($stat['icon'] == 'users')
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                            @elseif($stat['icon'] == 'check-circle')
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><rect x="8" y="2" width="8" height="4" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="m9 14 2 2 4-4"/></svg>
                            @else
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><rect x="2" y="4" width="20" height="5" rx="1" ry="1"/><path d="M4 9v9a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9"/><path d="M10 13h4"/></svg>
                            @endif
                        </div>
                        <span class="text-[10px] font-black text-slate-400 dark:text-slate-500 uppercase tracking-widest leading-none">Total</span>
                    </div>

                    {{-- Right Column: Title & Main Value --}}
                    <div class="flex-1 pt-1.5 flex flex-col justify-between h-full min-h-[70px]">
                        <div>
                            <span class="block text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em] leading-tight mb-1 line-clamp-1">{{ $stat['label'] }}</span>
                            <div class="h-0.5 w-6 rounded-full bg-{{ $stat['color'] }}-500/20 transition-all duration-300 group-hover:w-10 group-hover:bg-{{ $stat['color'] }}-500/50"></div>
                        </div>
                        
                        <div class="mt-auto">
                            <span class="text-3xl font-black text-slate-900 dark:text-white tracking-tighter">{{ $stat['value'] }}</span>
                        </div>
                    </div>
                </div>

                {{-- Decorative Glow --}}
                <div class="absolute -top-6 -right-6 w-24 h-24 bg-{{ $stat['color'] }}-500/5 blur-3xl rounded-full transition-transform duration-700 group-hover:scale-150"></div>
            </div>
        @endforeach
    </div>

    {{-- ═══ MAIN GRID ═══ --}}
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-stretch">

        {{-- Program Aktif --}}
        <div class="lg:col-span-5 xl:col-span-4 animate-fade-in-up" style="animation-delay: 500ms">
            <div class="card-glass h-full p-8 flex flex-col justify-between border-blue-500/20 dark:border-blue-400/10 relative overflow-hidden group">
                <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-500/20 dark:bg-blue-500/10 rounded-full blur-3xl group-hover:bg-blue-500/30 transition-all duration-700 group-hover:scale-110"></div>
                <div class="absolute -bottom-24 -left-24 w-64 h-64 bg-indigo-500/10 dark:bg-indigo-500/5 rounded-full blur-3xl group-hover:bg-indigo-500/20 transition-all duration-700"></div>
                
                @if($acaraAktif)
                    <div class="relative z-10">
                        <div class="flex items-center justify-between mb-6">
                            <span class="px-3 py-1 rounded-lg bg-blue-100 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 text-[11px] font-black uppercase tracking-[0.2em]">Live Program</span>
                            <span class="flex h-3 w-3 rounded-full bg-green-500 shadow-[0_0_10px_rgba(34,197,94,0.5)]"></span>
                        </div>
                        <h3 class="text-2xl font-black leading-[1.15] mb-2 text-slate-900 dark:text-white tracking-tight group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">
                            {{ $acaraAktif->judul }}
                        </h3>
                        <p class="text-[13px] font-medium text-slate-500 dark:text-slate-400 mb-6 inline-flex items-center gap-2">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            {{ \Carbon\Carbon::parse($acaraAktif->tanggal_mulai)->format('d M') }} — {{ \Carbon\Carbon::parse($acaraAktif->tanggal_selesai)->format('d M Y') }}
                        </p>
                        
                        <div class="space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-bold text-slate-600 dark:text-slate-300">Target Progress</span>
                                <span class="text-sm font-black text-blue-600 dark:text-blue-400">{{ (int)($acaraAktif->progress ?? 0) }}%</span>
                            </div>
                            <div class="h-3 rounded-full overflow-hidden bg-slate-100 dark:bg-slate-800/50 p-0.5 border border-slate-200 dark:border-white/5">
                                <div class="h-full rounded-full transition-all duration-1000 bg-gradient-to-r from-blue-600 to-indigo-500 shadow-[0_0_15px_rgba(59,130,246,0.3)]"
                                     style="width:{{ (int)($acaraAktif->progress ?? 0) }}%;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-8 relative z-10 pt-2">
                        <a href="{{ route('kegiatan.show', $acaraAktif->id) }}" class="flex items-center justify-center w-full px-6 py-3.5 rounded-2xl font-bold text-[14px] transition-all bg-blue-600 hover:bg-blue-700 text-white shadow-lg shadow-blue-500/25 hover:shadow-blue-600/30 hover:-translate-y-1 active:scale-[0.98]">
                            Pantau Workspace &nbsp; <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                        </a>
                    </div>
                @else
                    <div class="relative z-10 py-10 text-center">
                        <div class="w-16 h-16 bg-slate-100 dark:bg-white/5 rounded-2xl flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <h4 class="text-lg font-bold text-slate-900 dark:text-white mb-2">Fokus Baru</h4>
                        <p class="text-sm text-slate-500">Belum ada program yang berjalan saat ini.</p>
                    </div>
                @endif
            </div>
        </div>

        {{-- Kondisi Kas --}}
        <div class="lg:col-span-7 xl:col-span-8 grid grid-cols-1 md:grid-cols-2 gap-8">
            {{-- Saldo Card --}}
            <div class="card-glass p-7 flex flex-col justify-between border-emerald-500/10 dark:border-emerald-400/10 animate-fade-in-up relative overflow-hidden group" style="animation-delay: 600ms">
                <div class="absolute -top-24 -right-24 w-48 h-48 bg-emerald-500/10 dark:bg-emerald-500/5 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path d="M12 1v22M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg>
                            </div>
                            <span class="text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em]">Saldo Keuangan</span>
                        </div>
                    </div>
                    <div class="text-4xl font-black mb-1.5 leading-none tracking-tight {{ $saldoTotal < 0 ? 'text-red-500' : 'text-slate-900 dark:text-white' }}">
                        <span class="text-xl opacity-50 font-black mr-1 uppercase">Rp</span>{{ number_format(abs($saldoTotal), 0, ',', '.') }}
                    </div>
                    <p class="text-[13px] font-medium text-slate-500 dark:text-slate-400">Total kas organisasi saat ini</p>
                </div>
                <div class="mt-8">
                    <a href="{{ route('keuangan.index') }}" class="group flex items-center justify-between w-full px-5 py-3.5 rounded-2xl font-bold text-[14px] transition-all border border-slate-200 dark:border-white/5 bg-slate-50/50 dark:bg-white/5 hover:bg-white dark:hover:bg-white/10 text-slate-700 dark:text-slate-200 shadow-sm hover:shadow-md">
                        <span>Laporan Detail</span>
                        <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                    </a>
                </div>
            </div>

            {{-- Anggota Online / Activity --}}
            <div class="card-glass p-7 border-slate-200 dark:border-white/5 animate-fade-in-up flex flex-col relative overflow-hidden group" style="animation-delay: 700ms">
                <div class="absolute -bottom-24 -right-24 w-48 h-48 bg-blue-500/10 dark:bg-blue-500/5 rounded-full blur-3xl group-hover:scale-150 transition-transform duration-700"></div>
                <div class="flex items-center justify-between mb-6 relative z-10">
                    <span class="text-[11px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-[0.2em]">Kolaborasi</span>
                    <span class="px-2 py-1 rounded-md text-[9px] font-black uppercase tracking-wider bg-green-500/10 text-green-600 border border-green-500/20">Aktif</span>
                </div>
                
                @if($onlineUsers->count())
                    <div class="flex-1 flex flex-col justify-between">
                        <div class="flex flex-wrap gap-2.5 mb-6">
                            @foreach($onlineUsers as $online)
                                <div class="relative group" title="{{ $online->name }}">
                                    <div class="w-11 h-11 rounded-full ring-2 ring-white dark:ring-slate-800 transition-all group-hover:ring-blue-500 group-hover:scale-110 overflow-hidden bg-blue-600 shadow-sm">
                                        @if($online->foto_profil)
                                            <img src="{{ asset('storage/'.$online->foto_profil) }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-xs font-black text-white">
                                                {{ strtoupper(substr($online->name, 0, 1)) }}
                                            </div>
                                        @endif
                                    </div>
                                    <span class="absolute bottom-0 right-0 w-3.5 h-3.5 rounded-full border-[3.5px] border-white dark:border-slate-800 bg-green-500 shadow-sm"></span>
                                </div>
                            @endforeach
                        </div>
                        <p class="text-[13px] font-medium text-slate-500 dark:text-slate-400 leading-snug">
                            <span class="text-slate-900 dark:text-white font-black">{{ $onlineUsers->count() }} Anggota</span> sedang online di workspace ini.
                        </p>
                    </div>
                @else
                    <div class="py-6 text-center">
                        <div class="w-12 h-12 bg-slate-50 dark:bg-white/5 rounded-xl flex items-center justify-center mx-auto mb-3">
                            <svg class="w-6 h-6 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                        </div>
                        <p class="text-sm font-medium text-slate-400">Belum ada aktivitas baru</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- ═══ BOTTOM GRID ═══ --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">

        {{-- Task Produktivitas --}}
        <div class="card hover-lift overflow-hidden animate-fade-in-up relative group" style="animation-delay: 800ms">
            <div class="absolute right-0 top-0 w-64 h-64 bg-slate-100/50 dark:bg-white/5 rounded-full blur-3xl -mr-32 -mt-32 transition-transform duration-700 group-hover:scale-110 pointer-events-none"></div>
            <div class="flex items-center justify-between mb-6 relative z-10">
                <h3 class="text-lg font-black text-slate-900 dark:text-white">Statistik Tugas</h3>
                <a href="{{ route('tugas.index') }}" class="text-xs font-black uppercase tracking-widest text-blue-500 hover:text-blue-600 transition-colors">Task Manager &rarr;</a>
            </div>
            
            <div class="flex flex-col sm:flex-row items-center gap-10 relative z-10">
                <div class="relative h-44 w-44 shrink-0 flex items-center justify-center">
                    <canvas id="tugasSummaryChart" class="relative z-10"></canvas>
                    <div class="absolute inset-0 flex flex-col items-center justify-center">
                        <span class="text-2xl font-black text-slate-900 dark:text-white tracking-tighter">{{ $progressPercentage }}%</span>
                        <span class="text-[9px] font-black uppercase tracking-[0.2em] text-slate-400">Selesai</span>
                    </div>
                </div>
                
                <div class="flex-1 w-full flex flex-col gap-5">
                    <div class="p-5 rounded-2xl bg-white dark:bg-white/4 border border-slate-100 dark:border-white/5 flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-blue-500/10 text-blue-500 flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            <span class="text-sm font-bold text-slate-600 dark:text-slate-300">Tugas Selesai</span>
                        </div>
                        <span class="text-2xl font-black text-slate-900 dark:text-white">{{ $selesaiTugas }}</span>
                    </div>
                    
                    <div class="p-5 rounded-2xl bg-white dark:bg-white/4 border border-slate-100 dark:border-white/5 flex items-center justify-between shadow-sm">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-xl bg-slate-100 dark:bg-slate-800 flex items-center justify-center text-slate-400">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <span class="text-sm font-bold text-slate-600 dark:text-slate-300">Masih Tertunda</span>
                        </div>
                        <span class="text-2xl font-black text-slate-900 dark:text-white">{{ $belumSelesaiTugas }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pengumuman Terbaru --}}
        <div class="card hover-lift flex flex-col animate-fade-in-up relative group" style="animation-delay: 900ms">
            <div class="absolute left-0 bottom-0 w-64 h-64 bg-slate-100/50 dark:bg-white/5 rounded-full blur-3xl -ml-32 -mb-32 transition-transform duration-700 group-hover:scale-110 pointer-events-none"></div>
            <div class="flex items-center justify-between mb-6 relative z-10">
                <h3 class="text-lg font-black text-slate-900 dark:text-white">Pengumuman</h3>
                <a href="{{ route('pengumuman.index') }}" class="text-xs font-black uppercase tracking-widest text-blue-500 hover:text-blue-600 transition-colors">Lihat Semua &rarr;</a>
            </div>
            
            <div class="relative z-10 flex-1 flex flex-col">
            @if($pengumuman->count())
                <div class="flex-1 flex flex-col justify-center gap-5">
                    @foreach($pengumuman->take(3) as $item)
                        <a href="{{ route('pengumuman.show', $item->id) }}" class="flex items-center gap-5 p-5 rounded-2xl bg-white/5 dark:bg-white/4 border border-slate-100/5 dark:border-white/5 hover:bg-slate-50 dark:hover:bg-white/10 transition-all group shadow-sm">
                            <div class="w-14 h-14 rounded-2xl bg-blue-500/10 flex flex-col items-center justify-center text-blue-500 transition-colors">
                                <span class="text-[10px] font-black uppercase leading-none opacity-60">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('M') }}</span>
                                <span class="text-xl font-black leading-none mt-1.5">{{ \Carbon\Carbon::parse($item->tanggal)->format('d') }}</span>
                            </div>
                            <div class="flex-1 min-w-0">
                                <h4 class="text-[16px] font-bold text-slate-900 dark:text-white truncate group-hover:text-blue-500 transition-colors mb-1">{{ $item->judul }}</h4>
                                <p class="text-[13px] text-slate-500 dark:text-slate-400 font-medium">Pengumuman Resmi</p>
                            </div>
                            <div class="w-9 h-9 rounded-xl flex items-center justify-center text-slate-300 group-hover:text-blue-500 group-hover:bg-blue-500/10 transition-all">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </div>
                        </a>
                    @endforeach
                </div>
            @else
                <div class="flex-1 flex flex-col items-center justify-center py-10 opacity-50">
                    <svg class="w-12 h-12 text-slate-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/></svg>
                    <p class="text-sm font-bold text-slate-400">Tidak ada pengumuman baru</p>
                </div>
            @endif
            </div>
        </div>
</div>

@push('scripts')
<script>
document.addEventListener('turbo:load', function() {
    const canvas = document.getElementById('tugasSummaryChart');
    if (!canvas) return;
    
    if (typeof Chart === 'undefined') {
        console.warn('Dashboard: Chart.js not loaded yet.');
        return;
    }

    const ctx = canvas.getContext('2d');
    
    // Destroy existing chart instance if it exists to prevent 'Canvas is already in use' error
    let existingChart = Chart.getChart(canvas);
    if (existingChart) {
        existingChart.destroy();
    }
    
    // Function to get colors based on theme
    const getColors = () => {
        const isDark = document.documentElement.classList.contains('dark');
        return {
            primary: '#3b82f6',
            track: isDark ? 'rgba(255, 255, 255, 0.05)' : 'rgba(0, 0, 0, 0.05)',
            border: 'transparent'
        };
    };

    let colors = getColors();
    
    const config = {
        type: 'doughnut',
        data: {
            labels: ['Selesai', 'Sisa'],
            datasets: [{
                data: [{{ $progressPercentage }}, {{ 100 - $progressPercentage }}],
                backgroundColor: [colors.primary, colors.track],
                borderColor: colors.border,
                borderWidth: 0,
                hoverOffset: 0,
                borderRadius: 10,
                spacing: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '80%',
            plugins: { 
                legend: { display: false }, 
                tooltip: { enabled: false } 
            },
            animation: {
                duration: 2000,
                easing: 'easeOutQuart'
            }
        }
    };

    const chart = new Chart(ctx, config);

    // Watch for theme changes to update chart
    const themeHandler = (e) => {
        if (!Chart.getChart(canvas)) {
            window.removeEventListener('theme-changed', themeHandler);
            return;
        }
        const newColors = getColors();
        chart.data.datasets[0].backgroundColor = [newColors.primary, newColors.track];
        chart.update();
    };

    window.addEventListener('theme-changed', themeHandler);
});
</script>
@endpush

<style>
    @keyframes bounce-subtle {
        0%, 100% { transform: translateY(0) rotate(0); }
        50% { transform: translateY(-5px) rotate(10deg); }
    }
    .animate-bounce-subtle {
        animation: bounce-subtle 2s ease-in-out infinite;
    }
</style>

</x-admin-layout>
