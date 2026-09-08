<?php
// app/Http/Requests/ServiceSectionRequest.php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceSectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'label'       => 'nullable|string|max:50',
            'heading'     => 'required|string|max:255',
            'description' => 'required|string|max:1000',
        ];
    }
}