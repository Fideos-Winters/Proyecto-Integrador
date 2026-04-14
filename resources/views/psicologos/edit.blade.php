@extends('layouts.app')

@section('contenido')
<div class="max-w-4xl mx-auto py-8 px-4">
    {{-- Encabezado --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-black text-[#1e1b4b]">Actualizar Perfil</h1>
            <p class="text-gray-500 font-medium">Modifica tus credenciales y tu imagen de perfil.</p>
        </div>
        <a href="{{ route('psicologos.index') }}" class="text-gray-500 bg-white border border-gray-200 hover:bg-gray-100 focus:ring-4 focus:outline-none focus:ring-gray-200 font-medium rounded-2xl text-sm p-4 transition shadow-sm inline-flex items-center">
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
                    <span class="font-bold">Hay detalles que requieren tu atención:</span>
                </div>
                <ul class="list-disc list-inside text-sm">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- AJUSTE: Se añade enctype para permitir el envío de archivos --}}
        <form action="{{ route('psicologos.update', $psicologo->id_psicologa) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- SECCIÓN DE IMAGEN --}}
            <div class="flex flex-col items-center mb-10">
                <div class="relative group">
                    {{-- Usamos el Accessor que creamos en el modelo si ya lo tienes, o la lógica manual --}}
                    <img id="preview-avatar" 
                         src="{{ $psicologo->url_imagen ?? asset('assets/iconos/perfil_psicologa.jpg') }}" 
                         alt="Foto de perfil" 
                         class="w-40 h-40 rounded-[2.5rem] object-cover border-4 border-indigo-50 shadow-xl transition-all group-hover:border-indigo-200">
                    
                    <label for="imagen" class="absolute bottom-2 right-2 bg-[#1e1b4b] text-white p-3 rounded-2xl cursor-pointer hover:scale-110 transition shadow-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <input type="file" name="imagen" id="imagen" class="hidden" accept="image/*" onchange="previewImage(event)">
                    </label>
                </div>
                <p class="text-xs text-gray-400 mt-4 font-bold uppercase tracking-widest">Retrato de la Psicóloga</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                {{-- Usuario --}}
                <div>
                    <label for="usuario" class="block mb-2 text-sm font-black text-[#1e1b4b] uppercase tracking-wider">Nombre de Usuario</label>
                    <input type="text" name="usuario" id="usuario" value="{{ old('usuario', $psicologo->usuario) }}" 
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-2xl focus:ring-blue-500 focus:border-blue-500 block w-full p-4 font-medium transition" required>
                </div>

                {{-- Correo --}}
                <div>
                    <label for="correo" class="block mb-2 text-sm font-black text-[#1e1b4b] uppercase tracking-wider">Correo Electrónico</label>
                    <input type="email" name="correo" id="correo" value="{{ old('correo', $psicologo->correo) }}" 
                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-2xl focus:ring-blue-500 focus:border-blue-500 block w-full p-4 font-medium transition" required>
                </div>
            </div>

            {{-- Cambio de Contraseña (Opcional) --}}
            <div class="p-6 bg-indigo-50 rounded-[2rem] border border-indigo-100 mt-4">
                <label for="contrasena" class="block mb-2 text-sm font-black text-[#1e1b4b] uppercase tracking-wider">Nueva Contraseña</label>
                <input type="password" name="contrasena" id="contrasena" 
                    class="bg-white border border-indigo-200 text-gray-900 text-sm rounded-2xl focus:ring-blue-500 focus:border-blue-500 block w-full p-4 font-medium transition" 
                    placeholder="Dejar en blanco para no cambiar">
                <p class="mt-2 text-xs text-indigo-600 font-medium italic">Si decides cambiarla, asegúrate de que sea una llama difícil de apagar (mínimo 8 caracteres).</p>
            </div>

            <div class="flex flex-col md:flex-row gap-4 pt-6">
                <button type="submit" class="flex-grow text-white bg-[#1e1b4b] hover:bg-black focus:ring-4 focus:outline-none focus:ring-indigo-300 font-bold rounded-2xl text-md px-5 py-4 text-center transition shadow-lg shadow-indigo-100">
                    Actualizar Datos del Perfil
                </button>
                <button type="reset" onclick="resetPreview()" class="md:w-1/3 text-gray-900 bg-white border border-gray-300 focus:outline-none hover:bg-gray-100 focus:ring-4 focus:ring-gray-200 font-bold rounded-2xl text-md px-5 py-4 text-center transition">
                    Restablecer
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // Vista previa de la imagen antes de subirla
    function previewImage(event) {
        const reader = new FileReader();
        reader.onload = function() {
            const output = document.getElementById('preview-avatar');
            output.src = reader.result;
        }
        reader.readAsDataURL(event.target.files[0]);
    }

    // Resetear la vista previa al limpiar el formulario
    function resetPreview() {
        document.getElementById('preview-avatar').src = "{{ $psicologo->url_imagen ?? asset('assets/iconos/perfil_psicologa.jpg') }}";
    }

    document.addEventListener('DOMContentLoaded', function() {
        const forms = document.querySelectorAll('form');
        forms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const submitBtn = form.querySelector('button[type="submit"]');
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    submitBtn.innerHTML = `
                        <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Procesando cambios...
                    `;
                }
            });
        });
    });
</script>
@endsection