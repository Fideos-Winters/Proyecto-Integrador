@extends('layouts.app')

@section('contenido')
<div class="max-w-7xl mx-auto py-8 px-4">
    {{-- Encabezado Principal --}}
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <span class="text-[10px] font-black text-indigo-500 uppercase tracking-[0.2em]">Expediente Clínico</span>
            <h1 class="text-3xl font-black text-[#1e1b4b] tracking-tight">
                {{ $expediente->paciente->nombre }} {{ $expediente->paciente->apellido }}
            </h1>
        </div>
        <div class="flex gap-3">
            <a href="{{ route('expedientes.index') }}" class="px-6 py-3 bg-white border border-gray-100 text-gray-400 font-bold rounded-2xl hover:bg-gray-50 transition text-sm">
                Volver al Índice
            </a>
            <a href="{{ route('expedientes.edit', $expediente->id_expediente) }}" class="px-6 py-3 bg-[#1e1b4b] text-white font-bold rounded-2xl hover:bg-opacity-90 transition text-sm shadow-lg shadow-indigo-100">
                Editar Datos
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        {{-- SECCIÓN 1: DATOS DEL EXPEDIENTE (Izquierda) --}}
        <div class="space-y-6">
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
                <h3 class="text-xs font-black text-indigo-500 uppercase tracking-widest mb-6 border-b border-gray-50 pb-4">Ficha Técnica</h3>
                
                <div class="space-y-6">
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Ocupación</p>
                        <p class="text-sm font-bold text-[#1e1b4b]">{{ $expediente->ocupacion }}</p>
                    </div>
                    <div class="flex justify-between">
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Edad</p>
                            <p class="text-sm font-bold text-[#1e1b4b]">{{ $expediente->edad }} años</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">ID Folio</p>
                            <p class="text-sm font-bold text-indigo-600">#EXP-{{ str_pad($expediente->id_expediente, 4, '0', STR_PAD_LEFT) }}</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Motivo de Consulta</p>
                        <p class="text-sm text-gray-600 leading-relaxed italic">"{{ $expediente->motivo_consulta }}"</p>
                    </div>
                </div>
            </div>

            <div class="bg-indigo-600 p-8 rounded-[2.5rem] shadow-xl text-white">
                <h3 class="text-xs font-black text-indigo-200 uppercase tracking-widest mb-4">Diagnóstico Actual</h3>
                <p class="text-md font-bold leading-relaxed">
                    {{ $expediente->diagnostico ?? 'Evaluación inicial en proceso.' }}
                </p>
            </div>
        </div>

        {{-- SECCIÓN 2: GESTIÓN DE SESIONES (Derecha - 2 columnas) --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 min-h-[500px]">
                
                {{-- Controles de Sesión --}}
                <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-10 pb-6 border-b border-gray-50">
                    <div>
                        <h3 class="text-xl font-black text-[#1e1b4b]">Historial de Sesiones</h3>
                        <p class="text-xs text-gray-400 font-medium">Cronología de intervenciones terapéuticas</p>
                    </div>
                    
                    <div class="flex items-center gap-2 w-full md:w-auto">
                        {{-- Buscador por fecha --}}
                        <div class="relative flex-grow">
                            <input type="date" class="w-full pl-4 pr-10 py-3 bg-gray-50 border-none rounded-xl text-xs font-bold text-gray-600 focus:ring-2 focus:ring-indigo-500">
                        </div>
                        
                        <a href="{{ route('sesiones.create', ['expediente' => $expediente->id_expediente]) }}" class="p-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition shadow-md">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        </a>
                    </div>
                </div>

                {{-- Listado de Sesiones --}}
                <div class="space-y-4">
                    
                    @forelse($expediente->sesiones as $sesion)
                    
                    <div class="flex items-center p-5 bg-gray-50 rounded-[2rem] border border-transparent hover:border-indigo-100 hover:bg-white transition-all group">
                        <div class="flex-shrink-0 w-12 h-12 bg-white rounded-2xl flex flex-col items-center justify-center shadow-sm border border-gray-100">
                            <span class="text-[10px] font-black text-indigo-600 uppercase">{{ \Carbon\Carbon::parse($sesion->fecha)->format('M') }}</span>
                            <span class="text-sm font-black text-[#1e1b4b]">{{ \Carbon\Carbon::parse($sesion->fecha)->format('d') }}</span>
                        </div>
                        
                        <div class="ml-6 flex-grow">
                            <h4 class="text-sm font-black text-[#1e1b4b]">Sesión de Seguimiento</h4>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-tight">{{ $sesion->hora_inicio }} - {{ $sesion->hora_fin }}</p>
                        </div>

{{-- En el listado de sesiones del expediente --}}
<a href="{{ route('sesiones.show', $sesion->id_sesion) }}" class="p-2 text-indigo-600 hover:bg-indigo-50 rounded-lg transition">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
    </svg>
</a>
                    </div>
                    @empty
                    <div class="py-20 text-center">
                        <p class="text-gray-400 font-bold text-sm">No hay sesiones registradas para este paciente.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection