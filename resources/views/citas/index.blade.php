@extends('layouts.app')

@section('contenido')
<div class="max-w-6xl mx-auto py-8 px-4">
    {{-- Encabezado --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-black text-[#1e1b4b]">Agenda de Citas</h1>
            <p class="text-gray-500 font-medium">Gestiona los próximos encuentros con tus pacientes.</p>
        </div>
        <a href="{{ route('citas.create') }}" class="inline-flex items-center justify-center px-6 py-4 text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-bold rounded-2xl text-md transition shadow-lg shadow-blue-200">
            <svg class="w-5 h-5 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 4v16m8-8H4"></path>
            </svg>
            Agendar Nueva Cita
        </a>
    </div>
    

    {{-- Tabla de Citas --}}
    <div class="bg-white rounded-[2.5rem] shadow-lg border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-[#1e1b4b] uppercase bg-gray-50 font-black tracking-widest">
                    <tr>
                        <th scope="col" class="px-6 py-5">Paciente</th>
                        <th scope="col" class="px-6 py-5">Fecha</th>
                        <th scope="col" class="px-6 py-5">Hora</th>
                        <th scope="col" class="px-6 py-5 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($citas as $cita)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <th scope="row" class="px-6 py-6 font-bold text-gray-900 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 me-3">
                                    {{ substr($cita->paciente->nombre, 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-base">{{ $cita->paciente->nombre }} {{ $cita->paciente->apellido }}</div>
                                    <div class="text-xs text-gray-400 font-medium">{{ $cita->paciente->telefono }}</div>
                                </div>
                            </div>
                        </th>
                        <td class="px-6 py-6">
                            <span class="bg-indigo-50 text-indigo-700 px-3 py-1.5 rounded-lg font-bold">
                                {{ \Carbon\Carbon::parse($cita->fecha)->format('d/m/Y') }}
                            </span>
                        </td>
                        <td class="px-6 py-6">
                            <span class="bg-emerald-50 text-emerald-700 px-3 py-1.5 rounded-lg font-bold">
                                {{ \Carbon\Carbon::parse($cita->hora)->format('h:i A') }}
                            </span>
                        </td>
                        <td class="px-6 py-6 text-center">
                            <div class="flex justify-center gap-2">
                                <a href="{{ route('citas.edit', $cita->id_citas) }}" class="p-2 text-amber-600 bg-amber-50 rounded-xl hover:bg-amber-100 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                                <form action="{{ route('citas.destroy', $cita->id_citas) }}" method="POST" onsubmit="return confirm('¿Eliminar esta cita?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 text-red-600 bg-red-50 rounded-xl hover:bg-red-100 transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-16 h-16 text-gray-200 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                                <p class="text-gray-400 font-bold text-lg">No hay citas programadas.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{-- Cambia el encabezado del Index por este en el Historial --}}
<div>
    <h1 class="text-3xl font-black text-gray-600">Historial de Consultas</h1>
    <p class="text-gray-500 font-medium">Registro de todas las citas finalizadas.</p>
</div>

<a href="{{ route('citas.historial') }}" class="text-blue-600 font-bold hover:underline">
    Ver historial completo →
</a>
</div>


@endsection