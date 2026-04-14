@extends('layouts.app')

@section('contenido')
<div class="max-w-7xl mx-auto py-8 px-4">
    <div class="mb-8">
        <h1 class="text-3xl font-black text-[#1e1b4b] mb-6">Listado de Pacientes</h1>

        <div class="flex flex-col md:flex-row gap-4 items-center justify-between bg-white p-4 rounded-[2rem] shadow-sm border border-gray-100">
            <form action="{{ route('pacientes.index') }}" method="GET" class="relative w-full md:w-2/3">
                <div class="absolute inset-y-0 start-0 flex items-center ps-4 pointer-events-none">
                    <svg class="w-4 h-4 text-gray-400" fill="none" viewBox="0 0 20 20">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m19 19-4-4m0-7A7 7 0 1 1 1 8a7 7 0 0 1 14 0Z" />
                    </svg>
                </div>
                <input type="search" 
                       name="search" 
                       value="{{ $buscar ?? '' }}" 
                       class="block w-full p-4 ps-10 text-sm text-gray-900 border-none bg-gray-50 rounded-2xl focus:ring-2 focus:ring-blue-500 font-medium" 
                       placeholder="Buscar por nombre, apellido o correo...">
                
                <button type="submit" class="text-white absolute end-2.5 bottom-2 bg-[#1e1b4b] hover:bg-opacity-90 font-bold rounded-xl text-sm px-6 py-2.5 transition">
                    Buscar
                </button>
            </form>

            <div class="flex gap-2 w-full md:w-auto">
                <a href="{{ route('pacientes.create') }}" class="flex items-center justify-center gap-2 text-white bg-green-500 hover:bg-green-600 font-bold rounded-2xl text-sm px-5 py-4 w-full md:w-auto transition shadow-lg shadow-green-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Nuevo Paciente
                </a>
            </div>
        </div>
    </div>

    <div class="space-y-4">
        @forelse($pacientes as $paciente)
            <div class="flex flex-col md:flex-row items-center gap-6 bg-white p-6 rounded-[2.5rem] shadow-sm border border-gray-50 hover:border-blue-100 transition-all duration-300 group">
                
                <div class="relative">
                    @php
                        $urlFinal = asset('assets/iconos/perfil_paciente.jpg');
                        if ($paciente->imagen) {
                            $urlFinal = str_starts_with($paciente->imagen, 'http') 
                                ? $paciente->imagen 
                                : asset('storage/' . $paciente->imagen);
                        }
                    @endphp

                    <img class="w-24 h-24 rounded-[2rem] object-cover border-4 border-gray-50 group-hover:border-blue-100 transition shadow-sm" 
                         src="{{ $urlFinal }}" 
                         alt="Foto de {{ $paciente->nombre }}">
                    
                    @if($paciente->imagen && str_contains($paciente->imagen, 'googleusercontent'))
                        <div class="absolute -bottom-1 -right-1 bg-green-500 w-5 h-5 rounded-full border-2 border-white shadow-sm" title="Sincronizado con Google"></div>
                    @endif
                </div>

                <div class="flex-grow text-center md:text-left">
                    <h3 class="text-xl font-extrabold text-[#1e1b4b]">{{ $paciente->nombre }} {{ $paciente->apellido }}</h3>
                    <p class="text-gray-500 text-sm mt-1 font-medium leading-relaxed">
                        {{ $paciente->correo ?? 'Sin correo registrado' }} • Tel: {{ $paciente->telefono ?? 'N/A' }}
                    </p>
                    <div class="flex flex-wrap justify-center md:justify-start gap-4 mt-3">
                        <span class="text-[10px] font-black uppercase tracking-widest text-blue-500 bg-blue-50 px-3 py-1 rounded-full">ID #{{ $paciente->id_pacientes }}</span>
                        <span class="text-[10px] font-black uppercase tracking-widest text-gray-400 bg-gray-50 px-3 py-1 rounded-full">Registrado: {{ $paciente->fecha_registro }}</span>
                    </div>
                </div>

                <div class="flex gap-2">
                    {{-- Botón Editar --}}
                    <a href="{{ route('pacientes.edit', $paciente->id_pacientes) }}" class="p-4 text-gray-400 bg-gray-50 hover:bg-[#1e1b4b] hover:text-white rounded-2xl transition-all duration-300">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                    </a>
                    
                    {{-- Botón Eliminar con bloqueo de Citas y Expediente --}}
                    <div class="flex items-center gap-2">
                        @if($paciente->expediente()->exists() || $paciente->citas()->exists())
                            {{-- Botón Deshabilitado --}}
                            <div class="relative group">
                                <button type="button" 
                                    class="p-4 text-gray-300 bg-gray-100 rounded-2xl cursor-not-allowed border border-gray-200">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                    </svg>
                                </button>
                                
                                {{-- El anuncio solo sale si el cursor se pone arriba (hover:block) --}}
                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 hidden group-hover:block bg-gray-800 text-white text-[10px] px-3 py-2 rounded-xl shadow-xl whitespace-nowrap z-50">
                                    @if($paciente->expediente()->exists())
                                    Paciente con expediente activo
                                    @else
                                    Paciente con citas registradas
                                    @endif
                                    {{-- Triangulito del tooltip --}}
                                    <div class="absolute top-full left-1/2 -translate-x-1/2 border-8 border-transparent border-t-gray-800"></div>
                                </div>
                            </div>
                        @else
                            {{-- Botón Activo --}}
                            <form action="{{ route('pacientes.destroy', $paciente->id_pacientes) }}" method="POST" onsubmit="return confirm('¿Confirma la eliminación definitiva de este registro?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" 
                                    class="p-4 text-gray-400 bg-gray-50 hover:bg-red-600 hover:text-white rounded-2xl transition-all duration-300 border border-transparent hover:border-red-200 shadow-sm">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="text-center py-20 bg-gray-50 rounded-[2.5rem]">
                <p class="text-gray-400 font-bold">No hay pacientes en este santuario todavía.</p>
            </div>
        @endforelse
    </div>
</div>
@endsection