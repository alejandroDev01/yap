<?php

namespace App\Http\Requests;

use App\Enums\UserStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class U_userStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'genero' => 'required|string|max:10',
            'nombre' => 'required|string|max:255',
            'foto' => 'required|string|max:255',
            'apellidos' => 'required|string|max:255',
            'fecha_nacimiento' => 'required|date|before:today',
            'direccion' => 'required|string|max:255',
            'nro_documento' => 'required|string|max:20|unique:u_users,nro_documento',
            'celular' => 'required|string|max:20',
            'email' => 'required|email|unique:u_users,email',
            'password' => 'required|string|min:8|confirmed',
            'estado' => ['nullable', new Enum(UserStatus::class)],
        ];
    }

    public function messages()
    {
        return [
            'genero.required' => 'El género es obligatorio',
            'nombre.required' => 'El nombre es obligatorio',
            'apellidos.required' => 'Los apellidos son obligatorios',
            'fecha_nacimiento.required' => 'La fecha de nacimiento es obligatoria',
            'fecha_nacimiento.date' => 'La fecha de nacimiento debe ser una fecha válida',
            'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy',
            'direccion.required' => 'La dirección es obligatoria',
            'nro_documento.required' => 'El número de documento es obligatorio',
            'nro_documento.unique' => 'El número de documento ya está registrado',
            'celular.required' => 'El celular es obligatorio',
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'El correo electrónico debe ser válido',
            'email.unique' => 'El correo electrónico ya está registrado',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres',
            'password.confirmed' => 'La confirmación de la contraseña no coincide',
            'estado.enum' => 'El estado del usuario no es válido',
        ];
    }
}
