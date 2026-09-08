<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class WhyChooseUsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'heading'     => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',

            'mission_title'       => 'nullable|string|max:100',
            'mission_description' => 'nullable|string|max:1000',
            'mission_image'       => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',

            'vision_title'       => 'nullable|string|max:100',
            'vision_description' => 'nullable|string|max:1000',
            'vision_image'       => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',

            'values_title'       => 'nullable|string|max:100',
            'values_description' => 'nullable|string|max:1000',
            'values_image'       => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',

            'commitment_title'       => 'nullable|string|max:100',
            'commitment_description' => 'nullable|string|max:1000',
        ];
    }

    public function messages(): array
    {
        return [
            'heading.required' => 'Please enter a heading.',
            '*.image'          => 'File must be a valid image.',
            '*.mimes'          => 'Images must be JPG, PNG, or WEBP.',
            '*.max'            => 'File exceeds the allowed size.',
        ];
    }
}
