<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateJobSkillRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'skill_name' => ['sometimes', 'required', 'string', 'max:255'],
            'level' => ['sometimes', 'required', 'in:required,nice_to_have'],
            'matched' => ['nullable', 'boolean'],
        ];
    }
}
