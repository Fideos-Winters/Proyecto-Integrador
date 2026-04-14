@extends('layouts.app')

@section('contenido')
<div class="max-w-6xl mx-auto py-8 px-4">
    <div class="mb-8">
        <h1 class="text-3xl font-black text-slate-700">Centro de Notificaciones</h1>
        <p class="text-slate-500 font-medium">Alertas automáticas para citas programadas para mañana.</p>
    </div>

    <div class="bg-white rounded-[2.5rem] shadow-lg border border-slate-100 overflow-hidden">
        <table class="w-full text-sm text-left">
            <thead class="text-xs text-slate-400 uppercase bg-slate-50 font-black tracking-widest">
                <tr>
                    <th class="px-6 py-5">Paciente</th>
                    <th class="px-6 py-5">Mensaje del Sistema</th>
                    <th class="px-6 py-5">Generada el</th>
                    <th class="px-6 py-5 text-center">Estado</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($notificaciones as $notificacion)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-6 font-bold text-slate-900">
                        {{ $notificacion->cita->paciente->nombre }} {{ $notificacion->cita->paciente->apellido }}
                    </td>
                    <td class="px-6 py-6">
                        <span class="text-indigo-600 font-bold">● {{ $notificacion->tipo_notificacion }}</span>
                        <p class="text-xs text-slate-400">Cita programada para: {{ $notificacion->cita->fecha }}</p>
                    </td>
                    <td class="px-6 py-6 text-slate-500">
                        {{ \Carbon\Carbon::parse($notificacion->fecha_envio)->diffForHumans() }}
                    </td>
                    <td class="px-6 py-6 text-center">
                        <span class="px-3 py-1 text-[10px] font-black uppercase rounded-full bg-blue-100 text-blue-700">
                            Pendiente
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-20 text-center">
                        <p class="text-slate-300 font-bold text-lg">No hay alertas pendientes por ahora.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection