<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreResumeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'version_name' => ['required', 'string', 'max:255'],
            'file_path' => ['required', 'string', 'max:2048'],
            'sent_at' => ['nullable', 'date'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
