<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreExpedienteRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
            return true;
                }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
public function rules(): array
{
    return [
        // 'unique:tabla,columna' evita duplicados
        'id_pacientes'    => 'required|exists:pacientes,id_pacientes|unique:expediente,id_pacientes',
        'motivo_consulta' => 'nullable|string',
        'diagnostico'     => 'nullable|string',
        'ocupacion'       => 'required|string|max:100',
        'edad'            => 'required|integer|min:0|max:120',
    ];
}

/**
 * Mensajes personalizados para que la psicóloga entienda el error
 */
public function messages()
{
    return [
        'id_pacientes.unique' => 'Este paciente ya posee un expediente clínico activo.',
    ];
}
    
}
