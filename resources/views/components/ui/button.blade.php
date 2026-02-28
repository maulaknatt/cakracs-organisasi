@props([
    'variant'  => 'primary',
    'size'     => 'md',
    'type'     => 'button',
    'icon'     => null,
    'loading'  => false,
    'disabled' => false
])

@php
$variants = [
    'primary'   => 'bg-blue-600 hover:bg-blue-700 text-white border border-blue-600/80 shadow-sm hover:shadow-blue-500/20 hover:shadow-md',
    'secondary' => 'bg-white dark:bg-white/6 text-slate-700 dark:text-slate-300 border border-slate-200 dark:border-white/10 hover:bg-slate-50 dark:hover:bg-white/10 shadow-sm',
    'danger'    => 'bg-red-600 hover:bg-red-700 text-white border border-red-600/80 shadow-sm',
    'ghost'     => 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-white/8 hover:text-slate-900 dark:hover:text-white',
    'success'   => 'bg-emerald-600 hover:bg-emerald-700 text-white border border-emerald-600/80 shadow-sm',
];
$sizes = [
    'xs' => 'px-2.5 py-1 text-[11px] rounded-lg',
    'sm' => 'px-3 py-1.5 text-xs rounded-[8px]',
    'md' => 'px-4 py-2 text-sm rounded-[10px]',
    'lg' => 'px-5 py-2.5 text-sm rounded-[10px]',
];
$base = 'inline-flex items-center justify-center gap-2 font-semibold transition-all duration-150 active:scale-[.97] disabled:opacity-40 disabled:pointer-events-none';
$cls  = $base . ' ' . ($variants[$variant] ?? $variants['primary']) . ' ' . ($sizes[$size] ?? $sizes['md']);
@endphp

<button {{ $attributes->merge(['type' => $type, 'class' => $cls, 'disabled' => $disabled || $loading]) }}>
    @if($loading)
        <svg class="animate-spin h-3.5 w-3.5 flex-shrink-0" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/>
        </svg>
    @elseif($icon)
        <div class="h-4 w-4 flex-shrink-0">{!! $icon !!}</div>
    @endif
    <span>{{ $slot }}</span>
</button>
