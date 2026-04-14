@extends('layouts.app')

@section('contenido')
<div class="max-w-4xl mx-auto py-8 px-4">
    <form action="{{ route('sesiones.update', $sesion->id_sesion) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- ENCABEZADO --}}
        <div class="bg-[#1e1b4b] p-8 rounded-[2.5rem] shadow-xl mb-6 text-white flex justify-between items-center">
            <div>
                <span class="text-[10px] font-black text-indigo-300 uppercase tracking-[0.2em]">Editando Intervención</span>
                <h2 class="text-2xl font-black tracking-tight">{{ $sesion->expediente->paciente->nombre }} {{ $sesion->expediente->paciente->apellido }}</h2>
            </div>
            <div class="text-right">
                <span class="text-[10px] font-black text-indigo-300 uppercase block">Sesión ID</span>
                <p class="font-bold text-lg">#{{ $sesion->id_sesion }}</p>
            </div>
        </div>

        {{-- TIEMPO --}}
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 mb-6">
            <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6 flex items-center gap-2">Registro de Tiempo</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div>
                    <label class="block mb-2 text-[10px] font-black text-gray-500 uppercase">Fecha</label>
                    <input type="date" name="fecha" value="{{ $sesion->fecha }}" class="w-full p-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 font-bold text-gray-700">
                </div>
                <div>
                    <label class="block mb-2 text-[10px] font-black text-gray-500 uppercase">Inicio</label>
                    <input type="time" name="hora_inicio" value="{{ $sesion->hora_inicio }}" class="w-full p-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 font-bold text-gray-700">
                </div>
                <div>
                    <label class="block mb-2 text-[10px] font-black text-gray-500 uppercase">Fin</label>
                    <input type="time" name="hora_fin" value="{{ $sesion->hora_fin }}" class="w-full p-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 font-bold text-gray-700">
                </div>
            </div>
        </div>

        {{-- NOTAS (Tomamos la primera nota existente) --}}
        @php $nota = $sesion->notas->first(); @endphp
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 mb-6">
            <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6">Notas Clínicas</h3>
            <div class="space-y-6">
                <div>
                    <label class="block mb-2 text-[10px] font-black text-indigo-500 uppercase tracking-wider">Subjetivo</label>
                    <textarea name="subjetivo" rows="4" class="w-full p-6 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 font-medium text-gray-700">{{ $nota->subjetivo ?? '' }}</textarea>
                </div>
                <div>
                    <label class="block mb-2 text-[10px] font-black text-indigo-500 uppercase tracking-wider">Anotaciones</label>
                    <textarea name="anotaciones" rows="4" class="w-full p-6 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 font-medium text-gray-700">{{ $nota->anotaciones ?? '' }}</textarea>
                </div>
            </div>
        </div>

        {{-- EJERCICIO (Tomamos el primer ejercicio existente) --}}
        @php $ejercicio = $sesion->ejercicios->first(); @endphp
        <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100 mb-8">
            <h3 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6">Tarea Asignada</h3>
            <div class="space-y-6">
                <div>
                    <label class="block mb-2 text-[10px] font-black text-indigo-500 uppercase">Título del Ejercicio</label>
                    <input type="text" name="titulo" value="{{ $ejercicio->titulo ?? '' }}" class="w-full p-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 font-bold text-gray-700">
                </div>
                <div>
                    <label class="block mb-2 text-[10px] font-black text-indigo-500 uppercase">Descripción</label>
                    <textarea name="descripcion" rows="3" class="w-full p-6 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-indigo-500 font-medium text-gray-700">{{ $ejercicio->descripcion ?? '' }}</textarea>
                </div>
            </div>
        </div>

        <div class="flex items-center justify-between">
            <a href="{{ route('sesiones.show', $sesion->id_sesion) }}" class="text-sm font-bold text-gray-400">← Cancelar</a>
            <button type="submit" class="px-12 py-5 bg-indigo-600 text-white font-black rounded-3xl shadow-xl hover:bg-indigo-700 transition-all">
                Actualizar Registro
            </button>
        </div>
    </form>
</div>
@endsection