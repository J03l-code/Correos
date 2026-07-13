@props([
    'variant' => 'primary', // primary, secondary, success, danger, outline
    'type' => 'button',
    'href' => null,
])

@php
    $baseStyles = "inline-flex items-center justify-center font-semibold transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-offset-2 active:scale-95 cursor-pointer min-h-[44px] px-6 py-2.5";
    
    // Fetch custom buttons styles from Setting/CSS variables
    $btnStyleClass = \App\Models\Setting::get('button_style', 'rounded-xl');

    $variants = [
        'primary' => 'bg-[var(--primary)] text-white hover:bg-[var(--primary-dark)] focus:ring-[var(--primary)]',
        'secondary' => 'bg-[var(--coral)] text-white hover:bg-[var(--coral-dark, #ED4654)] focus:ring-[var(--coral)]',
        'success' => 'bg-emerald-600 text-white hover:bg-emerald-700 focus:ring-emerald-500',
        'danger' => 'bg-rose-600 text-white hover:bg-rose-700 focus:ring-rose-500',
        'outline' => 'border-2 border-[var(--primary)] text-[var(--primary)] hover:bg-[var(--primary)] hover:text-white focus:ring-[var(--primary)]',
    ];

    $classes = $baseStyles . " " . ($variants[$variant] ?? $variants['primary']) . " " . $btnStyleClass;
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        {{ $slot }}
    </button>
@endif
