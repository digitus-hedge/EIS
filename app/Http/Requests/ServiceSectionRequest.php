<?php
// app/Http/Requests/ServiceSectionRequest.php

namespace App\Http\Requests;

use App\Models\ServiceSection;
use Illuminate\Foundation\Http\FormRequest;

class ServiceSectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $section = ServiceSection::first();
        $hasExistingImage = $section && $section->image;

        return [
            'label'       => 'nullable|string|max:50',
            'heading'     => 'required|string|max:90',
            'description' => 'required|string|max:140',
            'image'       => $hasExistingImage
                                ? 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240'
                                : 'required|image|mimes:jpg,jpeg,png,webp|max:10240',
        ];
    }

    public function messages(): array
    {
        return [
            'label.max' => 'Label must not exceed 50 characters.',

            'heading.required' => 'Please enter a heading.',
            'heading.max'      => 'Heading must not exceed 90 characters.',

            'description.required' => 'Please enter a description.',
            'description.max'      => 'Description must not exceed 140 characters.',

            'image.required' => 'Please upload an image.',
            'image.image'    => 'The file must be a valid image.',
            'image.mimes'    => 'The image must be a JPG, PNG, or WEBP file.',
            'image.max'      => 'The image must not exceed 10MB.',
        ];
    }
}