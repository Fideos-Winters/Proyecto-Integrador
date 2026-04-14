<?php

namespace App\Http\Controllers;

use App\Models\Expediente;
use App\Http\Requests\StoreExpedienteRequest;
use Illuminate\Http\Request;

class ExpedienteController extends Controller
{
    // Mostrar todos los expedientes
    public function index(Request $request)
{
    // Capturamos el término de búsqueda desde el input 'buscar'
    $termino = $request->input('buscar');

    // Iniciamos la consulta cargando las relaciones necesarias
    $query = Expediente::with(['paciente', 'sesiones']);

    // Si el usuario escribió algo, filtramos
    if ($termino) {
        $query->where(function($q) use ($termino) {
            $q->where('id_expediente', 'LIKE', "%{$termino}%")
              ->orWhere('motivo_consulta', 'LIKE', "%{$termino}%")
              ->orWhere('diagnostico', 'LIKE', "%{$termino}%")
              // Buscamos dentro de la relación con pacientes
              ->orWhereHas('paciente', function($subQuery) use ($termino) {
                  $subQuery->where('nombre', 'LIKE', "%{$termino}%")
                           ->orWhere('apellido', 'LIKE', "%{$termino}%");
              });
        });
    }

    // Obtenemos los resultados (puedes usar paginate(10) si tienes muchos)
    $expedientes = $query->get();

    return view('expedientes.index', compact('expedientes', 'termino'));
}

    // Guardar un nuevo expediente
    public function store(StoreExpedienteRequest $request)
    {
        Expediente::create($request->validated());

        return redirect()->route('expedientes.index')
                         ->with('success', 'El expediente ha sido grabado en las crónicas.');
    }

public function show($id)
{
    $expediente = Expediente::with(['paciente', 'sesiones.notas', 'sesiones.ejercicios'])
                            ->findOrFail($id);

    return view('expedientes.show', compact('expediente'));
}
    public function create()
{
    // Traemos a los pacientes para poder seleccionarlos en el formulario
    $pacientes = \App\Models\Paciente::all(); 

    return view('expedientes.create', compact('pacientes'));
}

public function destroy($id)
{
    try {
        // Localizamos el expediente o lanzamos un error 404 si no existe
        $expediente = Expediente::findOrFail($id);

        // Procedemos al borrado lógico o físico del registro
        $expediente->delete();

        // Retornamos al índice con un mensaje de éxito profesional
        return redirect()->route('expedientes.index')
            ->with('success', 'El expediente clínico ha sido eliminado del sistema exitosamente.');

    } catch (\Illuminate\Database\QueryException $e) {
        // Error 1451: Ocurre si el expediente tiene sesiones vinculadas (Integridad Referencial)
        return redirect()->route('expedientes.index')
            ->with('error', 'No es posible eliminar el expediente. Existen registros de sesiones vinculados a este folio.');
            
    } catch (\Exception $e) {
        // Cualquier otro error imprevisto
        return redirect()->route('expedientes.index')
            ->with('error', 'Ha ocurrido un error inesperado al intentar procesar la solicitud.');
    }
}

public function edit($id)
{
    // Localizamos el expediente con su paciente vinculado
    $expediente = Expediente::with('paciente')->findOrFail($id);
    
    // Solo permitimos editar datos técnicos, no la identidad del paciente
    return view('expedientes.edit', compact('expediente'));
}

public function update(Request $request, $id)
{
    $request->validate([
        'ocupacion'       => 'required|string|max:150',
        'edad'            => 'required|integer|min:0|max:120',
        'motivo_consulta' => 'required|string',
        'diagnostico'     => 'nullable|string',
    ]);

    $expediente = Expediente::findOrFail($id);
    $expediente->update($request->only([
        'ocupacion', 
        'edad', 
        'motivo_consulta', 
        'diagnostico'
    ]));
    return redirect()->route('expedientes.index')
                     ->with('success', 'El registro clínico ha sido actualizado correctamente.');
}

}