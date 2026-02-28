@props([
    'title' => null,
    'footer' => null,
    'padding' => 'p-6',
    'class' => ''
])

<div {{ $attributes->merge(['class' => "card-premium flex flex-col $class"]) }}>
    @if($title)
        <div class="px-6 py-4 border-b border-slate-100 dark:border-slate-800">
            <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-wider">{{ $title }}</h3>
        </div>
    @endif

    <div class="flex-1 {{ $padding }}">
        {{ $slot }}
    </div>

    @if(isset($footerSlot))
        <div class="px-6 py-4 bg-slate-50/50 dark:bg-slate-800/50 border-t border-slate-100 dark:border-slate-800 rounded-b-premium">
            {{ $footerSlot }}
        </div>
    @elseif($footer)
        <div class="px-6 py-4 bg-slate-50/50 dark:bg-slate-800/50 border-t border-slate-100 dark:border-slate-800 rounded-b-premium">
            {!! $footer !!}
        </div>
    @endif
</div>
