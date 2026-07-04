@props([
    'type' => 'default', // default, success, warning, danger, info
])

@php
    $baseClasses = 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold tracking-wide uppercase shadow-sm border';

    $typeClasses = match($type) {
        'success' => 'bg-emerald-50 text-emerald-700 border-emerald-200',
        'warning' => 'bg-amber-50 text-amber-700 border-amber-200',
        'danger' => 'bg-rose-50 text-rose-700 border-rose-200',
        'info' => 'bg-sky-50 text-sky-700 border-sky-200',
        'primary' => 'bg-indigo-50 text-indigo-700 border-indigo-200',
        default => 'bg-slate-50 text-slate-700 border-slate-200',
    };
@endphp

<span {{ $attributes->merge(['class' => $baseClasses . ' ' . $typeClasses]) }}>
    {{ $slot }}
</span>
