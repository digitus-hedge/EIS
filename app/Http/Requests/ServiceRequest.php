<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ServiceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'                     => 'required|string|max:255',
            'description'               => 'required|string|max:500',
            'process_description'       => 'nullable|string',
            'technical_scope'           => 'nullable|array',
            'technical_scope.*'         => 'nullable|string|max:255',
            'specifications'            => 'nullable|array',
            'specifications.*.specification' => 'nullable|string|max:255',
            'specifications.*.details'       => 'nullable|string|max:255',
            'specifications.*.compliance'    => 'nullable|string|max:100',
            'image'                     => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'gallery.*'                 => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'sort_order'                => 'nullable|integer|min:0',
            'status'                    => 'nullable|boolean',
        ];
    }
}
