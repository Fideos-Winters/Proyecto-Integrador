<?php

namespace App\Http\Controllers;

use App\Models\Cita;
use App\Models\Paciente;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CitaController extends Controller
{
    public function index()
    {
        $hoy = Carbon::today()->toDateString();

        $citas = Cita::with('paciente')
            ->where('fecha', '>=', $hoy)
            ->orderBy('fecha', 'asc')
            ->orderBy('hora', 'asc')
            ->get();

        return view('citas.index', compact('citas'));
    }

    public function historial()
    {
        $hoy = Carbon::today()->toDateString();

        $citas = Cita::with('paciente')
            ->where('fecha', '<', $hoy)
            ->orderBy('fecha', 'desc')
            ->orderBy('hora', 'desc')
            ->get();

        return view('citas.historial', compact('citas'));
    }

    /**
     * Muestra el formulario de creación.
     * $fechaMin se usa en la vista: <input type="date" min="{{ $fechaMin }}">
     */
    public function create()
    {
        $pacientes = Paciente::orderBy('nombre', 'asc')->get();
        $fechaMin  = Carbon::today()->toDateString();
        return view('citas.create', compact('pacientes', 'fechaMin'));
    }

    /**
     * Valida y guarda la nueva cita.
     * Reglas:
     *   1. La fecha no puede ser anterior a hoy.
     *   2. No puede existir otra cita el mismo día a menos de 60 minutos de distancia.
     */
    public function store(Request $request)
    {
        $request->validate([
            'id_pacientes' => 'required|exists:pacientes,id_pacientes',
            'fecha'        => 'required|date|after_or_equal:today',
            'hora'         => 'required',
            'id_psicologa' => 'nullable|integer',
        ], [
            'fecha.after_or_equal' => 'No puedes agendar citas en días que ya pasaron.',
            'id_pacientes.exists'  => 'El paciente seleccionado no es válido.',
        ]);

        $conflicto = $this->buscarConflictoHorario($request->fecha, $request->hora, null);

        if ($conflicto) {
            $horaConflicto = Carbon::parse($conflicto->hora)->format('H:i');
            return back()
                ->withInput()
                ->withErrors([
                    'hora' => "Ya existe una cita a las {$horaConflicto}. "
                            . "Debes dejar al menos 1 hora de diferencia entre citas.",
                ]);
        }

        Cita::create([
            'id_pacientes' => $request->id_pacientes,
            'fecha'        => $request->fecha,
            'hora'         => $request->hora,
            'id_psicologa' => 1,
        ]);

        return redirect()->route('citas.index')
            ->with('success', 'Cita agendada correctamente.');
    }

    /**
     * Muestra el formulario de edición.
     * $fechaMin bloquea días pasados en el date-picker de la vista.
     */
    public function edit($id)
    {
        $cita      = Cita::findOrFail($id);
        $pacientes = Paciente::orderBy('nombre', 'asc')->get();
        $fechaMin  = Carbon::today()->toDateString();
        return view('citas.edit', compact('cita', 'pacientes', 'fechaMin'));
    }

    /**
     * Actualiza la cita.
     * Mismas reglas que store, pero excluye la propia cita al revisar solapamiento.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'id_pacientes' => 'required|exists:pacientes,id_pacientes',
            'fecha'        => 'required|date|after_or_equal:today',
            'hora'         => 'required',
        ], [
            'fecha.after_or_equal' => 'No puedes reprogramar una cita para una fecha que ya pasó.',
            'id_pacientes.exists'  => 'El paciente seleccionado no es válido.',
        ]);

        $conflicto = $this->buscarConflictoHorario($request->fecha, $request->hora, $id);

        if ($conflicto) {
            $horaConflicto = Carbon::parse($conflicto->hora)->format('H:i');
            return back()
                ->withInput()
                ->withErrors([
                    'hora' => "Ya existe una cita a las {$horaConflicto}. "
                            . "Debes dejar al menos 1 hora de diferencia entre citas.",
                ]);
        }

        $cita = Cita::findOrFail($id);

        if ($cita->fecha !== $request->fecha) {
            $cita->notificaciones()->delete();
        }

        $cita->update([
            'id_pacientes' => $request->id_pacientes,
            'fecha'        => $request->fecha,
            'hora'         => $request->hora,
        ]);

        return redirect()->route('citas.index')
            ->with('success', 'Cita actualizada. El sistema recalculará las notificaciones.');
    }

    public function destroy($id)
    {
        $cita = Cita::findOrFail($id);
        $cita->delete();

        return redirect()->route('citas.index')
            ->with('success', 'La cita ha sido borrada del registro.');
    }

    // ══════════════════════════════════════════════════════════════════════════
    // MÉTODO PRIVADO
    // ══════════════════════════════════════════════════════════════════════════

    /**
     * Busca si existe alguna cita el mismo día cuya hora esté
     * a menos de 60 minutos de la hora solicitada.
     *
     * @param  string   $fecha      Fecha en formato Y-m-d
     * @param  string   $hora       Hora en formato H:i
     * @param  mixed    $excluirId  id_citas a ignorar (en update) o null (en store)
     * @return Cita|null
     */
    private function buscarConflictoHorario($fecha, $hora, $excluirId)
    {
        $horaCarbon = Carbon::parse("{$fecha} {$hora}");

        $desde = $horaCarbon->copy()->subMinutes(59)->format('H:i:s');
        $hasta = $horaCarbon->copy()->addMinutes(59)->format('H:i:s');

        $query = Cita::where('fecha', $fecha)
            ->whereBetween('hora', [$desde, $hasta]);

        if ($excluirId !== null) {
            $query->where('id_citas', '!=', $excluirId); // primary key correcta
        }

        return $query->first();
    }
}