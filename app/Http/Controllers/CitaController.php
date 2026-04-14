<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CitaController extends Controller
{
    /**
     * Lista todas las citas con sus pacientes (Eager Loading)
     */
public function index() 
{
    // Obtenemos la fecha de hoy usando Carbon
    $hoy = \Carbon\Carbon::today()->toDateString();

    // Filtramos: fecha mayor o igual a hoy
    $citas = Cita::with('paciente')
        ->where('fecha', '>=', $hoy) 
        ->orderBy('fecha', 'asc')
        ->orderBy('hora', 'asc')
        ->get();

    return view('citas.index', compact('citas'));
}

    public function historial() 
{
    $hoy = \Carbon\Carbon::today()->toDateString();

    // Traemos solo las que ya pasaron
    $citas = Cita::with('paciente')
        ->where('fecha', '<', $hoy) 
        ->orderBy('fecha', 'desc') // La última que pasó aparece primero
        ->orderBy('hora', 'desc')
        ->get();

    // Reutilizamos la misma vista o una copia con título diferente
    return view('citas.historial', compact('citas'));
}



    /**
     * Muestra el formulario de creación
     */
    public function create()
    {
        $pacientes = Paciente::orderBy('nombre', 'asc')->get();
        return view('citas.create', compact('pacientes'));
    }

    /**
     * Valida y guarda la nueva cita
     */
    public function store(Request $request)
    {
        // Validaciones optimizadas
        $request->validate([
            'id_pacientes' => 'required|exists:pacientes,id_pacientes',
            'fecha'        => 'required|date|after_or_equal:today', // No citas en el pasado
            'hora'         => 'required',
            'id_psicologa' => 'nullable|integer'
        ], [
            // Mensajes personalizados (opcional)
            'fecha.after_or_equal' => 'No puedes agendar citas en días que ya pasaron.',
            'id_pacientes.exists'  => 'El paciente seleccionado no es válido.'
        ]);

        // Creación masiva (Asegúrate de tener $fillable en el Modelo Cita)
        Cita::create([
            'id_pacientes' => $request->id_pacientes,
            'fecha'        => $request->fecha,
            'hora'         => $request->hora,
            'id_psicologa' => 1, // Ajustar a auth()->id() cuando tengas login listo
        ]);

        return redirect()->route('citas.index')
            ->with('success', 'La llama se mantiene: Cita agendada correctamente.');
    }

    /**
     * Muestra el formulario para editar (Preparado para el siguiente paso)
     */
    public function edit($id)
    {
        $cita = Cita::findOrFail($id);
        $pacientes = Paciente::all();
        return view('citas.edit', compact('cita', 'pacientes'));
    }

    /**
     * Actualiza la cita
     */
public function update(Request $request, $id)
{
    // 1. Validaciones estrictas
    $request->validate([
        'id_pacientes' => 'required|exists:pacientes,id_pacientes',
        'fecha'        => 'required|date|after_or_equal:today', 
        'hora'         => 'required',
    ], [
        'fecha.after_or_equal' => 'No puedes reprogramar una cita para una fecha que ya pasó.',
        'id_pacientes.exists'  => 'El paciente seleccionado no es válido.',
    ]);

    // 2. Localizar la cita
    $cita = Cita::findOrFail($id);

    // --- LÓGICA DE LIMPIEZA DE NOTIFICACIONES ---
    // Si la fecha actual en la DB es distinta a la nueva fecha del formulario...
    if ($cita->fecha !== $request->fecha) {
        // Borramos todas las notificaciones asociadas a esta cita.
        // Esto usa la relación que creamos en el modelo Cita.
        $cita->notificaciones()->delete();
    }
    // --------------------------------------------

    // 3. Actualizar los datos
    $cita->update([
        'id_pacientes' => $request->id_pacientes,
        'fecha'        => $request->fecha,
        'hora'         => $request->hora,
    ]);

    // Redirigir al index con mensaje de éxito
    return redirect()->route('citas.index')
                     ->with('success', 'Cita actualizada. El sistema recalculará las notificaciones.');
}

    /**
     * Elimina la cita
     */
    public function destroy($id)
    {
        $cita = Cita::findOrFail($id);
        $cita->delete();

        return redirect()->route('citas.index')
            ->with('success', 'La cita ha sido borrada del registro.');
    }
}