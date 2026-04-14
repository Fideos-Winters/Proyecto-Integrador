@extends('layouts.app')

@section('contenido')
<div class="max-w-4xl mx-auto py-8 px-4">
    {{-- Encabezado --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-black text-[#1e1b4b]">Agendar Nueva Cita</h1>
            <p class="text-gray-500 font-medium">Completa los datos para el registro médico.</p>
        </div>
        <a href="{{ route('citas.index') }}" class="text-gray-500 bg-white border border-gray-200 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 font-medium rounded-2xl text-sm p-4 transition shadow-sm inline-flex items-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
    </div>

    <div class="bg-white p-8 md:p-12 rounded-[2.5rem] shadow-lg border border-gray-100">
        {{-- Alertas de Validación --}}
        @if ($errors->any())
            <div class="mb-6 p-4 text-red-700 bg-red-50 border border-red-100 rounded-2xl">
                <div class="flex items-center mb-2">
                    <svg class="w-5 h-5 me-2" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>
                    <span class="font-bold">Por favor corrige los siguientes errores:</span>
                </div>
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('citas.store') }}" method="POST" class="space-y-6">
            @csrf

            {{-- Selección de Paciente --}}
            <div>
                <label for="id_pacientes" class="block mb-2 text-sm font-black text-[#1e1b4b] uppercase tracking-wider">Seleccionar Paciente</label>
                <select id="id_pacientes" name="id_pacientes" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-2xl focus:ring-blue-500 focus:border-blue-500 block w-full p-4 font-medium transition" required>
                    <option selected disabled>Busca un paciente...</option>
                    @foreach($pacientes as $paciente)
                        <option value="{{ $paciente->id_pacientes }}" {{ old('id_pacientes') == $paciente->id_pacientes ? 'selected' : '' }}>
                            {{ $paciente->nombre }} {{ $paciente->apellido }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Fecha --}}
                <div>
                    <label for="fecha" class="block mb-2 text-sm font-black text-[#1e1b4b] uppercase tracking-wider">Fecha de la Cita</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                            </svg>
                        </div>
                        <input datepicker datepicker-autohide datepicker-format="yyyy-mm-dd" type="text" name="fecha" id="fecha" value="{{ old('fecha') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-2xl focus:ring-blue-500 focus:border-blue-500 block w-full ps-11 p-4 font-medium transition" placeholder="YYYY-MM-DD" required>
                    </div>
                </div>

                {{-- Hora --}}
                <div>
                    <label for="hora" class="block mb-2 text-sm font-black text-[#1e1b4b] uppercase tracking-wider">Hora</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <input type="time" name="hora" id="hora" value="{{ old('hora') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-2xl focus:ring-blue-500 focus:border-blue-500 block w-full ps-11 p-4 font-medium transition" required>
                    </div>
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-4 pt-4">
                <button type="submit" class="flex-grow text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-bold rounded-2xl text-md px-5 py-4 text-center transition shadow-lg shadow-blue-200">
                    Confirmar y Guardar Cita
                </button>
                <button type="reset" class="md:w-1/3 text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-bold rounded-2xl text-md px-5 py-4 text-center transition">
                    Limpiar Formulario
                </button>
            </div>
        </form>
    </div>
</div>
<!--script mega mama para evitar envíos múltiples aca mega mamonsisimo del diablo increible wfua chalval -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Buscamos todos los formularios en la página
        const forms = document.querySelectorAll('form');

        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                // Buscamos el botón de tipo submit dentro del formulario que se está enviando
                const submitBtn = form.querySelector('button[type="submit"]');
                
                if (submitBtn) {
                    // 1. Desactivamos el botón para evitar más clics
                    submitBtn.disabled = true;

                    // 2. Estética: Cambiamos el cursor y la opacidad
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');

                    // 3. Opcional: Cambiar el texto del botón
                    // Guardamos el texto original por si acaso
                    const originalText = submitBtn.innerText;
                    submitBtn.innerHTML = `
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Guardando...
                    `;
                }
            });
        });
    });
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/datepicker.min.js"></script>
@endsection