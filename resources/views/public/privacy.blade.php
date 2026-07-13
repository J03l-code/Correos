@extends('layouts.public')

@section('title', 'Política de Privacidad')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-16">
    <div class="bg-white rounded-3xl border border-gray-100 p-8 sm:p-12 shadow-xl space-y-6">
        
        <h1 class="text-3xl font-extrabold text-[var(--primary-dark)] font-montserrat tracking-tight border-b border-gray-100 pb-4">
            Política de Privacidad y Protección de Datos
        </h1>

        <div class="prose prose-blue text-sm text-gray-600 leading-relaxed font-semibold space-y-4">
            {!! nl2br(e($privacyText)) !!}
        </div>

        <div class="pt-6 border-t border-gray-100 flex justify-between items-center text-xs text-gray-400 font-bold">
            <p>Última actualización: 13 de Julio de 2026</p>
            <a href="{{ route('public.index') }}" class="text-[var(--primary)] hover:underline focus:outline-none">
                Volver al inicio
            </a>
        </div>

    </div>
</div>
@endsection
