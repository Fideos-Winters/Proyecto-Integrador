@extends('layouts.app')

@section('contenido')
<div class="max-w-4xl mx-auto py-8 px-4">
    <form action="{{ route('sesiones.store') }}" method="POST">
        @csrf
        {{-- Vínculo oculto con el expediente --}}
        <input type="hidden" name="id_expediente" value="{{ $expediente->id_expediente }}">

        {{-- SECCIÓN 1: IDENTIDAD DEL PACIENTE --}}
        <div class="bg-[#1e1b4b] p-8 rounded-[2.5rem] shadow-xl mb-6 text-white flex justify-between items-center">
            <div>
                <span class="text-[10px] font-black text-indigo-300 uppercase tracking-[0.2em]">Intervención para</span>
                <h2 class="text-2xl font-black tracking-tight">{{ $expediente->paciente->nombre }} {{ $expediente->paciente->apellido }}</h2>
                <p class="text-xs text-indigo-200 mt-1 font-medium">Expediente: #EXP-{{ str_pad($expediente->id_expediente, 4, '0', STR_PAD_LEFT) }}</p>
            </div>
            <div class="hidden md:block">
                <div class="w-16 h-16 bg-white/10 rounded-2xl flex items-center justify-center backdrop-blur-sm border border-white/10">
                    <svg class="w-8 h-8 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                </div>
            </div>
        </div>

        {{-- SECCIÓN 2: DATOS TEMPORALES --}}
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 mb-6">
            <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Registro de Tiempo
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block mb-2 text-[10px] font-black text-gray-500 uppercase tracking-wider">Fecha de Encuentro</label>
                    <input type="date" name="fecha" value="{{ date('Y-m-d') }}" class="w-full p-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 font-bold text-gray-700 transition-all">
                </div>
                <div>
                    <label class="block mb-2 text-[10px] font-black text-gray-500 uppercase tracking-wider">Hora de Inicio</label>
                    <input type="time" name="hora_inicio" class="w-full p-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 font-bold text-gray-700 transition-all">
                </div>
                <div>
                    <label class="block mb-2 text-[10px] font-black text-gray-500 uppercase tracking-wider">Hora de Finalización</label>
                    <input type="time" name="hora_fin" class="w-full p-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 font-bold text-gray-700 transition-all">
                </div>
            </div>
        </div>

        {{-- Dentro de la SECCIÓN 3: NOTAS CLÍNICAS --}}
<div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 mb-8">
    <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6">Contenido Clínico</h3>
    
    <div class="space-y-6">
        {{-- Campo Subjetivo --}}
        <div>
            <label class="block mb-2 text-[10px] font-black text-indigo-500 uppercase">Lo que el paciente expresa (Subjetivo)</label>
            <textarea name="subjetivo" rows="4" 
                class="w-full p-6 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 font-medium text-gray-700 transition-all"
                placeholder="Sentimientos, quejas o relatos del paciente..."></textarea>
        </div>

        {{-- Campo Anotaciones --}}
        <div>
            <label class="block mb-2 text-[10px] font-black text-indigo-500 uppercase">Observaciones de la Psicóloga (Anotaciones)</label>
            <textarea name="anotaciones" rows="4" 
                class="w-full p-6 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 font-medium text-gray-700 transition-all"
                placeholder="Análisis técnico, lenguaje no verbal, observaciones..."></textarea>
        </div>
    </div>
</div>
{{-- SECCIÓN 4: EJERCICIOS Y TAREAS --}}
<div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 mb-8">
    <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6 flex items-center gap-2">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
        Asignación de Ejercicios
    </h3>
    
    <div class="space-y-6">
        <div>
            <label class="block mb-2 text-[10px] font-black text-indigo-500 uppercase">Nombre del Ejercicio / Tarea</label>
            <input type="text" name="nombre_ejercicio" 
                class="w-full p-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 font-bold text-gray-700 transition-all"
                placeholder="Ej: Técnica de respiración 4-7-8">
        </div>

        <div>
            <label class="block mb-2 text-[10px] font-black text-indigo-500 uppercase">Instrucciones y Descripción</label>
            <textarea name="descripcion_ejercicio" rows="3" 
                class="w-full p-6 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 font-medium text-gray-700 transition-all"
                placeholder="Describid los pasos que el paciente debe seguir en casa..."></textarea>
        </div>
    </div>
</div>
        {{-- BOTÓN DE CIERRE (AL FINAL) --}}
        <div class="flex items-center justify-between">
            <a href="{{ route('expedientes.show', $expediente->id_expediente) }}" class="text-sm font-bold text-gray-400 hover:text-gray-600 transition-colors">
                ← Cancelar y volver al expediente
            </a>
            <button type="submit" class="px-12 py-5 bg-indigo-600 text-white font-black rounded-3xl shadow-xl shadow-indigo-100 hover:bg-indigo-700 transform hover:-translate-y-1 transition-all duration-300">
                Grabar Sesión y Notas
            </button>
        </div>
    </form>
</div>
@endsection