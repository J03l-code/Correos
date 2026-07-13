@extends('layouts.admin')

@section('title', 'Bitácora de Cambios')

@section('breadcrumbs')
    <span class="text-gray-400">Panel</span> / <span class="text-[var(--primary)]">Bitácora</span>
@endsection

@section('content')
<div class="space-y-6">
    
    <div>
        <h1 class="text-2xl font-extrabold text-[var(--primary-dark)] font-montserrat">
            Bitácora de Actividad del Sistema
        </h1>
        <p class="text-xs text-gray-500 font-semibold mt-1">
            Historial de auditoría completo de todos los cambios de contenido y configuración.
        </p>
    </div>

    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs font-semibold text-gray-600">
                <thead class="bg-gray-50 border-b border-gray-100 text-[10px] uppercase font-bold text-gray-500">
                    <tr>
                        <th class="px-6 py-3">Fecha y Hora</th>
                        <th class="px-6 py-3">Usuario</th>
                        <th class="px-6 py-3">Operación</th>
                        <th class="px-6 py-3">Descripción</th>
                        <th class="px-6 py-3">IP Origen</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($logs as $log)
                        <tr class="hover:bg-gray-50/50">
                            <td class="px-6 py-3.5 text-gray-400 whitespace-nowrap">
                                {{ $log->created_at->format('d/m/Y H:i:s') }}
                            </td>
                            <td class="px-6 py-3.5">
                                <span class="font-bold text-[var(--primary-dark)]">{{ $log->user->name ?? 'Sistema' }}</span>
                                <p class="text-[9px] text-gray-400 uppercase tracking-wider font-extrabold">{{ $log->user->role ?? 'N/A' }}</p>
                            </td>
                            <td class="px-6 py-3.5">
                                <span class="px-2.5 py-0.5 rounded-full text-[9px] uppercase font-extrabold tracking-wider bg-slate-100 text-slate-700">
                                    {{ str_replace('_', ' ', $log->action) }}
                                </span>
                            </td>
                            <td class="px-6 py-3.5 text-gray-600 font-medium">
                                {{ $log->description }}
                            </td>
                            <td class="px-6 py-3.5 text-gray-400 font-mono text-[10px]">
                                {{ $log->ip_address }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-6 text-center text-gray-400">No hay logs de actividad registrados todavía.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $logs->links() }}
        </div>
    </div>

</div>
@endsection
