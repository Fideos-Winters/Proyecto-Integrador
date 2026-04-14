<?php

namespace App\Http\Controllers;

use App\Models\Psicologo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PsicologoController extends Controller
{
    public function index()
    {
        $psicologo = Psicologo::first();
        
        if (!$psicologo) {
             return redirect()->route('psicologos.create');
        }

        return view('psicologos.index', compact('psicologo'));
    }

    public function store(Request $request)
    {
        $datos = $request->validate([
            'correo'     => 'required|email|unique:psicologo,correo',
            'usuario'    => 'required|string|unique:psicologo,usuario|max:50',
            'contrasena' => 'required|string|min:8',
            'imagen'     => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('imagen')) {
            $datos['imagen'] = $request->file('imagen')->store('psicologos', 'public');
        }

        Psicologo::create($datos);

        return redirect()->route('psicologos.index')
                         ->with('success', 'Psicólogo registrado con éxito.');
    }

    public function update(Request $request, $id)
    {
        $psicologo = Psicologo::findOrFail($id);

        $rules = [
            'correo'  => "required|email|unique:psicologo,correo,{$psicologo->id_psicologa},id_psicologa",
            'usuario' => "required|string|max:50|unique:psicologo,usuario,{$psicologo->id_psicologa},id_psicologa",
            'imagen'  => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ];

        if ($request->filled('contrasena')) {
            $rules['contrasena'] = 'required|string|min:8';
        }

        $datos = $request->validate($rules);

        if ($request->hasFile('imagen')) {
            if ($psicologo->imagen) {
                Storage::disk('public')->delete($psicologo->imagen);
            }
            $datos['imagen'] = $request->file('imagen')->store('psicologos', 'public');
        }

        if (!$request->filled('contrasena')) {
            unset($datos['contrasena']);
        }

        $psicologo->update($datos);

        return redirect()->route('psicologos.index')
                         ->with('success', 'Perfil actualizado con éxito.');
    }

    public function destroy($id)
    {
        $psicologo = Psicologo::findOrFail($id);
        if ($psicologo->imagen) {
            Storage::disk('public')->delete($psicologo->imagen);
        }
        $psicologo->delete();
        
        return redirect()->route('psicologos.index')
                         ->with('success', 'Registro eliminado.');
    }

    public function edit($id)
    {
        $psicologo = Psicologo::findOrFail($id);
        return view('psicologos.edit', compact('psicologo'));
    }
}