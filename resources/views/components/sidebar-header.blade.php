@props(['label'])
<div {{ $attributes->merge(['class' => 'px-4 mt-6 mb-2']) }}>
    <span class="text-[10px] font-bold uppercase tracking-[0.2em] text-slate-400 dark:text-slate-500">
        {{ $label }}
    </span>
</div>
