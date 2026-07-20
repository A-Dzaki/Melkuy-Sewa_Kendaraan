@props([
    'type' => 'button',
    'variant' => 'primary', // primary, secondary, outline, danger
    'size' => 'md', // sm, md, lg
    'href' => null,
])

@php
    $baseClasses =
        'inline-flex items-center justify-center font-semibold rounded-xl transition-all duration-300 focus:outline-none focus:ring-2 focus:ring-offset-2 disabled:opacity-50 disabled:cursor-not-allowed transform hover:-translate-y-0.5 active:scale-95';

    $sizeClasses = match ($size) {
        'sm' => 'px-3 py-1.5 text-xs',
        'md' => 'px-5 py-2.5 text-sm',
        'lg' => 'px-6 py-3 text-base',
        default => 'px-5 py-2.5 text-sm',
    };

    $variantClasses = match ($variant) {
        'primary'
            => 'bg-[#ff8e3c] text-white hover:bg-[#ff8e3c]/90 hover:shadow-lg hover:shadow-[#ff8e3c]/30 focus:ring-[#ff8e3c] border border-transparent',
        'secondary'
            => 'bg-slate-900 text-white hover:bg-slate-800 hover:shadow-lg focus:ring-slate-900 border border-transparent',
        'outline'
            => 'bg-white text-slate-700 border border-slate-300 hover:bg-slate-50 hover:text-[#ff8e3c] focus:ring-[#ff8e3c] shadow-sm',
        'danger'
            => 'bg-rose-600 text-white hover:bg-rose-700 hover:shadow-lg hover:shadow-rose-500/30 focus:ring-rose-500 border border-transparent',
        default => 'bg-indigo-600 text-white hover:bg-indigo-700',
    };
@endphp

@if ($href)
    <a href="{{ $href }}"
        {{ $attributes->merge(['class' => $baseClasses . ' ' . $sizeClasses . ' ' . $variantClasses]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}"
        {{ $attributes->merge(['class' => $baseClasses . ' ' . $sizeClasses . ' ' . $variantClasses]) }}>
        {{ $slot }}
    </button>
@endif
