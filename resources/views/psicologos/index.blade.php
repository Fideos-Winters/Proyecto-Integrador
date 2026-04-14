@extends('layouts.app')

@section('contenido')
<div class="max-w-4xl mx-auto py-8 px-4">
    {{-- Encabezado --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-black text-[#1e1b4b]">Mi Perfil Profesional</h1>
            <p class="text-gray-500 font-medium">Gestiona tu identidad en la plataforma.</p>
        </div>
        <a href="{{ route('psicologos.edit', $psicologo->id_psicologa) }}" class="text-blue-700 bg-blue-50 border border-blue-100 hover:bg-blue-100 focus:ring-4 focus:outline-none focus:ring-blue-200 font-bold rounded-2xl text-sm px-6 py-4 transition shadow-sm inline-flex items-center">
            <svg class="w-5 h-5 me-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
            </svg>
            Editar Perfil
        </a>
    </div>

    <div class="bg-white p-8 md:p-12 rounded-[2.5rem] shadow-lg border border-gray-100 text-center">
        {{-- Avatar de la Psicóloga --}}
        <div class="relative inline-block mb-6">
            {{-- Usamos la URL optimizada del modelo --}}
            <img src="{{ $psicologo->url_imagen }}" 
                 alt="Foto de {{ $psicologo->usuario }}" 
                 class="w-40 h-40 rounded-[2.5rem] object-cover border-4 border-indigo-50 shadow-xl shadow-indigo-100/50 transition-transform hover:scale-105 duration-300">
            

        </div>

        <div class="space-y-4">
            <h2 class="text-3xl font-black text-[#1e1b4b]">{{ $psicologo->usuario }}</h2>
            <span class="inline-block px-4 py-2 bg-green-50 text-green-700 text-xs font-bold uppercase tracking-widest rounded-full border border-green-100">
                Especialista Activo
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-12 text-left">
            <div class="p-6 bg-gray-50 rounded-3xl border border-gray-100">
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Correo Electrónico</p>
                <p class="text-[#1e1b4b] font-bold text-lg">{{ $psicologo->correo }}</p>
            </div>
            <div class="p-6 bg-gray-50 rounded-3xl border border-gray-100">
                <p class="text-xs font-black text-gray-400 uppercase tracking-widest mb-1">Nombre de Usuario</p>
                <p class="text-[#1e1b4b] font-bold text-lg">{{ $psicologo->usuario }}</p>
            </div>
        </div>

        <p class="mt-8 text-sm text-gray-400 font-medium">
            Registrado bajo el folio clínico: <span class="font-bold text-gray-600">#{{ $psicologo->id_psicologa }}</span>
        </p>
    </div>
</div>
@endsection