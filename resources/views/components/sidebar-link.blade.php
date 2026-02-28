@props(['route', 'icon', 'label', 'badge' => null])
@php
    $isActive = request()->routeIs($route . '*');
@endphp
<a href="{{ route($route) }}"
   {{ $attributes->merge(['class' => 'flex items-center gap-3 px-3 py-2 rounded-lg font-bold text-[13px] transition-all duration-200 group relative ' . ($isActive ? 'bg-brand text-white shadow-lg shadow-brand/20' : 'text-slate-600 dark:text-slate-400 hover:bg-slate-100 dark:hover:bg-slate-800 hover:text-slate-900 dark:hover:text-white')]) }}>
    <svg class="h-4 w-4 flex-shrink-0 {{ $isActive ? 'text-white' : 'text-slate-400 group-hover:text-slate-600 dark:group-hover:text-slate-300' }}" 
         fill="none" 
         stroke="currentColor" 
         viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $icon }}" />
    </svg>
    <span class="flex-1">{{ $label }}</span>
    
    @if($badge)
        <div class="shrink-0 flex items-center justify-center">
            {!! $badge !!}
        </div>
    @endif

    @if($isActive)
        <div class="absolute -left-1 top-2 bottom-2 w-1.5 bg-white rounded-r-full opacity-50"></div>
    @endif
</a>
