@props([
    'title',
    'id',
])

<div x-data="{ open: false }" class="border border-gray-200 rounded-xl overflow-hidden mb-3 bg-white transition-all duration-200">
    <button 
        type="button"
        @click="open = !open"
        :aria-expanded="open ? 'true' : 'false'"
        aria-controls="accordion-content-{{ $id }}"
        class="w-full px-6 py-4 text-left flex items-center justify-between font-semibold text-[var(--primary)] hover:bg-gray-50 focus:outline-none focus:bg-gray-50 transition-all duration-150"
    >
        <span class="font-montserrat leading-relaxed">{{ $title }}</span>
        <svg 
            class="h-5 w-5 text-gray-500 transform transition-transform duration-200"
            :class="{ 'rotate-180': open }"
            fill="none" 
            viewBox="0 0 24 24" 
            stroke="currentColor"
        >
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
        </svg>
    </button>

    <div 
        id="accordion-content-{{ $id }}"
        x-show="open"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 max-h-0"
        x-transition:enter-end="opacity-100 max-h-[1000px]"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 max-h-[1000px]"
        x-transition:leave-end="opacity-0 max-h-0"
        class="px-6 pb-5 pt-0 text-gray-600 leading-relaxed font-medium"
        style="display: none;"
    >
        {{ $slot }}
    </div>
</div>
