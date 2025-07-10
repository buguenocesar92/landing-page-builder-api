<?php

namespace App\Http\Requests;

use App\Http\Requests\BaseFormRequest;

class UserRequest extends BaseFormRequest
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
            "name"=> "required|string|max:255",
            "email"=> "required|email|max:255|unique:users",
            "password"=> "required|string|min:6",
        ];
    }


    public function messages(): array
    {
        return [
            "name.required"=> "EL nombre es requerido",
            "name.string"=> "El nombre debe ser una cadena de texto",
            "name.max"=> "El nombre no debe exceder los 255 caracteres",
            "email.required"=> "El correo electrónico es requerido",
            "email.email"=> "El correo electrónico debe ser una dirección válida",
            "email.max"=> "El correo electrónico no debe exceder los 255 caracteres",
            "email.unique"=> "El correo electrónico ya está en uso",
            "password.required"=> "La contraseña es requerida",
            "password.string"=> "La contraseña debe ser una cadena de texto",
            "password.min"=> "La contraseña debe tener al menos 6 caracteres",
        ];
    }
}
