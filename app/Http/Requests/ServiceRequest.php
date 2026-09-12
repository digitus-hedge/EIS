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
            // Banner
            'banner_title'       => ['required', 'string', 'max:255'],
            'banner_description' => ['nullable', 'string'],
            'banner_image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'], // 10MB

            // Overview
            'overview_title'       => ['nullable', 'string', 'max:255'],
            'overview_description' => ['nullable', 'string'],
            'overview_image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'], // 10MB

            // Process (repeatable video + description rows, unlimited)
            'process'                  => ['nullable', 'array'],
            'process.*.description'    => ['nullable', 'string'],
            'process.*.video'          => ['nullable', 'mimes:mp4,mov,avi,webm', 'max:20480'], // 20MB
            'process.*.existing_video' => ['nullable', 'string'],

            // Features (repeatable icon + title + description rows, max 4)
            'features_heading'         => ['nullable', 'string', 'max:255'],
            'features'                 => ['nullable', 'array', 'max:4'],
            'features.*.title'         => ['nullable', 'string', 'max:255'],
            'features.*.description'   => ['nullable', 'string'],
            'features.*.icon'          => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'], // 5MB
            'features.*.existing_icon' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'features.max' => 'You can add a maximum of 4 features.',
        ];
    }
}