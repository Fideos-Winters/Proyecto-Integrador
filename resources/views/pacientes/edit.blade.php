@extends('layouts.app')

@section('contenido')
    <div class="p-4 sm:ml-64 min-h-screen"> 
        <div class="max-w-7xl mx-auto">
            
            <div class="mb-8">
                <h1 class="text-3xl font-black text-[#1e1b4b]">Editar Expediente</h1>
                <p class="text-gray-500 mt-1">Actualizando datos de: <span class="text-blue-600">{{ $paciente->nombre }} {{ $paciente->apellido }}</span></p>
            </div>

            <div class="bg-white p-8 rounded-[2.5rem] shadow-sm border border-gray-100">
                @if ($errors->any())
                    <div class="mb-6 p-4 bg-red-50 text-red-600 rounded-2xl border border-red-100">
                        <ul class="list-disc ps-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- INICIO DEL FORMULARIO --}}
                <form action="{{ route('pacientes.update', $paciente->id_pacientes) }}" method="POST" id="form-editar-paciente" enctype="multipart/form-data">
                    @csrf 
                    @method('PUT')
                    
                    {{-- SECCIÓN 1: DATOS PERSONALES --}}
                    <h2 class="text-lg font-bold text-[#1e1b4b] mb-6 flex items-center gap-2">
                        <span class="w-8 h-8 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center text-sm">1</span>
                        Información Personal
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8 mb-12">
                        {{-- BLOQUE DE IMAGEN --}}
                        <div class="flex flex-col items-center justify-center p-6 border-2 border-dashed border-gray-200 rounded-[2rem] bg-gray-50 h-64">
                            @php
                                // Lógica de visualización idéntica a tu tabla index
                                $urlImagen = asset('assets/iconos/perfil_paciente.jpg');
                                if ($paciente->imagen) {
                                    $urlImagen = str_starts_with($paciente->imagen, 'http') 
                                        ? $paciente->imagen 
                                        : asset('storage/' . $paciente->imagen);
                                }
                            @endphp

                            <img id="preview-foto" class="w-32 h-32 rounded-[2rem] object-cover mb-4 border-4 border-white shadow-sm" 
                                 src="{{ $urlImagen }}" 
                                 alt="Avatar">
                            
                            <label class="cursor-pointer text-sm font-bold text-blue-600 hover:underline">
                                Cambiar Foto
                                {{-- IMPORTANTE: name="imagen" para coincidir con el controlador --}}
                                <input type="file" name="imagen" class="hidden" id="input-foto" accept="image/*">
                            </label>
                            <p class="text-[10px] text-gray-400 mt-2 italic">PNG, JPG o JPEG (Máx. 2MB)</p>
                        </div>

                        <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Nombre(s)</label>
                                <input type="text" name="nombre" value="{{ old('nombre', $paciente->nombre) }}" required class="w-full p-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Apellido(s)</label>
                                <input type="text" name="apellido" value="{{ old('apellido', $paciente->apellido) }}" required class="w-full p-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Correo Electrónico</label>
                                <input type="email" name="correo" value="{{ old('correo', $paciente->correo) }}" class="w-full p-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div>
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Teléfono</label>
                                <input type="text" name="telefono" value="{{ old('telefono', $paciente->telefono) }}" class="w-full p-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500">
                            </div>
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Fecha Nacimiento</label>
                                <input type="date" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', $paciente->fecha_nacimiento) }}" class="w-full p-4 bg-gray-50 border-none rounded-2xl focus:ring-2 focus:ring-blue-500">
                            </div>
                        </div>
                    </div>

                    {{-- SECCIÓN 2: CREDENCIALES DE ACCESO --}}
                    <h2 class="text-lg font-bold text-[#1e1b4b] mb-6 flex items-center gap-2 mt-12">
                        <span class="w-8 h-8 rounded-full bg-green-50 text-green-600 flex items-center justify-center text-sm">2</span>
                        Credenciales de Acceso
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 bg-gray-50/50 p-6 rounded-[2rem] border border-gray-100">
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Usuario</label>
                            <input type="text" name="usuario" value="{{ old('usuario', $paciente->extras->usuario ?? '') }}" required class="w-full p-4 bg-white border-none rounded-2xl focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Correo de Acceso</label>
                            <input type="email" name="correo_acceso" value="{{ old('correo_acceso', $paciente->extras->correo ?? '') }}" required class="w-full p-4 bg-white border-none rounded-2xl focus:ring-2 focus:ring-blue-500">
                        </div>
                        <div>
                            <label class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Nueva Contraseña (Opcional)</label>
                            <input type="password" name="contrasena" class="w-full p-4 bg-white border-none rounded-2xl focus:ring-2 focus:ring-blue-500" placeholder="Dejar en blanco para no cambiar">
                        </div>
                    </div>

                    {{-- BOTONES DE ACCIÓN --}}
                    <div class="flex justify-end gap-4 mt-10 pt-8 border-t border-gray-100">
                        <a href="{{ route('pacientes.index') }}" class="px-8 py-4 text-sm font-bold text-gray-500 bg-gray-100 rounded-2xl hover:bg-gray-200 transition">Cancelar</a>
                        <button type="submit" class="px-8 py-4 text-sm font-bold text-white bg-blue-600 rounded-2xl hover:bg-blue-700 shadow-lg shadow-blue-900/20 transition" id="boton-actualizar">Actualizar Datos</button>
                    </div>

                </form>
            </div>
        </div>
    </div>

<script>
    // Vista previa de la foto seleccionada
    document.getElementById('input-foto').addEventListener('change', function(e) {
        if (e.target.files && e.target.files[0]) {
            let reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-foto').src = e.target.result;
            }
            reader.readAsDataURL(e.target.files[0]);
        }
    });

    // Manejo del botón de carga
    document.getElementById('form-editar-paciente').addEventListener('submit', function() {
        let btn = document.getElementById('boton-actualizar');
        btn.disabled = true;
        btn.innerHTML = '<span class="inline-block animate-spin mr-2">↻</span> Guardando...';
    });
</script>

@endsection