<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'items'               => 'required|array|min:1|max:5',
            'items.*.value'       => 'required|string|max:50',
            'items.*.label'       => 'required|string|max:100',
            'items.*.description' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'items.required'         => 'Please add at least 1 stat.',
            'items.max'               => 'You can add a maximum of 5 stats.',
            'items.*.value.required'  => 'Value is required for each stat.',
            'items.*.label.required'  => 'Label is required for each stat.',
            'items.*.description.required' => 'Description is required for each stat.',
        ];
    }
}
