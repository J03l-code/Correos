@extends('layouts.admin')

@section('title', 'Código QR')

@section('breadcrumbs')
    <span class="text-gray-400">Panel</span> / <a href="{{ route('links.index') }}" class="text-gray-400 hover:text-[var(--primary)]">Enlaces</a> / <span class="text-[var(--primary)]">Código QR</span>
@endsection

@section('content')
<div class="max-w-md mx-auto space-y-6">
    
    <!-- Printable Card -->
    <div id="qr-print-area" class="bg-white rounded-3xl border border-gray-100 p-8 shadow-xl text-center space-y-6 flex flex-col items-center">
        <!-- Brand header for print -->
        <div class="hidden print:block w-full text-center border-b border-gray-200 pb-4 mb-4">
            <h1 class="text-2xl font-black text-[#062B63] font-montserrat">QUITO 2026</h1>
            <p class="text-xs text-gray-500 font-bold uppercase tracking-widest mt-1">Portal Oficial de Accesos</p>
        </div>

        <div class="space-y-1 text-center">
            <h2 class="text-lg font-extrabold text-[var(--primary-dark)] font-montserrat">Código QR Oficial</h2>
            <p class="text-[11px] text-gray-400 font-bold uppercase tracking-wider text-[var(--coral)]">{{ $link->title }}</p>
        </div>

        <!-- QR Code Image using public API -->
        @php
            $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=" . urlencode($publicUrl);
        @endphp
        
        <div class="p-4 bg-white border border-gray-200 rounded-3xl shadow-sm inline-block">
            <img src="{{ $qrUrl }}" alt="Código QR para {{ $link->title }}" class="w-64 h-64 object-contain">
        </div>

        <div class="text-center space-y-1 max-w-xs">
            <p class="text-xs text-gray-500 font-semibold leading-relaxed">
                Este QR apunta a la URL intermedia del portal:
            </p>
            <p class="text-[10px] text-[var(--primary)] font-mono font-bold break-all bg-gray-50 p-2.5 rounded-lg border border-gray-100 select-all">
                {{ $publicUrl }}
            </p>
        </div>

        <div class="text-[10px] text-gray-400 font-semibold max-w-xs italic leading-relaxed">
            Puedes cambiar el destino real (grupo de WhatsApp, etc.) en el panel en cualquier momento sin necesidad de reimprimir este código QR.
        </div>
    </div>

    <!-- Actions Buttons (Hidden during printing) -->
    <div class="flex flex-col gap-3 print:hidden">
        <x-button variant="primary" onclick="window.print()" class="w-full text-xs">
            <i data-lucide="printer" class="h-4 w-4 mr-2"></i>
            Imprimir Código QR
        </x-button>

        <x-button variant="outline" href="{{ $qrUrl }}&download=1" download="qr_quito2026_{{ $link->slug }}.png" target="_blank" class="w-full text-xs">
            <i data-lucide="download" class="h-4 w-4 mr-2"></i>
            Descargar Imagen PNG
        </x-button>

        <a href="{{ route('links.index') }}" class="text-center text-xs text-gray-500 font-bold hover:underline py-2">
            Volver a la lista de enlaces
        </a>
    </div>

</div>

<!-- Styles to target clean printing of the QR card -->
<style>
    @media print {
        /* Hide all page layouts, sidebar, headers */
        body *, aside, header, main, .print\:hidden {
            display: none !important;
        }
        /* Only display print area */
        #qr-print-area, #qr-print-area * {
            display: flex !important;
            visibility: visible !important;
        }
        #qr-print-area {
            position: absolute;
            left: 50%;
            top: 50%;
            transform: translate(-50%, -50%);
            border: none !important;
            box-shadow: none !important;
            width: 100% !important;
        }
    }
</style>
@endsection
