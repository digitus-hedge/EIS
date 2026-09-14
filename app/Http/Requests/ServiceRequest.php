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
        $service = $this->route('service');
        $hasExistingBanner   = $service && $service->banner_image;
        $hasExistingOverview = $service && $service->overview_image;

        return [
            // Banner
            'banner_title'       => ['required', 'string', 'max:55'],
            'banner_description' => ['required', 'string', 'max:400'],
            'banner_image'       => $hasExistingBanner
                ? ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240']
                : ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'remove_banner_image' => ['nullable', 'boolean'],

            'show_on_home' => ['nullable', 'boolean'],

            // Overview
            'overview_title'       => ['required', 'string', 'max:30'],
            'overview_description' => ['required', 'string', 'max:280'],
            'overview_image'       => $hasExistingOverview
                ? ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240']
                : ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'remove_overview_image' => ['nullable', 'boolean'],


            // Meta
            'meta_title'       => ['nullable', 'string', 'max:80'],
            'meta_description' => ['nullable', 'string', 'max:200'],


            // Process — thumbnail and video now genuinely required unless an existing one is present
            'process'                       => ['required', 'array', 'min:1'],
            'process.*.description'         => ['required', 'string', 'max:1000'],
            'process.*.thumbnail'           => ['required_without:process.*.existing_thumbnail', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'process.*.existing_thumbnail'  => ['nullable', 'string'],
            'process.*.video'               => ['required_without:process.*.existing_video', 'mimes:mp4,mov,avi,webm', 'max:20480'],
            'process.*.existing_video'      => ['nullable', 'string'],

            // Features — icon now genuinely required unless an existing one is present
            'features_heading'         => ['required', 'string', 'max:60'],
            'features'                 => ['required', 'array', 'min:1', 'max:4'],
            'features.*.title'         => ['required', 'string', 'max:60'],
            'features.*.description'   => ['required', 'string', 'max:1000'],
            'features.*.icon'          => ['required_without:features.*.existing_icon', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'features.*.existing_icon' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'banner_title.required'       => 'Please enter a banner title.',
            'banner_title.max'            => 'Banner title must not exceed 55 characters.',

            'banner_description.required' => 'Please enter a banner description.',
            'banner_description.max'      => 'Banner description must not exceed 400 characters.',

            'banner_image.required' => 'Please upload a banner image.',
            'banner_image.image'    => 'The banner image must be a valid image.',
            'banner_image.mimes'    => 'The banner image must be a JPG, PNG, or WEBP file.',
            'banner_image.max'      => 'The banner image must not exceed 10MB.',

            'overview_title.required'       => 'Please enter an overview title.',
            'overview_title.max'            => 'Overview title must not exceed 30 characters.',

            'overview_description.required' => 'Please enter an overview description.',
            'overview_description.max'      => 'Overview description must not exceed 280 characters.',

            'overview_image.required' => 'Please upload an overview image.',
            'overview_image.image'    => 'The overview image must be a valid image.',
            'overview_image.mimes'    => 'The overview image must be a JPG, PNG, or WEBP file.',
            'overview_image.max'      => 'The overview image must not exceed 10MB.',

            'process.required' => 'Please add at least one process step.',
            'process.min'      => 'Please add at least one process step.',
            'process.*.description.required' => 'Process description is required.',
            'process.*.description.max'      => 'Process description must not exceed 1000 characters.',
            'process.*.thumbnail.required_without' => 'Process thumbnail is required.',
            'process.*.thumbnail.image' => 'Process thumbnail must be a valid image.',
            'process.*.thumbnail.mimes' => 'Process thumbnail must be a JPG, PNG, or WEBP file.',
            'process.*.thumbnail.max'   => 'Process thumbnail must not exceed 10MB.',
            'process.*.video.required_without' => 'Process video is required.',
            'process.*.video.mimes'     => 'Process video must be an MP4, MOV, AVI, or WEBM file.',
            'process.*.video.max'       => 'Process video must not exceed 20MB.',

            'features_heading.required' => 'Please enter a features heading.',
            'features_heading.max'      => 'Features heading must not exceed 60 characters.',

            'features.required' => 'Please add at least one feature.',
            'features.min'      => 'Please add at least one feature.',
            'features.max'      => 'You can add a maximum of 4 features.',
            'features.*.title.required'       => 'Feature title is required.',
            'features.*.title.max'            => 'Feature title must not exceed 60 characters.',
            'features.*.description.required' => 'Feature description is required.',
            'features.*.description.max'      => 'Feature description must not exceed 1000 characters.',
            'features.*.icon.required_without' => 'Feature icon is required.',
            'features.*.icon.image'       => 'Feature icon must be a valid image.',
            'features.*.icon.mimes'       => 'Feature icon must be a JPG, PNG, or WEBP file.',
            'features.*.icon.max'         => 'Feature icon must not exceed 10MB.',
        ];
    }
}
