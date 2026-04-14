<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nota;
use App\Models\Sesion;

class NotaController extends Controller
{

    public function create(Request $request)
    {
        // Obtenemos el ID de la sesión desde la URL
        $id_sesion = $request->query('sesion');
        $sesion = Sesion::with('expediente.paciente')->findOrFail($id_sesion);

        return view('notas.create', compact('sesion'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'subjetivo'   => 'nullable|string',
            'anotaciones' => 'nullable|string',
            'id_sesion'   => 'required|exists:sesion,id_sesion'
        ]);

        Nota::create($request->all());

        // Al terminar, devolvemos a la psicóloga al expediente del paciente
        $sesion = Sesion::findOrFail($request->id_sesion);
        
        return redirect()->route('expedientes.show', $sesion->id_expediente)
                         ->with('success', 'La nueva nota ha sido grabada en el historial.');
    }

    /**
     * Muestra el formulario para editar una nota específica.
     */
    public function edit($id)
    {
        // Cargamos la nota con la sesión y el paciente para dar contexto
        $nota = Nota::with('sesion.expediente.paciente')->findOrFail($id);
        
        return view('notas.edit', compact('nota'));
    }

    /**
     * Actualiza la nota en la base de datos.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'subjetivo'   => 'nullable|string',
            'anotaciones' => 'nullable|string'
        ]);

        $nota = Nota::findOrFail($id);
        $nota->update($request->all());

        // Buscamos el ID del expediente para la redirección
        $id_expediente = $nota->sesion->id_expediente;

        return redirect()->route('expedientes.show', $id_expediente)
                         ->with('success', 'La nota clínica ha sido actualizada correctamente.');
    }

    /**
     * Elimina una nota del registro.
     */
    public function destroy($id)
    {
        $nota = Nota::findOrFail($id);
        $id_expediente = $nota->sesion->id_expediente;
        $nota->delete();

        return redirect()->route('expedientes.show', $id_expediente)
                         ->with('success', 'La nota ha sido purificada del registro.');
    }
}