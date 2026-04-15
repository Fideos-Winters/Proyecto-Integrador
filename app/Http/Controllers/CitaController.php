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
     * Muestra el formulario de creación
     */
    public function create()
    {
        $pacientes  = Paciente::orderBy('nombre', 'asc')->get();
        $fechaMin   = Carbon::today()->toDateString(); // Para el atributo min del input date en la vista
        return view('citas.create', compact('pacientes', 'fechaMin'));
    }

    /**
     * Valida y guarda la nueva cita.
     * Reglas:
     *   1. La fecha no puede ser anterior a hoy.
     *   2. No puede existir otra cita el mismo día dentro de un rango de ±60 minutos.
     */
    public function store(Request $request)
    {
        // ── 1. Validaciones básicas ──────────────────────────────────────────
        $request->validate([
            'id_pacientes' => 'required|exists:pacientes,id_pacientes',
            'fecha'        => 'required|date|after_or_equal:today',
            'hora'         => 'required|date_format:H:i',
            'id_psicologa' => 'nullable|integer',
        ], [
            'fecha.after_or_equal'  => 'No puedes agendar citas en días que ya pasaron.',
            'id_pacientes.exists'   => 'El paciente seleccionado no es válido.',
            'hora.date_format'      => 'El formato de hora debe ser HH:MM (ej. 09:30).',
        ]);

        // ── 2. Verificar solapamiento (±60 min el mismo día) ─────────────────
        $conflicto = $this->buscarConflictoHorario(
            fecha     : $request->fecha,
            hora      : $request->hora,
            excluirId : null   // en store no hay cita a excluir
        );

        if ($conflicto) {
            $horaConflicto = Carbon::parse($conflicto->hora)->format('H:i');
            return back()
                ->withInput()
                ->withErrors([
                    'hora' => "Ya existe una cita a las {$horaConflicto}. "
                            . "Debes dejar al menos 1 hora de diferencia entre citas.",
                ]);
        }

        // ── 3. Crear ─────────────────────────────────────────────────────────
        Cita::create([
            'id_pacientes' => $request->id_pacientes,
            'fecha'        => $request->fecha,
            'hora'         => $request->hora,
            'id_psicologa' => 1, // Cambiar a auth()->id() cuando tengas login
        ]);

        return redirect()->route('citas.index')
            ->with('success', 'Cita agendada correctamente.');
    }

    /**
     * Muestra el formulario de edición.
     * Pasa la fecha mínima para bloquear días pasados en el date-picker.
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
        // ── 1. Validaciones básicas ──────────────────────────────────────────
        $request->validate([
            'id_pacientes' => 'required|exists:pacientes,id_pacientes',
            'fecha'        => 'required|date|after_or_equal:today',
            'hora'         => 'required|date_format:H:i',
        ], [
            'fecha.after_or_equal' => 'No puedes reprogramar una cita para una fecha que ya pasó.',
            'id_pacientes.exists'  => 'El paciente seleccionado no es válido.',
            'hora.date_format'     => 'El formato de hora debe ser HH:MM (ej. 09:30).',
        ]);

        // ── 2. Verificar solapamiento excluyendo la cita actual ───────────────
        $conflicto = $this->buscarConflictoHorario(
            fecha     : $request->fecha,
            hora      : $request->hora,
            excluirId : $id        // excluimos la propia cita para no bloquearse a sí misma
        );

        if ($conflicto) {
            $horaConflicto = Carbon::parse($conflicto->hora)->format('H:i');
            return back()
                ->withInput()
                ->withErrors([
                    'hora' => "Ya existe una cita a las {$horaConflicto}. "
                            . "Debes dejar al menos 1 hora de diferencia entre citas.",
                ]);
        }

        // ── 3. Localizar y actualizar ─────────────────────────────────────────
        $cita = Cita::findOrFail($id);

        // Limpiar notificaciones si cambió la fecha
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

    /**
     * Elimina la cita.
     */
    public function destroy($id)
    {
        $cita = Cita::findOrFail($id);
        $cita->delete();

        return redirect()->route('citas.index')
            ->with('success', 'La cita ha sido borrada del registro.');
    }

    // ══════════════════════════════════════════════════════════════════════════
    // MÉTODO PRIVADO: buscar conflicto de horario
    // ══════════════════════════════════════════════════════════════════════════

    /**
     * Busca si existe alguna cita el mismo día cuya hora esté a menos de 60 minutos
     * de la hora solicitada.
     *
     * @param  string      $fecha      Fecha en formato Y-m-d
     * @param  string      $hora       Hora en formato H:i
     * @param  int|null    $excluirId  ID de cita a ignorar (útil en update)
     * @return Cita|null               La cita conflictiva, o null si no hay conflicto
     */
    private function buscarConflictoHorario(string $fecha, string $hora, ?int $excluirId): ?Cita
    {
        $horaCarbon = Carbon::parse("{$fecha} {$hora}");

        // Ventana de bloqueo: desde 1 minuto después de la hora anterior
        // hasta 59 minutos después de la hora solicitada.
        // Equivale a: no permitir dos citas a menos de 60 minutos de distancia.
        $desde = $horaCarbon->copy()->subMinutes(59)->format('H:i:s');
        $hasta = $horaCarbon->copy()->addMinutes(59)->format('H:i:s');

        $query = Cita::where('fecha', $fecha)
            ->whereBetween('hora', [$desde, $hasta]);

        // En update, excluimos la propia cita para que no se bloquee a sí misma
        if ($excluirId !== null) {
            $query->where('id', '!=', $excluirId);
        }

        return $query->first();
    }
}