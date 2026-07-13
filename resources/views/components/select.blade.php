@props([
    'label' => null,
    'name',
    'selected' => null,
    'required' => false,
    'help' => null,
    'options' => [], // Associative array or simple array
])

<div class="mb-4">
    @if($label)
        <label for="{{ $name }}" class="block text-sm font-semibold text-gray-700 mb-1.5 flex items-center">
            {{ $label }}
            @if($required)
                <span class="text-rose-500 ml-1">*</span>
            @endif
        </label>
    @endif
    <select 
        id="{{ $name }}" 
        name="{{ $name }}" 
        {{ $required ? 'required' : '' }}
        {{ $attributes->merge(['class' => 'w-full px-4 py-2.5 rounded-xl border border-gray-300 bg-white text-gray-800 font-medium focus:outline-none focus:ring-2 focus:ring-[var(--primary)] focus:border-[var(--primary)] transition-all duration-200 min-h-[44px]']) }}
    >
        {{ $slot }}
    </select>
    @if($help)
        <p class="mt-1.5 text-xs text-gray-500 font-medium">{{ $help }}</p>
    @endif
    @error($name)
        <p class="mt-1.5 text-sm text-rose-600 font-semibold">{{ $message }}</p>
    @enderror
</div>
