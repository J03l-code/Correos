@props([
    'variant' => 'info', // info, success, warning, danger, primary, secondary
])

@php
    $baseStyles = "inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold uppercase tracking-wider";
    
    $variants = [
        'info' => 'bg-sky-100 text-sky-800',
        'success' => 'bg-emerald-100 text-emerald-800',
        'warning' => 'bg-amber-100 text-amber-800',
        'danger' => 'bg-rose-100 text-rose-800',
        'primary' => 'bg-blue-100 text-blue-800',
        'secondary' => 'bg-orange-100 text-orange-800',
    ];

    $classes = $baseStyles . " " . ($variants[$variant] ?? $variants['info']);
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>
