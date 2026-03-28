<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJobRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'max:255'],
            'company' => ['required', 'string', 'max:255'],
            'source_name' => ['nullable', 'string', 'max:255'],
            'source_url' => ['nullable', 'url', 'max:2048'],
            'apply_url' => ['nullable', 'url', 'max:2048'],
            'description' => ['nullable', 'string'],
            'salary_expectation' => ['nullable', 'numeric', 'min:0'],
            'status' => ['nullable', 'in:identified,applied,recruiter_interview,technical_interview,technical_test,offer,hired,rejected,ghosted'],
            'notes' => ['nullable', 'string'],
            'applied_at' => ['nullable', 'date'],
        ];
    }
}
