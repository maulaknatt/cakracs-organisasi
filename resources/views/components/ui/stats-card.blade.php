@props([
    'label',
    'value',
    'icon'    => null,
    'trend'   => null,
    'trendUp' => true,
    'color'   => 'blue'
])

@php
$colorMap = [
    'blue'    => ['icon' => 'bg-blue-500/10 text-blue-400 border-blue-500/20',    'glow' => '#3b82f6'],
    'emerald' => ['icon' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20', 'glow' => '#10b981'],
    'amber'   => ['icon' => 'bg-amber-500/10 text-amber-400 border-amber-500/20',  'glow' => '#f59e0b'],
    'purple'  => ['icon' => 'bg-purple-500/10 text-purple-400 border-purple-500/20', 'glow' => '#8b5cf6'],
    'red'     => ['icon' => 'bg-red-500/10 text-red-400 border-red-500/20',        'glow' => '#ef4444'],
];
$c = $colorMap[$color] ?? $colorMap['blue'];
@endphp

<div {{ $attributes->merge(['class' => 'card-premium p-5 flex items-center justify-between gap-4 group']) }}
     style="transition: border-color .2s, box-shadow .2s;">
    <div class="min-w-0">
        <p class="text-[10px] font-black text-slate-500 dark:text-slate-500 uppercase tracking-widest mb-2 leading-none">{{ $label }}</p>
        <h4 class="text-2xl font-black text-slate-900 dark:text-white leading-none">{{ $value }}</h4>

        @if($trend)
        <div class="flex items-center gap-1.5 mt-2">
            <span @class([
                'inline-flex items-center gap-0.5 text-[10px] font-bold px-1.5 py-0.5 rounded-full',
                'bg-emerald-500/10 text-emerald-500' => $trendUp,
                'bg-red-500/10 text-red-500' => !$trendUp,
            ])>
                @if($trendUp)
                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
                @else
                    <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                @endif
                {{ $trend }}
            </span>
            <span class="text-[10px] text-slate-500 font-medium">vs bulan lalu</span>
        </div>
        @endif
    </div>

    @if($icon)
    <div class="h-11 w-11 rounded-xl border flex-shrink-0 flex items-center justify-center {{ $c['icon'] }}">
        <div class="h-5 w-5">{!! $icon !!}</div>
    </div>
    @endif
</div>
