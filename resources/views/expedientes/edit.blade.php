@extends('layouts.app')

@section('contenido')
<div class="max-w-4xl mx-auto py-8 px-4">
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('expedientes.index') }}" class="inline-flex items-center text-sm font-semibold text-gray-600 hover:text-indigo-900 transition-colors">
                    Gestión de Expedientes
                </a>
            </li>
            <li aria-current="page">
                <div class="flex items-center text-gray-400">
                    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <span class="ml-1 text-sm font-bold text-indigo-600 uppercase tracking-wider">Modificar Registro</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="bg-white p-10 rounded-[2.5rem] shadow-xl border border-gray-100">
        <div class="mb-10 border-b border-gray-50 pb-6">
            <h2 class="text-2xl font-black text-[#1e1b4b] tracking-tight">Actualización de Expediente Clínico</h2>
            <p class="text-sm text-gray-400 mt-2 font-medium">Modifique los criterios técnicos o el diagnóstico evolutivo del paciente.</p>
        </div>

        <form action="{{ route('expedientes.update', $expediente->id_expediente) }}" method="POST" id="form-editar-expediente">
            @csrf
            @method('PUT')
            
            <div class="grid gap-8 mb-8 md:grid-cols-2">
                
                {{-- Paciente (Solo Lectura por Integridad) --}}
                <div class="md:col-span-2">
                    <label class="block mb-2 text-[10px] font-black text-gray-400 uppercase tracking-[0.15em]">Paciente Vinculado (No Editable)</label>
                    <div class="bg-gray-100 border border-gray-200 text-gray-500 text-sm rounded-2xl block w-full p-4 font-bold cursor-not-allowed">
                        {{ $expediente->paciente->nombre }} {{ $expediente->paciente->apellido }} — Folio: EXP-{{ str_pad($expediente->id_expediente, 4, '0', STR_PAD_LEFT) }}
                    </div>
                </div>

                {{-- Ocupación --}}
                <div>
                    <label for="ocupacion" class="block mb-2 text-[10px] font-black text-gray-500 uppercase tracking-[0.15em]">Ocupación / Actividad</label>
                    <input type="text" id="ocupacion" name="ocupacion" value="{{ old('ocupacion', $expediente->ocupacion) }}" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full p-4 font-medium transition-all" required>
                </div>

                {{-- Edad --}}
                <div>
                    <label for="edad" class="block mb-2 text-[10px] font-black text-gray-500 uppercase tracking-[0.15em]">Edad Cronológica Actualizada</label>
                    <input type="number" id="edad" name="edad" value="{{ old('edad', $expediente->edad) }}" class="bg-gray-50 border border-gray-200 text-gray-900 text-sm rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full p-4 font-medium transition-all" required>
                </div>

                {{-- Motivo de Consulta --}}
                <div class="md:col-span-2">
                    <label for="motivo_consulta" class="block mb-2 text-[10px] font-black text-gray-500 uppercase tracking-[0.15em]">Anamnesis / Motivo de Consulta</label>
                    <textarea id="motivo_consulta" name="motivo_consulta" rows="4" class="block p-4 w-full text-sm text-gray-900 bg-gray-50 rounded-2xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-medium transition-all">{{ old('motivo_consulta', $expediente->motivo_consulta) }}</textarea>
                </div>

                {{-- Diagnóstico --}}
                <div class="md:col-span-2">
                    <label for="diagnostico" class="block mb-2 text-[10px] font-black text-gray-500 uppercase tracking-[0.15em]">Evolución del Diagnóstico Clínico</label>
                    <textarea id="diagnostico" name="diagnostico" rows="5" class="block p-4 w-full text-sm text-gray-900 bg-gray-50 rounded-2xl border border-gray-200 focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-medium transition-all">{{ old('diagnostico', $expediente->diagnostico) }}</textarea>
                </div>
            </div>

            <div class="flex items-center justify-end space-x-4 border-t border-gray-50 pt-8">
                <a href="{{ route('expedientes.index') }}" class="py-3.5 px-8 text-xs font-black text-gray-400 uppercase tracking-widest bg-white rounded-2xl border border-gray-100 hover:bg-gray-50 hover:text-indigo-900 transition-all">
                    Descartar Cambios
                </a>
                <button type="submit" class="text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:outline-none focus:ring-indigo-200 font-bold rounded-2xl text-sm px-10 py-4 text-center shadow-lg shadow-indigo-100 transition-all tracking-wide" id="boton-guardar">
                    Guardar Actualización
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Escuchamos cuando se envíe el formulario
    document.getElementById('form-editar-expediente').addEventListener('submit', function() {
        let btn = document.getElementById('boton-guardar');
        
        // Deshabilitamos el botón
        btn.disabled = true;
        
        // Opcional: Cambiamos el texto para que el usuario sepa que está cargando
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Guardando...';
    });
</script>

@endsection