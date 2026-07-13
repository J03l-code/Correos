@extends('layouts.admin')

@section('title', 'Gestor de Medios')

@section('breadcrumbs')
    <span class="text-gray-400">Panel</span> / <span class="text-[var(--primary)]">Medios</span>
@endsection

@section('content')
<div class="space-y-8">
    
    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-extrabold text-[var(--primary-dark)] font-montserrat">
                Biblioteca de Medios
            </h1>
            <p class="text-xs text-gray-500 font-semibold mt-1">
                Sube imágenes para tus secciones, logos y tarjetas de enlaces de forma segura.
            </p>
        </div>
        
        <!-- Upload Form inline -->
        <form action="{{ route('media.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-wrap items-center gap-3">
            @csrf
            <div class="flex items-center space-x-2 bg-white border border-gray-200 rounded-xl px-3 py-1.5 shadow-sm text-xs font-bold">
                <input type="file" name="file" required class="cursor-pointer file:bg-transparent file:border-none file:text-[var(--primary)] file:font-bold">
            </div>
            
            <div class="w-40">
                <input type="text" name="alt_text" placeholder="Texto Alternativo" class="w-full text-xs px-3 py-2 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-1 focus:ring-[var(--primary)]">
            </div>

            <x-button type="submit" variant="primary" class="text-xs">
                <i data-lucide="upload" class="h-4.5 w-4.5 mr-1.5"></i>
                Subir
            </x-button>
        </form>
    </div>

    <!-- MEDIA GRID -->
    <div class="bg-white rounded-2xl border border-gray-100 p-6 shadow-sm">
        @if($mediaFiles->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-6 gap-6">
                @foreach($mediaFiles as $file)
                    <div class="relative bg-gray-50 rounded-2xl border border-gray-100 overflow-hidden shadow-sm flex flex-col group">
                        
                        <!-- Media Preview -->
                        <div class="relative aspect-square flex items-center justify-center bg-gray-100 overflow-hidden">
                            <img src="{{ Storage::url($file->path) }}" alt="{{ $file->alt_text }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                            
                            <!-- Delete button (overlay on hover) -->
                            @if(auth()->user()->role !== 'visualizer')
                                <form action="{{ route('media.destroy', $file->id) }}" method="POST" class="absolute top-2 right-2 opacity-0 group-hover:opacity-100 transition-opacity">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-1.5 bg-rose-600/90 text-white rounded-lg hover:bg-rose-700 transition-all focus:outline-none" title="Eliminar de forma permanente">
                                        <i data-lucide="trash" class="h-3.5 w-3.5"></i>
                                    </button>
                                </form>
                            @endif
                        </div>

                        <!-- Info & Actions -->
                        <div class="p-3.5 space-y-1.5 flex-grow flex flex-col justify-between">
                            <div>
                                <h4 class="text-[10px] font-bold text-gray-700 truncate" title="{{ $file->original_name }}">
                                    {{ $file->original_name }}
                                </h4>
                                <p class="text-[8px] text-gray-400 font-extrabold uppercase mt-0.5 font-mono">
                                    {{ $file->mime_type }} • {{ round($file->size / 1024, 1) }} KB
                                </p>
                            </div>

                            <!-- Form Alt text -->
                            <form action="{{ route('media.alt', $file->id) }}" method="POST" class="mt-2 space-y-1.5">
                                @csrf
                                <input 
                                    type="text" 
                                    name="alt_text" 
                                    value="{{ $file->alt_text }}" 
                                    placeholder="Texto Alt"
                                    class="w-full text-[9px] px-2 py-1 bg-white border border-gray-200 rounded-lg focus:outline-none text-gray-600 font-semibold"
                                >
                            </form>

                            <!-- Copy Path button -->
                            <button 
                                type="button"
                                onclick="navigator.clipboard.writeText('/storage/{{ $file->path }}'); alert('Ruta del archivo copiada: \n/storage/{{ $file->path }}')"
                                class="w-full text-center text-[9px] font-bold text-[var(--primary)] hover:underline focus:outline-none pt-2 border-t border-gray-100"
                            >
                                Copiar Ruta
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="mt-8 pt-6 border-t border-gray-100">
                {{ $mediaFiles->links() }}
            </div>
        @else
            <!-- Empty state -->
            <div class="text-center py-16 text-gray-400">
                <i data-lucide="image-off" class="h-12 w-12 mx-auto mb-2 text-gray-300"></i>
                <p class="text-sm font-semibold">No se han subido imágenes todavía.</p>
            </div>
        @endif
    </div>

</div>
@endsection
