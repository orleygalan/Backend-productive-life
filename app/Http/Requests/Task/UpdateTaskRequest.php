<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTaskRequest extends FormRequest
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
            'title' => ['sometimes', 'string', 'min:3', 'max:150'],
            'description' => ['nullable', 'string', 'max:500'],
            'assigned_to' => ['nullable', 'uuid', 'exists:users,id'],
            'status' => ['sometimes', 'in:todo,in_progress,done'],
            'due_date' => ['nullable', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.min' => 'El título debe tener al menos 3 caracteres.',
            'title.max' => 'El título no puede superar 150 caracteres.',
            'description.max' => 'La descripción no puede superar 500 caracteres.',
            'assigned_to.exists' => 'El usuario seleccionado no existe.',
            'status.in' => 'El estado debe ser todo, in_progress o done.',
        ];
    }
}
