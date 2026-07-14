@php
    $status = $link->getAvailabilityStatus();
    $isAvailable = $link->isAvailable();
@endphp
<div class="py-4 first:pt-0 last:pb-0 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
    
    <!-- Shift Details -->
    <div class="space-y-1 sm:max-w-md">
        <div class="flex items-center space-x-2">
            <span class="font-bold text-[var(--primary-dark)] text-sm">{{ $link->title }}</span>
            @if($link->is_featured)
                <span class="bg-[var(--coral)]/10 text-[var(--coral)] text-[8px] font-black uppercase px-2 py-0.5 rounded">Destacado</span>
            @endif
        </div>
        @if($link->description)
            <p class="text-xs text-gray-400 font-semibold leading-relaxed">{{ $link->description }}</p>
        @endif

        <!-- Contact info -->
        @if($link->contact_name || $link->contact_phone)
            <div class="flex flex-wrap gap-x-4 gap-y-1 text-[11px] text-gray-500 font-bold pt-1">
                @if($link->contact_name)
                    <span class="flex items-center">
                        <i data-lucide="user" class="h-3 w-3 mr-1 text-gray-400"></i>
                        Encargado: <strong class="text-gray-700 ml-1">{{ $link->contact_name }}</strong>
                    </span>
                @endif
                @if($link->contact_phone)
                    <span class="flex items-center">
                        <i data-lucide="phone" class="h-3 w-3 mr-1 text-gray-400"></i>
                        Contacto: <span class="text-gray-700 ml-1 font-mono">{{ $link->contact_phone }}</span>
                    </span>
                @endif
            </div>
        @endif
    </div>

    <!-- Join button -->
    <div class="self-start sm:self-center min-w-[140px] text-right">
        @if($isAvailable)
            <x-button 
                variant="{{ $link->is_featured ? 'secondary' : 'primary' }}" 
                href="{{ route('public.access', $link->slug) }}" 
                target="{{ $link->open_new_tab ? '_blank' : '_self' }}"
                class="text-xs w-full py-2 min-h-[38px] justify-center"
            >
                <i data-lucide="message-circle" class="h-4 w-4 mr-1.5"></i>
                {{ $link->button_text ?: 'Unirse al Grupo' }}
            </x-button>
        @else
            <span class="inline-block w-full text-center py-2 px-3 bg-gray-100 text-gray-400 font-bold text-xs rounded-xl border border-gray-200 cursor-not-allowed">
                {{ $link->status_label ?: 'Cupo Completo' }}
            </span>
        @endif
    </div>

</div>
