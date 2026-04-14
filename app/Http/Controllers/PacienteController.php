<?php
namespace App\Http\Controllers;

use App\Models\Paciente;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PacienteController extends Controller
{
 //Listar todos los pacientes
public function index(Request $request)
{
    // Capturamos el término desde el input 'search'
    $buscar = $request->input('search');

    // Usamos when() para filtrar solo si existe un término de búsqueda
    $pacientes = Paciente::when($buscar, function ($query, $buscar) {
        return $query->where('nombre', 'LIKE', "%{$buscar}%")
                     ->orWhere('apellido', 'LIKE', "%{$buscar}%")
                     ->orWhere('correo', 'LIKE', "%{$buscar}%");
    })
    ->get(); // Puedes cambiar a ->paginate(10) si la lista crece mucho

    // Pasamos tanto los pacientes como el término para la persistencia en la vista
    return view('pacientes.index', compact('pacientes', 'buscar'));
}

    // mostar el formulario de creación)
    public function create() {
        return view('pacientes.create');
    }



    // Guardar en la base de datos
public function store(Request $request)
{
    // 1. Validamos TODO primero. Si esto falla, regresa a la vista automáticamente.
    $request->validate([
        'nombre' => 'required|string|max:100',
        'apellido' => 'required|string|max:100',
        'usuario' => 'required|unique:extra_pacientes,usuario',
        'contrasena' => 'required|min:8', // Aquí validamos los 8 caracteres
        'correo_acceso' => 'required|email|unique:extra_pacientes,correo',
        'imagen' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Máximo 2MB
    ], [
        // Mensajes si n ota bien
        'contrasena.min' => 'La contraseña debe tener al menos 8 letras o números.',
        'contrasena.required' => 'Debes escribir una contraseña.',
        'usuario.unique' => 'Ese nombre de usuario ya está utilizado.',
        'correo_acceso.unique' => 'Ese correo ya está registrado.',
    ]);

    // 2. Solo si pasó la validación, entramos al Try-Catch
    try {
        DB::beginTransaction();

        $pathImagen = null;
        if ($request->hasFile('imagen')) {
            // Guarda la foto en storage/app/public/pacientes y obtiene la ruta
            $pathImagen = $request->file('imagen')->store('pacientes', 'public');
        }


        // Creamos el paciente
        $paciente = Paciente::create([
            'nombre' => $request->nombre,
            'apellido' => $request->apellido,
            'telefono' => $request->telefono,
            'correo' => $request->correo,
            'imagen' => $pathImagen,
            'fecha_nacimiento' => $request->fecha_nacimiento,
            'id_psicologa' => $request->id_psicologa,
            'foto' => $pathImagen,
            
        ]);

        // Creamos las credenciales vinculadas
        $paciente->extras()->create([
            'usuario' => $request->usuario,
            'contrasena' => Hash::make($request->contrasena),
            'correo' => $request->correo_acceso,
        ]);

        DB::commit();
        return redirect()->route('pacientes.index')->with('success', 'Paciente y cuenta creados con éxito.');

    } catch (\Exception $e) {
        DB::rollBack();
        // Volvemos atrás con el error y los datos que ya había escrito el usuario
        return back()->withInput()->withErrors('Error al registrar: ' . $e->getMessage());
    }
}


    // Editar
public function edit($id)
{
    // Cargamos el paciente junto con sus extras para que los datos estén disponibles en la vista de edición
    $paciente = Paciente::with('extras')->findOrFail($id);
    return view('pacientes.edit', compact('paciente'));
}





    // Actualizar lso datos de lpaciente
public function update(Request $request, $id)
{
    $paciente = Paciente::with('extras')->findOrFail($id);

    $request->validate([
        'nombre' => 'required|string|max:100',
        'apellido' => 'required|string|max:100',
        'correo' => 'nullable|email',
        'contrasena' => 'nullable|min:8', 
        'usuario' => 'required|unique:extra_pacientes,usuario,' . $paciente->extras->id_extrapaciente . ',id_extrapaciente',
        'correo_acceso' => 'required|email|unique:extra_pacientes,correo,' . $paciente->extras->id_extrapaciente . ',id_extrapaciente',
        'imagen' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ], [
        'contrasena.min' => 'La nueva contraseña debe tener al menos 8 caracteres.',
        'usuario.unique' => 'Este nombre de usuario ya está en uso.',
        'correo_acceso.unique' => 'Este correo de acceso ya está registrado.',
        'nombre.required' => 'El nombre es obligatorio.',
        'apellido.required' => 'El apellido es obligatorio.',
    ]);

    try {
        \DB::transaction(function () use ($request, $paciente) {
            $datosPaciente = [
                'nombre' => $request->nombre,
                'apellido' => $request->apellido,
                'correo' => $request->correo,
                'telefono' => $request->telefono,
                'fecha_nacimiento' => $request->fecha_nacimiento,
            ];

            if ($request->hasFile('imagen')) {
                if ($paciente->imagen && !\Str::startsWith($paciente->imagen, 'http')) {
                    \Storage::disk('public')->delete($paciente->imagen);
                }
                $datosPaciente['imagen'] = $request->file('imagen')->store('pacientes', 'public');
            }

            $paciente->update($datosPaciente);

            $datosExtra = [
                'usuario' => $request->usuario,
                'correo' => $request->correo_acceso,
            ];

            if (isset($datosPaciente['imagen'])) {
                $datosExtra['foto'] = $datosPaciente['imagen'];
            }

            if ($request->filled('contrasena')) {
                $datosExtra['contrasena'] = \Hash::make($request->contrasena);
            }

            $paciente->extras()->update($datosExtra);
        });

        return redirect()->route('pacientes.index')
            ->with('success', 'El expediente de ' . $paciente->nombre . ' ha sido actualizado.');

    } catch (\Exception $e) {
        return back()->withInput()->withErrors('Error al actualizar: ' . $e->getMessage());
    }
}

    // Eliminar al paciente)
public function destroy($id)
{
    $paciente = Paciente::findOrFail($id);

    // Verificación de Expediente
    if ($paciente->expediente()->exists()) {
        return redirect()->route('pacientes.index')
            ->with('error', 'No se puede borrar: El paciente tiene un expediente clínico.');
    }

    // NUEVA Verificación de Citas
    if ($paciente->citas()->exists()) {
        return redirect()->route('pacientes.index')
            ->with('error', 'No se puede borrar: El paciente tiene citas agendadas en el sistema.');
    }

    try {
        \DB::beginTransaction();
        
        // Limpiar credenciales y fotos locales como hicimos antes...
        if ($paciente->extras) { $paciente->extras()->delete(); }
        
        if ($paciente->imagen && !\Str::startsWith($paciente->imagen, 'http')) {
            \Storage::disk('public')->delete($paciente->imagen);
        }

        $paciente->delete();
        \DB::commit();

        return redirect()->route('pacientes.index')
            ->with('success', 'Paciente eliminado correctamente.');

    } catch (\Exception $e) {
        \DB::rollBack();
        return redirect()->route('pacientes.index')
            ->with('error', 'Error inesperado: ' . $e->getMessage());
    }
}

}