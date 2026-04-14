@extends('layouts.app')

@section('contenido')
<div class="max-w-7xl mx-auto py-8 px-4">
    <div class="mb-8">
        <h1 class="text-3xl font-black text-[#1e1b4b] mb-6 font-sans tracking-tight">Gestión de Expedientes Clínicos</h1>

        <div class="bg-white p-4 rounded-[2rem] shadow-sm border border-gray-100 flex flex-col md:flex-row gap-4 items-center">
            
{{-- Buscador Funcional --}}
<form action="{{ route('expedientes.index') }}" method="GET" class="relative w-full md:flex-grow">
    <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none">
        <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 20 20">
            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
        </svg>
    </div>
    {{-- Añadimos value="{{ $termino ?? '' }}" para persistencia --}}
    <input type="search" name="buscar" value="{{ $termino ?? '' }}" 
           class="block w-full p-4 ps-10 text-sm text-gray-900 border-none bg-gray-50 rounded-2xl focus:ring-2 focus:ring-indigo-500 font-medium" 
           placeholder="Buscar por paciente, folio o diagnóstico...">
    
    <button type="submit" class="text-white absolute end-2.5 bottom-2 bg-[#1e1b4b] hover:bg-opacity-95 font-bold rounded-xl text-sm px-6 py-2.5 transition">
        Consultar
    </button>
</form>

            <div class="flex flex-row gap-2 w-full md:w-auto">

                
                <a href="{{ route('expedientes.create') }}" class="inline-flex items-center justify-center gap-2 text-white bg-indigo-600 hover:bg-indigo-700 font-bold rounded-2xl text-sm px-5 py-4 flex-1 md:flex-none transition shadow-lg shadow-indigo-100">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    <span class="whitespace-nowrap">Nuevo Expediente</span>
                </a>
            </div>

            {{-- Menú de Filtros --}}
            <div id="dropdownFiltroExp" class="z-10 hidden bg-white divide-y divide-gray-100 rounded-2xl shadow-xl w-44 border border-gray-100">
                <ul class="py-2 text-sm text-gray-700 font-bold">
                    <li><a href="#" class="block px-4 py-3 hover:bg-indigo-50 hover:text-indigo-700">Diagnóstico</a></li>
                    <li><a href="#" class="block px-4 py-3 hover:bg-indigo-50 hover:text-indigo-700">Ocupación</a></li>
                    <li><a href="#" class="block px-4 py-3 hover:bg-indigo-50 hover:text-indigo-700">Fecha de Registro</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="space-y-4">
       @forelse($expedientes as $exp)
<div class="flex items-center gap-4 group">
    {{-- 1. Tarjeta Principal: Ahora es un enlace al Show --}}
    <a href="{{ route('expedientes.show', $exp->id_expediente) }}" class="flex-grow bg-white p-6 rounded-[2.5rem] shadow-sm border border-gray-50 hover:border-indigo-200 hover:shadow-md transition-all duration-300">
        <div class="flex flex-col lg:flex-row gap-6">
            
            {{-- Identidad del Paciente --}}
            <div class="flex flex-col min-w-[220px]">
                <span class="text-[10px] font-black text-indigo-500 uppercase tracking-widest">
                    REGISTRO: EXP-{{ str_pad($exp->id_expediente, 4, '0', STR_PAD_LEFT) }}
                </span>
                <h3 class="text-xl font-black text-[#1e1b4b]">
                    {{ $exp->paciente->nombre ?? 'Identidad' }} {{ $exp->paciente->apellido ?? 'no registrada' }}
                </h3>
                <div class="flex gap-2 mt-2">
                    <span class="text-[11px] bg-indigo-50 text-indigo-700 px-2.5 py-1 rounded-lg font-bold border border-indigo-100">
                        {{ $exp->edad }} años
                    </span>
                    <span class="text-[11px] bg-gray-50 text-gray-600 px-2.5 py-1 rounded-lg font-bold border border-gray-100">
                        {{ $exp->ocupacion }}
                    </span>
                </div>
            </div>

            {{-- Resumen Clínico --}}
            <div class="flex-grow grid grid-cols-1 md:grid-cols-2 gap-6 lg:border-l lg:pl-8 border-gray-100">
                <div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Motivo de Consulta Principal</p>
                    <p class="text-sm text-gray-600 font-medium leading-relaxed italic">
                        "{{ Str::limit($exp->motivo_consulta, 120) }}"
                    </p>
                </div>
                <div>
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-1">Diagnóstico Clínico Actual</p>
                    <p class="text-sm font-bold text-[#1e1b4b]">
                        {{ $exp->diagnostico ?? 'Evaluación inicial pendiente' }}
                    </p>
                </div>
            </div>

        </div>
    </a>

    {{-- 2. Botón de Acciones: Se mantiene fuera del <a> principal --}}
    <div class="relative">
        <button id="dropExp-{{ $exp->id_expediente }}" data-dropdown-toggle="menuExp-{{ $exp->id_expediente }}" class="p-5 text-gray-400 bg-white border border-gray-50 hover:bg-[#1e1b4b] hover:text-white rounded-[1.5rem] transition shadow-sm h-full">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h.01M12 12h.01M19 12h.01M6 12a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0zm7 0a1 1 0 11-2 0 1 1 0 012 0z"></path>
            </svg>
        </button>

        {{-- Menú Desplegable --}}
        <div id="menuExp-{{ $exp->id_expediente }}" class="z-50 hidden bg-white divide-y divide-gray-100 rounded-2xl shadow-xl w-60 border border-gray-100">
            <ul class="py-2 text-sm text-gray-700 font-bold">
                <li>
                    <a href="{{ route('expedientes.show', $exp->id_expediente) }}" class="flex items-center px-4 py-3 hover:bg-indigo-50 hover:text-indigo-600 transition">
                        Ver Historial Completo
                    </a>
                </li>
                <li>
                    <a href="{{ route('expedientes.edit', $exp->id_expediente) }}" class="flex items-center px-4 py-3 hover:bg-indigo-50 hover:text-indigo-600 transition">
                        Actualizar Diagnóstico
                    </a>
                </li>
                <li class="pt-2">
                    <form action="{{ route('expedientes.destroy', $exp->id_expediente) }}" method="POST" onsubmit="return confirm('¿Confirma que desea eliminar este registro clínico?')">
                        @csrf 
                        @method('DELETE')
                        <button type="submit" class="flex items-center w-full px-4 py-3 text-red-600 hover:bg-red-50 transition text-left font-black uppercase text-[10px] tracking-widest">
                            Eliminar Expediente
                        </button>
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
@empty
    {{-- Mensaje de sistema vacío --}}
@endforelse
    </div>
</div>
@endsection