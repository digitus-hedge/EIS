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
        // Grab the bound Service model (if editing) so we know whether a banner
        // image already exists — controls whether banner_image is required.
        $service = $this->route('service');
        $hasExistingBanner = $service && $service->banner_image;

        return [
            // Banner
            'banner_title'       => ['required', 'string', 'max:255'],
            'banner_description' => ['required', 'string', 'max:1000'],
            'banner_image'       => $hasExistingBanner
                                        ? ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240']
                                        : ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'], // 10MB
            'remove_banner_image' => ['nullable', 'boolean'],

            // Overview
            'overview_title'       => ['required', 'string', 'max:255'],
            'overview_description' => ['nullable', 'string', 'max:1000'],
            'overview_image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'], // 10MB
            'remove_overview_image' => ['nullable', 'boolean'],

            // Process (repeatable video + description rows, unlimited)
            'process'                       => ['nullable', 'array'],
            'process.*.description'         => ['nullable', 'string', 'max:1000'],
            'process.*.thumbnail'           => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'process.*.existing_thumbnail'  => ['nullable', 'string'],
            'process.*.video'               => ['nullable', 'mimes:mp4,mov,avi,webm', 'max:20480'], // 20MB
            'process.*.existing_video'      => ['nullable', 'string'],

            // Features (repeatable icon + title + description rows, max 4)
            'features_heading'         => ['nullable', 'string', 'max:255'],
            'features'                 => ['nullable', 'array', 'max:4'],
            'features.*.title'         => ['nullable', 'string', 'max:255'],
            'features.*.description'   => ['nullable', 'string', 'max:1000'],
            'features.*.icon'          => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'], // 5MB
            'features.*.existing_icon' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'banner_title.required'       => 'Please enter a banner title.',
            'banner_title.max'            => 'Banner title must not exceed 255 characters.',

            'banner_description.required' => 'Please enter a banner description.',
            'banner_description.max'      => 'Banner description must not exceed 1000 characters.',

            'banner_image.required' => 'Please upload a banner image.',
            'banner_image.image'    => 'The banner image must be a valid image.',
            'banner_image.mimes'    => 'The banner image must be a JPG, PNG, or WEBP file.',
            'banner_image.max'      => 'The banner image must not exceed 10MB.',

            'overview_title.max'       => 'Overview title must not exceed 255 characters.',
            'overview_description.max' => 'Overview description must not exceed 1000 characters.',
            'overview_image.image'     => 'The overview image must be a valid image.',
            'overview_image.mimes'     => 'The overview image must be a JPG, PNG, or WEBP file.',
            'overview_image.max'       => 'The overview image must not exceed 10MB.',

            'process.*.description.max' => 'Process description must not exceed 1000 characters.',
            'process.*.thumbnail.image' => 'Process thumbnail must be a valid image.',
            'process.*.thumbnail.mimes' => 'Process thumbnail must be a JPG, PNG, or WEBP file.',
            'process.*.thumbnail.max'   => 'Process thumbnail must not exceed 10MB.',
            'process.*.video.mimes'     => 'Process video must be an MP4, MOV, AVI, or WEBM file.',
            'process.*.video.max'       => 'Process video must not exceed 20MB.',

            'features.max' => 'You can add a maximum of 4 features.',
            'features.*.title.max'        => 'Feature title must not exceed 255 characters.',
            'features.*.description.max'  => 'Feature description must not exceed 1000 characters.',
            'features.*.icon.image'       => 'Feature icon must be a valid image.',
            'features.*.icon.mimes'       => 'Feature icon must be a JPG, PNG, or WEBP file.',
            'features.*.icon.max'         => 'Feature icon must not exceed 10MB.',
        ];
    }
}