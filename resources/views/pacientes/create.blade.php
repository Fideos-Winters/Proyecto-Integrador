@extends('layouts.app')

@section('contenido')
    <div class="p-4 sm:ml-64 min-h-screen"> 
        <div class="max-w-7xl mx-auto">
            
            <div class="mb-8">
                <h1 class="text-3xl font-black text-[#1e1b4b]">Expediente y Acceso</h1>
                <p class="text-gray-500 mt-1">Registro integral de datos personales y credenciales del paciente</p>
            </div>

            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 text-red-600 rounded-2xl border border-red-100">
                        <ul class="list-disc ps-5 font-medium">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- AJUSTE: enctype para archivos y action al store --}}
                <form action="{{ route('pacientes.store') }}" method="POST" id="form-crear-paciente" enctype="multipart/form-data">
                    @csrf 
                    <input type="hidden" name="id_psicologa" value="1">

                    {{-- SECCION 1: DATOS PERSONALES --}}
                    <h2 class="text-lg font-bold text-[#1e1b4b] mb-6 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-sm">1</span>
                        Información Personal
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                        {{-- BLOQUE DE FOTO ADAPTADO --}}
                        <div class="flex flex-col items-center justify-center p-6 border-2 border-dashed border-gray-200 rounded-[2rem] bg-gray-50 h-80 transition-all hover:bg-gray-100">
                            <div class="relative mb-4">
                                {{-- Imagen de previsualización --}}
                                <img id="img-preview" src="{{ asset('assets/iconos/perfil_paciente.jpg') }}" 
                                     class="w-32 h-32 rounded-[2rem] object-cover border-4 border-white shadow-md">
                                
                                {{-- Icono indicador --}}
                                <div class="absolute -bottom-2 -right-2 bg-blue-600 text-white p-2 rounded-xl shadow-lg">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                    </svg>
                                </div>
                            </div>

                            <label class="cursor-pointer text-sm font-bold text-blue-600 hover:underline">
                                Seleccionar Foto
                                {{-- AJUSTE: name="imagen" para el controlador --}}
                                <input type="file" name="imagen" id="input-imagen" class="hidden" accept="image/*" onchange="previewFile()">
                            </label>
                            <p class="text-[10px] text-gray-400 mt-2 font-bold uppercase">PNG o JPG (Máx. 2MB)</p>
                        </div>

                        <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Nombre(s)</label>
                                <input type="text" name="nombre" value="{{ old('nombre') }}" required class="w-full p-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500" placeholder="Ej. Cesar">
                            </div>
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Apellido(s)</label>
                                <input type="text" name="apellido" value="{{ old('apellido') }}" required class="w-full p-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500" placeholder="Ej. Vallejo">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Correo Personal (Contacto)</label>
                                <input type="email" name="correo" value="{{ old('correo') }}" class="w-full p-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500" placeholder="paciente@ejemplo.com">
                            </div>
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Teléfono</label>
                                <input type="text" name="telefono" value="{{ old('telefono') }}" class="w-full p-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500" placeholder="33 0000 0000">
                            </div>
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Fecha Nacimiento</label>
                                <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" class="w-full p-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>

                    {{-- SECCIÓN 2: CREDENCIALES DE ACCESO --}}
                    <h2 class="text-lg font-bold text-[#1e1b4b] mb-6 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-full bg-green-50 text-green-600 flex items-center justify-center text-sm">2</span>
                        Credenciales de Acceso al Sistema
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-gray-50/50 p-6 rounded-[2rem] border border-gray-100">
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Usuario</label>
                            <input type="text" name="usuario" value="{{ old('usuario') }}" required class="w-full p-4 bg-white border-none rounded-2xl focus:ring-2 focus:ring-blue-500" placeholder="ej. damian_morales">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Correo de Acceso</label>
                            <input type="email" name="correo_acceso" value="{{ old('correo_acceso') }}" required class="w-full p-4 bg-white border-none rounded-2xl focus:ring-2 focus:ring-blue-500" placeholder="acceso@dominio.com">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Contraseña Temporal</label>
                            <input type="password" name="contrasena" required class="w-full p-4 bg-white border-none rounded-2xl focus:ring-2 focus:ring-blue-500" placeholder="********">
                        </div>
                    </div>

                    <div class="flex justify-end gap-4 mt-10 pt-8 border-t border-gray-100">
                        <a href="{{ route('pacientes.index') }}" class="px-8 py-4 text-sm font-bold text-gray-500 bg-gray-100 rounded-2xl hover:bg-gray-200 transition">Cancelar Operación</a>
                        <button type="submit" class="px-8 py-4 text-sm font-bold text-white bg-[#1e1b4b] rounded-2xl hover:bg-opacity-90 shadow-lg shadow-blue-900/20 transition" id="boton-guardar">Finalizar Registro</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

<script>
    // Función para previsualizar la imagen antes de subirla
    function previewFile() {
        const preview = document.getElementById('img-preview');
        const file = document.getElementById('input-imagen').files[0];
        const reader = new FileReader();

        reader.onloadend = function () {
            preview.src = reader.result;
        }

        if (file) {
            reader.readAsDataURL(file);
        } else {
            preview.src = "{{ asset('assets/iconos/perfil_paciente.jpg') }}";
        }
    }

    // Escuchamos cuando se envíe el formulario
    document.getElementById('form-crear-paciente').addEventListener('submit', function() {
        let btn = document.getElementById('boton-guardar');
        btn.disabled = true;
        btn.innerHTML = '<svg class="animate-spin h-5 w-5 mr-3 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Guardando...';
    });
</script>

@endsection