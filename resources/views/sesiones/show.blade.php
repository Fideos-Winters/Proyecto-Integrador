@extends('layouts.app')

@section('contenido')
<div class="max-w-5xl mx-auto py-8 px-4">
    {{-- Encabezado con Acciones --}}
    <div class="flex justify-between items-center mb-8">
        <div>
            <a href="{{ route('expedientes.show', $sesion->id_expediente) }}" class="text-xs font-black text-indigo-500 uppercase tracking-widest hover:text-indigo-700 transition flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15 19l-7-7 7-7"></path></svg>
                Volver al Expediente
            </a>
            <h1 class="text-3xl font-black text-[#1e1b4b] mt-2">Detalle de Sesión</h1>
            <p class="text-sm text-gray-400 font-medium italic">{{ $sesion->expediente->paciente->nombre }} — {{ \Carbon\Carbon::parse($sesion->fecha)->format('d \d\e F, Y') }}</p>
        </div>
        
        <div class="flex gap-2">
            <a href="{{ route('sesiones.edit', $sesion->id_sesion) }}" class="p-4 bg-white border border-gray-100 text-gray-500 hover:text-indigo-600 rounded-2xl shadow-sm transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 012.828 0L21 5.586a2 2 0 010 2.828l-7 7a2 2 0 01-1.06.548l-3 1a1 1 0 01-1.213-1.213l1-3a2 2 0 01.548-1.06l7-7z"></path></svg>
            </a>
            <form action="{{ route('sesiones.destroy', $sesion->id_sesion) }}" method="POST" onsubmit="return confirm('¿Deseáis purificar este registro de la historia?')">
                @csrf @method('DELETE')
                <button class="p-4 bg-white border border-gray-100 text-red-400 hover:bg-red-50 rounded-2xl shadow-sm transition">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            </form>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Tarjeta de Notas (Subjetivo y Anotaciones) --}}
        <div class="md:col-span-2 space-y-6">
            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
                <h3 class="text-xs font-black text-indigo-500 uppercase tracking-widest mb-6 border-b border-gray-50 pb-4">Notas Clínicas</h3>
                
                @forelse($sesion->notas as $nota)
                    <div class="space-y-6 mb-8 last:mb-0">
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-2">Relato Subjetivo</p>
                            <p class="text-sm text-gray-700 leading-relaxed bg-gray-50 p-4 rounded-2xl italic">"{{ $nota->subjetivo }}"</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider mb-2">Observaciones Técnicas</p>
                            <p class="text-sm text-gray-700 leading-relaxed">{{ $nota->anotaciones }}</p>
                        </div>
                    </div>
                @empty
                    <p class="text-gray-400 italic text-sm">No hay notas registradas en este encuentro.</p>
                @endforelse
            </div>
        </div>

        {{-- Tarjeta de Ejercicios --}}
        <div class="space-y-6">
            <div class="bg-[#1e1b4b] p-8 rounded-[2.5rem] shadow-xl text-white">
                <h3 class="text-xs font-black text-indigo-300 uppercase tracking-widest mb-6">Tarea Asignada</h3>
                @forelse($sesion->ejercicios as $ejercicio)
                    <div class="mb-4">
                        <h4 class="text-lg font-black text-indigo-100">{{ $ejercicio->titulo }}</h4>
                        <p class="text-sm text-indigo-200/80 mt-2 leading-relaxed">{{ $ejercicio->descripcion }}</p>
                    </div>
                @empty
                    <p class="text-indigo-300/50 italic text-xs">Sin ejercicios para esta sesión.</p>
                @endforelse
            </div>
            
            {{-- Datos de Tiempo --}}
            <div class="bg-white p-6 rounded-[2.5rem] border border-gray-100 shadow-sm text-center">
                <p class="text-[10px] text-gray-400 font-black uppercase mb-1">Duración</p>
                <p class="text-xl font-black text-[#1e1b4b]">{{ $sesion->hora_inicio }} - {{ $sesion->hora_fin }}</p>
            </div>
        </div>
    </div>
</div>
@endsection