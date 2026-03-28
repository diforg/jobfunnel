<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreJobTimelineRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'stage' => ['required', 'string', 'max:255'],
            'happened_at' => ['required', 'date'],
            'notes' => ['nullable', 'string'],
        ];
    }
}
