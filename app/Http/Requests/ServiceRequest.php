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
        $hasExistingOverview = $service && $service->overview_image;

        return [
            // Banner
            'banner_title'        => ['required', 'string', 'max:55'],
            'banner_description'  => ['required', 'string'],
            'banner_image'        => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'banner_video'        => ['nullable', 'mimes:mp4,mov,webm', 'max:20480'],
            'remove_banner_image' => ['nullable', 'boolean'],
            'remove_banner_video' => ['nullable', 'boolean'],

            'show_on_home'    => ['nullable', 'boolean'],
            'home_sort_order' => ['nullable', 'integer', 'min:1'],

            // Overview (single image)
            'overview_title'        => ['required', 'string', 'max:50'],
            'overview_description'  => ['required', 'string'],
            'overview_image'        => $hasExistingOverview
                ? ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240']
                : ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'remove_overview_image' => ['nullable', 'boolean'],

            // Meta
            'meta_title'       => ['nullable', 'string', 'max:80'],
            'meta_description' => ['nullable', 'string', 'max:200'],

            // Process (title + CKEditor description + multiple images)
            // "At least one image" is checked in withValidator()
            'process_title'             => ['required', 'string', 'max:50'],
            'process_description'       => ['required', 'string'],
            'process_image'             => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'process_images'            => ['nullable', 'array', 'max:20'],
            'process_images.*'          => ['image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'existing_process_images'   => ['nullable', 'array'],
            'existing_process_images.*' => ['nullable', 'string'],
            'remove_process_image'      => ['nullable', 'boolean'],

            // Features
            'features_heading'         => ['required', 'string', 'max:60'],
            'features'                 => ['required', 'array', 'min:1', 'max:4'],
            'features.*.title'         => ['required', 'string', 'max:60'],
            'features.*.description'   => ['required', 'string', 'max:1000'],
            'features.*.icon'          => ['required_without:features.*.existing_icon', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'features.*.existing_icon' => ['nullable', 'string'],

            // Gallery
            'gallery'                  => ['nullable', 'array'],
            'gallery.*.image'          => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'gallery.*.existing_image' => ['nullable', 'string'],
        ];
    }

    public function messages(): array
    {
        return [
            'banner_title.required' => 'Please enter a banner title.',
            'banner_title.max'      => 'Banner title must not exceed 55 characters.',

            'banner_description.required' => 'Please enter a banner description.',

            'banner_image.image' => 'The banner image must be a valid image.',
            'banner_image.mimes' => 'The banner image must be a JPG, PNG, or WEBP file.',
            'banner_image.max'   => 'The banner image must not exceed 10MB.',

            'banner_video.mimes' => 'The banner video must be an MP4, MOV, or WEBM file.',
            'banner_video.max'   => 'The banner video must not exceed 20MB.',

            'overview_title.required' => 'Please enter an overview title.',
            'overview_title.max'      => 'Overview title must not exceed 50 characters.',

            'overview_description.required' => 'Please enter an overview description.',

            'overview_image.required' => 'Please upload an overview image.',
            'overview_image.image'    => 'The overview image must be a valid image.',
            'overview_image.mimes'    => 'The overview image must be a JPG, PNG, or WEBP file.',
            'overview_image.max'      => 'The overview image must not exceed 10MB.',

            'process_title.required'        => 'Please enter a process title.',
            'process_title.max'             => 'Process title must not exceed 50 characters.',
            'process_description.required'  => 'Please enter a process description.',
            'process_image.image'           => 'The process image must be a valid image.',
            'process_image.mimes'           => 'The process image must be a JPG, PNG, or WEBP file.',
            'process_image.max'             => 'The process image must not exceed 10MB.',
            'process_images.max'            => 'You can add a maximum of 20 process images.',
            'process_images.*.image'        => 'Each process image must be a valid image.',
            'process_images.*.mimes'        => 'Process images must be JPG, PNG, or WEBP files.',
            'process_images.*.max'          => 'Each process image must not exceed 10MB.',

            'features_heading.required' => 'Please enter a features heading.',
            'features_heading.max'      => 'Features heading must not exceed 60 characters.',

            'features.required' => 'Please add at least one feature.',
            'features.min'      => 'Please add at least one feature.',
            'features.max'      => 'You can add a maximum of 4 features.',
            'features.*.title.required'        => 'Feature title is required.',
            'features.*.title.max'             => 'Feature title must not exceed 60 characters.',
            'features.*.description.required'  => 'Feature description is required.',
            'features.*.description.max'       => 'Feature description must not exceed 1000 characters.',
            'features.*.icon.required_without' => 'Feature icon is required.',
            'features.*.icon.image'            => 'Feature icon must be a valid image.',
            'features.*.icon.mimes'            => 'Feature icon must be a JPG, PNG, or WEBP file.',
            'features.*.icon.max'              => 'Feature icon must not exceed 10MB.',

            'gallery.*.image.image' => 'Each gallery image must be a valid image.',
            'gallery.*.image.mimes' => 'Gallery images must be JPG, PNG, or WEBP files.',
            'gallery.*.image.max'   => 'Each gallery image must not exceed 10MB.',

            'home_sort_order.integer' => 'Sort order must be a number.',
            'home_sort_order.min'     => 'Sort order must be at least 1.',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $service = $this->route('service');

            // ===== Banner Image OR Video: exactly one required =====
            $hasNewBannerImage = $this->hasFile('banner_image');
            $hasNewBannerVideo = $this->hasFile('banner_video');

            $hasExistingBannerImage = $service
                && !empty($service->banner_image)
                && !$this->boolean('remove_banner_image');

            $hasExistingBannerVideo = $service
                && !empty($service->banner_video)
                && !$this->boolean('remove_banner_video');

            $willHaveBannerImage = $hasNewBannerImage || $hasExistingBannerImage;
            $willHaveBannerVideo = $hasNewBannerVideo || $hasExistingBannerVideo;

            if (!$willHaveBannerImage && !$willHaveBannerVideo) {
                $validator->errors()->add('banner_image', 'Please upload either a Banner Image or a Banner Video.');
            }

            if ($willHaveBannerImage && $willHaveBannerVideo) {
                $validator->errors()->add('banner_image', 'Please choose only one — a Banner Image OR a Banner Video, not both.');
            }

            // ===== Process: at least one image (new uploads or kept ones) =====
            $newProcess = count(array_filter((array) $this->file('process_images', [])))
                + ($this->hasFile('process_image') ? 1 : 0);

            if ($this->has('existing_process_images')) {
                $keptProcess = count(array_filter((array) $this->input('existing_process_images', [])));
            } else {
                // Old form (no list sent): existing images are kept as they are
                $keptProcess = $service
                    ? count(array_filter(array_merge((array) ($service->process_images ?? []), [$service->process_image])))
                    : 0;
            }

            if ($newProcess + $keptProcess === 0) {
                $validator->errors()->add('process_images', 'Please upload at least one process image.');
            }
        });
    }
}