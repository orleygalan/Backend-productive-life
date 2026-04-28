<?php

namespace App\Http\Requests\DailyTask;

use Illuminate\Foundation\Http\FormRequest;

class StoreDailyTaskRequest extends FormRequest
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
            'title' => ['required', 'string', 'min:3', 'max:150'],
            'xp_reward' => ['required', 'integer', 'min:1', 'max:500'],
            'task_date' => ['required', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.required' => 'El título de la tarea es obligatorio.',
            'title.min' => 'El título debe tener al menos 3 caracteres.',
            'xp_reward.required' => 'Los puntos de la tarea son obligatorios.',
            'xp_reward.min' => 'Los puntos deben ser al menos 1.',
            'xp_reward.max' => 'Los puntos no pueden superar 500.',
            'task_date.required' => 'La fecha de la tarea es obligatoria.',
        ];
    }
}
