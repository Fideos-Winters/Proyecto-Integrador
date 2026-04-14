@extends('layouts.app')

@section('contenido')
<div class="max-w-6xl mx-auto py-8 px-4">
    {{-- Encabezado del Historial --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 mb-8">
        <div>
            <h1 class="text-3xl font-black text-slate-700">Historial de Citas</h1>
            <p class="text-slate-500 font-medium">Registro de consultas atendidas anteriormente.</p>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('citas.index') }}" class="inline-flex items-center justify-center px-6 py-4 text-slate-700 bg-white border border-slate-200 hover:bg-slate-50 focus:ring-4 focus:ring-slate-100 font-bold rounded-2xl text-md transition shadow-sm">
                <svg class="w-5 h-5 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 15l-3-3m0 0l3-3m-3 3h8M3 12a9 9 0 1118 0 9 9 0 01-18 0z"></path>
                </svg>
                Volver a Próximas
            </a>
        </div>
    </div>

    {{-- Tabla de Historial --}}
    <div class="bg-white rounded-[2.5rem] shadow-lg border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left text-slate-500">
                <thead class="text-xs text-slate-700 uppercase bg-slate-50 font-black tracking-widest">
                    <tr>
                        <th scope="col" class="px-6 py-5">Paciente</th>
                        <th scope="col" class="px-6 py-5">Fecha Realizada</th>
                        <th scope="col" class="px-6 py-5">Hora</th>
                        <th scope="col" class="px-6 py-5 text-center">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($citas as $cita)
                    <tr class="hover:bg-slate-50/50 transition-colors">
                        <th scope="row" class="px-6 py-6 font-bold text-slate-900 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center text-slate-500 me-3">
                                    {{ substr($cita->paciente->nombre, 0, 1) }}
                                </div>
                                <div>
                                    <div class="text-base">{{ $cita->paciente->nombre }} {{ $cita->paciente->apellido }}</div>
                                    <div class="text-xs text-slate-400 font-medium italic">Finalizada</div>
                                </div>
                            </div>
                        </th>
                        <td class="px-6 py-6">
                            <span class="text-slate-600 font-medium">
                                {{ \Carbon\Carbon::parse($cita->fecha)->translatedFormat('d M, Y') }}
                            </span>
                        </td>
                        <td class="px-6 py-6 text-slate-500">
                            {{ \Carbon\Carbon::parse($cita->hora)->format('h:i A') }}
                        </td>
                        <td class="px-6 py-6 text-center">
                            <div class="flex justify-center gap-2">
                                {{-- Botón para ver expediente o editar registro --}}
                                <a href="{{ route('citas.edit', $cita->id_citas) }}" class="p-2 text-slate-600 bg-slate-100 rounded-xl hover:bg-slate-200 transition" title="Corregir registro">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center">
                            <p class="text-slate-400 font-bold">No hay registros de citas pasadas.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection