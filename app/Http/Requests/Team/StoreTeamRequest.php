<?php

namespace App\Http\Requests\Team;

use Illuminate\Foundation\Http\FormRequest;

class StoreTeamRequest extends FormRequest
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
            'name' => ['required', 'string', 'min:3', 'max:100'],
            'organization_id' => ['required', 'uuid', 'exists:organization,id'],
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre del equipo es obligatorio.',
            'name.min' => 'El nombre debe tener al menos 3 caracteres.',
            'name.max' => 'El nombre no puede superar 100 caracteres.',
            'organization_id.required' => 'La organización es obligatoria.',
            'organization_id.exists' => 'La organización seleccionada no existe.',
        ];
    }
}
