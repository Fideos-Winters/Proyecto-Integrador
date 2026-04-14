@extends('layouts.app')

@section('contenido')
<div class="max-w-4xl mx-auto py-8 px-4">
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-black text-[#1e1b4b]">Re-programar Cita</h1>
            <p class="text-gray-500 font-medium">Modifica la fecha u hora del encuentro médico.</p>
        </div>
        <a href="{{ route('citas.index') }}" class="text-gray-500 bg-white border border-gray-200 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 font-medium rounded-2xl text-sm p-4 transition shadow-sm inline-flex items-center">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
        </a>
    </div>

    <div class="bg-white p-8 md:p-12 rounded-[2.5rem] shadow-lg border border-gray-100">
        @if ($errors->any())
            <div class="mb-6 p-4 text-red-700 bg-red-50 border border-red-100 rounded-2xl">
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('citas.update', $cita->id_citas) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT') {{-- Crucial para que Laravel sepa que es una actualización --}}

            <div>
                <label for="id_pacientes" class="block mb-2 text-sm font-black text-[#1e1b4b] uppercase tracking-wider">Paciente</label>
                <select id="id_pacientes" name="id_pacientes" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-2xl focus:ring-blue-500 focus:border-blue-500 block w-full p-4 font-medium transition" required>
                    @foreach($pacientes as $paciente)
                        <option value="{{ $paciente->id_pacientes }}" {{ $cita->id_pacientes == $paciente->id_pacientes ? 'selected' : '' }}>
                            {{ $paciente->nombre }} {{ $paciente->apellido }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label for="fecha" class="block mb-2 text-sm font-black text-[#1e1b4b] uppercase tracking-wider">Nueva Fecha</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none">
                            <svg class="w-4 h-4 text-gray-500" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M20 4a2 2 0 0 0-2-2h-2V1a1 1 0 0 0-2 0v1h-3V1a1 1 0 0 0-2 0v1H6V1a1 1 0 0 0-2 0v1H2a2 2 0 0 0-2 2v2h20V4ZM0 18a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V8H0v10Zm5-8h10a1 1 0 0 1 0 2H5a1 1 0 0 1 0-2Z"/>
                            </svg>
                        </div>
                        <input datepicker datepicker-autohide datepicker-format="yyyy-mm-dd" type="text" name="fecha" id="fecha" value="{{ $cita->fecha }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-2xl focus:ring-blue-500 focus:border-blue-500 block w-full ps-11 p-4 font-medium transition" required>
                    </div>
                </div>

                <div>
                    <label for="hora" class="block mb-2 text-sm font-black text-[#1e1b4b] uppercase tracking-wider">Nueva Hora</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <input type="time" name="hora" id="hora" value="{{ \Carbon\Carbon::parse($cita->hora)->format('H:i') }}" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-2xl focus:ring-blue-500 focus:border-blue-500 block w-full ps-11 p-4 font-medium transition" required>
                    </div>
                </div>
            </div>

            <div class="flex flex-col md:flex-row gap-4 pt-4">
                <button type="submit" class="flex-grow text-white bg-indigo-600 hover:bg-indigo-700 focus:ring-4 focus:ring-indigo-300 font-bold rounded-2xl text-md px-5 py-4 text-center transition shadow-lg shadow-indigo-200">
                    Actualizar Información
                </button>
                <a href="{{ route('citas.index') }}" class="md:w-1/3 text-gray-900 bg-white border border-gray-300 hover:bg-gray-100 font-bold rounded-2xl text-md px-5 py-4 text-center transition">
                    Cancelar
                </a>
            </div>
        </form>
    </div>
</div>
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