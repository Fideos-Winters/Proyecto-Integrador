@extends('layouts.app')

@section('contenido')
<link href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
<div class="max-w-4xl mx-auto py-8 px-4">


    {{-- Navegación Jerárquica --}}
    <nav class="flex mb-6" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ route('expedientes.index') }}" class="inline-flex items-center text-sm font-semibold text-gray-600 hover:text-indigo-900 transition-colors">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                    Gestión de Expedientes
                </a>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-gray-300" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd"></path></svg>
                    <span class="ml-1 text-sm font-bold text-indigo-600 md:ml-2 uppercase tracking-wider">Apertura de Expediente</span>
                </div>
            </li>
        </ol>
    </nav>

    <div class="bg-white p-10 rounded-[2.5rem] shadow-xl border border-gray-100">
        <div class="mb-10 border-b border-gray-50 pb-6">
            <h2 class="text-2xl font-black text-[#1e1b4b] tracking-tight">Registro de Nuevo Expediente Clínico</h2>
            <p class="text-sm text-gray-400 mt-2 font-medium">Ingrese los datos técnicos y demográficos del paciente para formalizar el registro clínico.</p>
        </div>

        <form action="{{ route('expedientes.store') }}" method="POST" id="form-crear-expediente">
            @csrf
            
            <div class="grid gap-8 mb-8 md:grid-cols-2">
                
                {{-- Búsqueda de Paciente (Lista en cascada con búsqueda) --}}
                <div class="md:col-span-2">
                    <label for="id_pacientes" class="block mb-2 text-[10px] font-black text-gray-500 uppercase tracking-[0.15em]">Identificación del Paciente</label>
                    <div class="relative">
                        <select id="id_pacientes" name="id_pacientes" class="bg-gray-50 border @error('id_pacientes') border-red-500 @else border-gray-200 @enderror text-gray-900 text-sm rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full p-4 transition-all appearance-none cursor-pointer font-medium" required>
                            <option selected disabled>Escriba para buscar o seleccione un paciente...</option>
                            @foreach($pacientes as $paciente)
                                <option value="{{ $paciente->id_pacientes }}" {{ old('id_pacientes') == $paciente->id_pacientes ? 'selected' : '' }}>
                                    {{ $paciente->nombre }}  {{ $paciente->apellido }} — ID: {{ str_pad($paciente->id_pacientes, 4, '0', STR_PAD_LEFT) }}
                                </option>
                            @endforeach
                        </select>
                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-gray-400">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                    @error('id_pacientes')
                        <p class="mt-2 text-[11px] text-red-600 font-bold flex items-center gap-1">
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Datos Demográficos --}}
                <div>
                    <label for="ocupacion" class="block mb-2 text-[10px] font-black text-gray-500 uppercase tracking-[0.15em]">Ocupación / Actividad</label>
                    <input type="text" id="ocupacion" name="ocupacion" value="{{ old('ocupacion') }}" class="bg-gray-50 border @error('ocupacion') border-red-500 @else border-gray-200 @enderror text-gray-900 text-sm rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full p-4 font-medium transition-all" placeholder="Ej: Especialista en Sistemas" required>
                    @error('ocupacion')
                        <p class="mt-2 text-[11px] text-red-600 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="edad" class="block mb-2 text-[10px] font-black text-gray-500 uppercase tracking-[0.15em]">Edad Cronológica</label>
                    <input type="number" id="edad" name="edad" value="{{ old('edad') }}" class="bg-gray-50 border @error('edad') border-red-500 @else border-gray-200 @enderror text-gray-900 text-sm rounded-2xl focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 block w-full p-4 font-medium transition-all" placeholder="Años cumplidos" required>
                    @error('edad')
                        <p class="mt-2 text-[11px] text-red-600 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Áreas de Texto Clínico --}}
                <div class="md:col-span-2">
                    <label for="motivo_consulta" class="block mb-2 text-[10px] font-black text-gray-500 uppercase tracking-[0.15em]">Anamnesis: Motivo de la Consulta</label>
                    <textarea id="motivo_consulta" name="motivo_consulta" rows="4" class="block p-4 w-full text-sm text-gray-900 bg-gray-50 rounded-2xl border @error('motivo_consulta') border-red-500 @else border-gray-200 @enderror focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-medium transition-all" placeholder="Descripción detallada de la sintomatología o motivo de ingreso...">{{ old('motivo_consulta') }}</textarea>
                    @error('motivo_consulta')
                        <p class="mt-2 text-[11px] text-red-600 font-bold">{{ $message }}</p>
                    @enderror
                </div>

                <div class="md:col-span-2">
                    <label for="diagnostico" class="block mb-2 text-[10px] font-black text-gray-500 uppercase tracking-[0.15em]">Impresión Diagnóstica Inicial</label>
                    <textarea id="diagnostico" name="diagnostico" rows="4" class="block p-4 w-full text-sm text-gray-900 bg-gray-50 rounded-2xl border @error('diagnostico') border-red-500 @else border-gray-200 @enderror focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 font-medium transition-all" placeholder="Diagnóstico clínico basado en criterios diagnósticos actuales...">{{ old('diagnostico') }}</textarea>
                    @error('diagnostico')
                        <p class="mt-2 text-[11px] text-red-600 font-bold">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            {{-- Acciones Finales --}}
            <div class="flex items-center justify-end space-x-4 border-t border-gray-50 pt-8">
                <a href="{{ route('expedientes.index') }}" class="py-3.5 px-8 text-xs font-black text-gray-400 uppercase tracking-widest bg-white rounded-2xl border border-gray-100 hover:bg-gray-50 hover:text-indigo-900 transition-all">
                    Cancelar Operación
                </a>
                <button type="submit" class="text-white bg-[#1e1b4b] hover:bg-indigo-900 focus:ring-4 focus:outline-none focus:ring-indigo-200 font-bold rounded-2xl text-sm px-10 py-4 text-center shadow-lg shadow-indigo-100 transition-all tracking-wide" id="boton-guardar">
                    Finalizar Apertura de Expediente
                </button>
            </div>
        </form>
    </div>
</div>


@push('scripts')

<script>
    new TomSelect("#id_pacientes", {
        create: false, // No permite crear pacientes desde aquí, solo seleccionar
        sortField: {
            field: "text",
            direction: "asc"
        },
        placeholder: "Escriba el nombre o ID del paciente...",
        // Personalización de estilo para que encaje con vuestro diseño
        onInitialize: function() {
            this.control.classList.add('rounded-2xl', 'p-1', 'bg-gray-50', 'border-gray-200');
        }
    });

    // Escuchamos cuando se envíe el formulario
    document.getElementById('form-crear-expediente').addEventListener('submit', function() {
        let btn = document.getElementById('boton-guardar');
        
        // Deshabilitamos el botón
        btn.disabled = true;
        
        // Opcional: Cambiamos el texto para que el usuario sepa que está cargando
        btn.innerHTML = '<span class="spinner-border spinner-border-sm"></span> Guardando...';
    });


</script>

<style>
    /* Ajuste para que Tom Select respete vuestros bordes redondeados profesionales */
    .ts-control {
        border-radius: 1rem !important; 
        padding: 0.75rem !important;
        border: 1px solid #e5e7eb !important;
    }
    .ts-dropdown {
        border-radius: 1rem !important;
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1) !important;
    }
</style>

@endpush
@endsection