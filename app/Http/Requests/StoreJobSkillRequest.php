<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJobSkillRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'skill_name' => ['required', 'string', 'max:255'],
            'level' => ['required', 'in:required,nice_to_have'],
            'matched' => ['nullable', 'boolean'],
        ];
    }
}
