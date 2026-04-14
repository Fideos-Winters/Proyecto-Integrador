<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
// Importaciones vitales para que el controlador reconozca los modelos
use App\Models\Expediente;
use App\Models\Sesion;
use App\Models\Nota;
use App\Models\Ejercicio;

class SesionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Generalmente las sesiones se ven dentro del show del expediente,
        // pero podríais listar todas las sesiones del día aquí si lo deseáis.
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        // Obtenemos el ID del expediente desde la URL (?expediente=ID)
        $id_expediente = $request->query('expediente');

        // Verificamos que el expediente exista junto con su paciente para mostrar los datos
        $expediente = Expediente::with('paciente')->findOrFail($id_expediente);

        return view('sesiones.create', compact('expediente'));
    }

    /**
     * Store a newly created resource in storage.
     */
public function store(Request $request)
{
    $request->validate([
        'fecha'                => 'required|date',
        'hora_inicio'          => 'required',
        'hora_fin'             => 'required',
        'id_expediente'        => 'required|exists:expediente,id_expediente',
        'subjetivo'            => 'nullable|string',
        'anotaciones'          => 'nullable|string',
        'nombre_ejercicio'     => 'nullable|string',
        'descripcion_ejercicio' => 'nullable|string',
    ]);

    \DB::transaction(function () use ($request) {
        // 1. Buscamos el expediente con sus relaciones para obtener el ID del Extra
        $expediente = Expediente::with('paciente.extras')->findOrFail($request->id_expediente);
        
        // Obtenemos el ID de la tabla extra_pacientes

        $id_extra = $expediente->paciente->extras->id_extrapaciente ?? null;

        if (!$id_extra) {
            throw new \Exception("El paciente no tiene una cuenta de usuario vinculada (extra_paciente).");
        }

        // 2. Crear la Sesión
        $sesion = Sesion::create([
            'fecha'         => $request->fecha,
            'hora_inicio'   => $request->hora_inicio,
            'hora_fin'      => $request->hora_fin,
            'id_expediente' => $request->id_expediente,
        ]);

        // 3. Crear la Nota
        if ($request->filled('subjetivo') || $request->filled('anotaciones')) {
            Nota::create([
                'subjetivo'   => $request->subjetivo,
                'anotaciones' => $request->anotaciones,
                'id_sesion'   => $sesion->id_sesion
            ]);
        }

        // 4. Crear el Ejercicio con el título y el ID extra obligatorio
        if ($request->filled('nombre_ejercicio')) {
            Ejercicio::create([
                'titulo'           => $request->nombre_ejercicio,
                'descripcion'      => $request->descripcion_ejercicio,
                'id_sesion'        => $sesion->id_sesion,
                'id_extrapaciente' => $id_extra // El centinela ya no gritará
            ]);
        }
    });

    return redirect()->route('expedientes.show', $request->id_expediente)
                     ->with('success', 'Sesión, notas y ejercicios han sido grabados en la piedra eterna.');
}



/**
     * Display the specified resource.
     */
public function show($id)
{
    // Cargamos la sesión con todo su linaje: notas, ejercicios y el paciente
    $sesion = Sesion::with(['notas', 'ejercicios', 'expediente.paciente'])->findOrFail($id);

    return view('sesiones.show', compact('sesion'));
}

public function edit($id)
{
    $sesion = Sesion::findOrFail($id);
    return view('sesiones.edit', compact('sesion'));
}

    /**
     * Update the specified resource in storage.
     */
public function update(Request $request, $id)
{
    $request->validate([
        'fecha'       => 'required|date',
        'hora_inicio' => 'required',
        'hora_fin'    => 'required',
        'titulo'      => 'nullable|string',
    ]);

    $sesion = Sesion::findOrFail($id);

    \DB::transaction(function () use ($request, $sesion) {
        // 1. Actualizar Sesión
        $sesion->update([
            'fecha'       => $request->fecha,
            'hora_inicio' => $request->hora_inicio,
            'hora_fin'    => $request->hora_fin,
        ]);

        // 2. Actualizar o Crear Nota
        if ($request->filled('subjetivo') || $request->filled('anotaciones')) {
            $sesion->notas()->updateOrCreate(
                ['id_sesion' => $sesion->id_sesion],
                [
                    'subjetivo'   => $request->subjetivo,
                    'anotaciones' => $request->anotaciones
                ]
            );
        }

        // 3. Actualizar o Crear Ejercicio
        if ($request->filled('titulo')) {
            // Buscamos el ID extra del paciente de nuevo por seguridad
            $id_extra = $sesion->expediente->paciente->extras->id_extrapaciente;

            $sesion->ejercicios()->updateOrCreate(
                ['id_sesion' => $sesion->id_sesion],
                [
                    'titulo'           => $request->titulo,
                    'descripcion'      => $request->descripcion,
                    'id_extrapaciente' => $id_extra
                ]
            );
        }
    });

    return redirect()->route('sesiones.show', $sesion->id_sesion)
                     ->with('success', 'El registro ha sido restaurado y actualizado.');
}

    /**
     * Remove the specified resource from storage.
     */
public function destroy($id)
{
    // Buscamos la sesión con sus vínculos
    $sesion = Sesion::findOrFail($id);
    $id_expediente = $sesion->id_expediente;

    // Iniciamos la transacción de purificación
    \DB::transaction(function () use ($sesion) {
        // 1. Eliminamos los ejercicios vinculados
        $sesion->ejercicios()->delete();

        // 2. Eliminamos las notas vinculadas
        $sesion->notas()->delete();

        // 3. Finalmente, eliminamos la sesión
        $sesion->delete();
    });

    return redirect()->route('expedientes.show', $id_expediente)
                     ->with('success', 'La sesión y todos sus registros vinculados han sido purificados con éxito.');
}
}