<?php

namespace App\Http\Requests\DailyTask;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDailyTaskRequest extends FormRequest
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
            'xp_reward' => ['sometimes', 'integer', 'min:1', 'max:500'],
            'task_date' => ['sometimes', 'date'],
        ];
    }

    public function messages(): array
    {
        return [
            'title.min' => 'El título debe tener al menos 3 caracteres.',
            'title.max' => 'El título debe tener menos de 150 caracteres.',
            'xp_reward.min' => 'Los puntos deben ser al menos 1.',
            'xp_reward.max' => 'Los puntos no pueden superar 500.',
        ];
    }
}
