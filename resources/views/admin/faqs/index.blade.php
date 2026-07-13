@extends('layouts.admin')

@section('title', 'Preguntas Frecuentes')

@section('breadcrumbs')
    <span class="text-gray-400">Panel</span> / <span class="text-[var(--primary)]">FAQs</span>
@endsection

@section('content')
<div class="space-y-6">
    
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-extrabold text-[var(--primary-dark)] font-montserrat">
                Preguntas Frecuentes
            </h1>
            <p class="text-xs text-gray-500 font-semibold mt-1">
                Administra las dudas comunes de tus usuarios mostradas en forma de acordeón accesible.
            </p>
        </div>
        <x-button variant="primary" href="{{ route('faqs.create') }}" class="text-xs">
            <i data-lucide="plus" class="h-4 w-4 mr-1.5"></i>
            Nueva FAQ
        </x-button>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs font-semibold text-gray-600">
                <thead class="bg-gray-50 border-b border-gray-100 text-[10px] uppercase font-bold text-gray-500">
                    <tr>
                        <th class="px-6 py-3 w-16 text-center">Orden</th>
                        <th class="px-6 py-3">Pregunta / Respuesta</th>
                        <th class="px-6 py-3">Categoría</th>
                        <th class="px-6 py-3">Estado</th>
                        <th class="px-6 py-3 text-right">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($faqs as $faq)
                        <tr class="hover:bg-gray-50/50">
                            <!-- Order controls -->
                            <td class="px-6 py-3.5 text-center">
                                <div class="flex flex-col items-center justify-center space-y-1">
                                    <form action="{{ route('faqs.up', $faq->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="p-0.5 text-gray-400 hover:text-[var(--primary)] transition-colors focus:outline-none">
                                            <i data-lucide="chevron-up" class="h-4 w-4"></i>
                                        </button>
                                    </form>
                                    <form action="{{ route('faqs.down', $faq->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="p-0.5 text-gray-400 hover:text-[var(--primary)] transition-colors focus:outline-none">
                                            <i data-lucide="chevron-down" class="h-4 w-4"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                            <td class="px-6 py-3.5">
                                <span class="font-bold text-[var(--primary-dark)] text-sm">{{ $faq->question }}</span>
                                <p class="text-[10px] text-gray-400 mt-1 leading-relaxed max-w-md line-clamp-2">{{ $faq->answer }}</p>
                            </td>
                            <td class="px-6 py-3.5 text-gray-500 capitalize">
                                {{ $faq->category ?: 'General' }}
                            </td>
                            <td class="px-6 py-3.5">
                                <form action="{{ route('faqs.toggle', $faq->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="focus:outline-none">
                                        <x-badge variant="{{ $faq->is_active ? 'success' : 'danger' }}">
                                            {{ $faq->is_active ? 'Activo' : 'Inactivo' }}
                                        </x-badge>
                                    </button>
                                </form>
                            </td>
                            <td class="px-6 py-3.5 text-right">
                                <div class="flex items-center justify-end space-x-2.5">
                                    <a href="{{ route('faqs.edit', $faq->id) }}" class="p-1.5 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition-all focus:outline-none" title="Editar">
                                        <i data-lucide="edit-3" class="h-4.5 w-4.5"></i>
                                    </a>
                                    @if(auth()->user()->role !== 'visualizer')
                                        <form action="{{ route('faqs.destroy', $faq->id) }}" method="POST" onsubmit="return confirm('¿Estás seguro de que deseas eliminar esta pregunta?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-1.5 bg-rose-50 text-rose-600 rounded-lg hover:bg-rose-100 transition-all focus:outline-none" title="Eliminar">
                                                <i data-lucide="trash-2" class="h-4.5 w-4.5"></i>
                                            </button>
                                        </form>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-8 text-center text-gray-400">
                                <i data-lucide="alert-circle" class="h-10 w-10 text-gray-300 mx-auto mb-2"></i>
                                <p class="text-sm font-semibold">No se han creado preguntas frecuentes todavía.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
