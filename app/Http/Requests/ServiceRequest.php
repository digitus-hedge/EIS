<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

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
            'banner_title'       => ['required', 'string', 'max:55'],
            'banner_description' => ['required', 'string'],
            'banner_image'       => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'banner_video'       => ['nullable', 'mimes:mp4,mov,webm', 'max:20480'],
            'remove_banner_image' => ['nullable', 'boolean'],
            'remove_banner_video' => ['nullable', 'boolean'],

            'show_on_home' => ['nullable', 'boolean'],
            'home_sort_order' => [
                'nullable',
                'integer',
                'min:1',
            ],
                // Overview
            'overview_title'       => ['required', 'string', 'max:50'],
            'overview_description' => ['required', 'string'],
            'overview_image'       => $hasExistingOverview
                ? ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240']
                : ['required', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'remove_overview_image' => ['nullable', 'boolean'],

            // Meta
            'meta_title'       => ['nullable', 'string', 'max:80'],
            'meta_description' => ['nullable', 'string', 'max:200'],

            // Process — thumbnail still required unless an existing one is present.
            // Video is now EITHER an uploaded file OR a YouTube link (checked in withValidator below,
            // since "at least one of three possible sources" isn't expressible with required_without alone).
            'process'                       => ['required', 'array', 'min:1'],
            'process.*.description'         => ['required', 'string', 'max:1000'],
            'process.*.thumbnail'           => ['required_without:process.*.existing_thumbnail', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'process.*.existing_thumbnail'  => ['nullable', 'string'],
            'process.*.video'               => ['nullable', 'mimes:mp4,mov,avi,webm', 'max:20480'],
            'process.*.existing_video'      => ['nullable', 'string'],
      'process.*.vedio_link' => [
    'nullable',
    'url',
    'regex:/^(https?:\/\/)?(www\.)?(youtube\.com\/(watch\?v=|embed\/|shorts\/)|youtu\.be\/)[a-zA-Z0-9_-]{11}([?&].*)?$/',
],
            // Features — icon now genuinely required unless an existing one is present
            'features_heading'         => ['required', 'string', 'max:60'],
            'features'                 => ['required', 'array', 'min:1', 'max:4'],
            'features.*.title'         => ['required', 'string', 'max:60'],
            'features.*.description'   => ['required', 'string', 'max:1000'],
            'features.*.icon'          => ['required_without:features.*.existing_icon', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],
            'features.*.existing_icon' => ['nullable', 'string'],

            'gallery' => 'nullable|array',
            'gallery.*.image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240', // 10MB
            'gallery.*.existing_image' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'banner_title.required'       => 'Please enter a banner title.',
            'banner_title.max'            => 'Banner title must not exceed 55 characters.',

            'banner_description.required' => 'Please enter a banner description.',

            'banner_image.image' => 'The banner image must be a valid image.',
            'banner_image.mimes' => 'The banner image must be a JPG, PNG, or WEBP file.',
            'banner_image.max'   => 'The banner image must not exceed 10MB.',

            'banner_video.mimes' => 'The banner video must be an MP4, MOV, or WEBM file.',
            'banner_video.max'   => 'The banner video must not exceed 20MB.',

            'overview_title.required'       => 'Please enter an overview title.',
            'overview_title.max'            => 'Overview title must not exceed 50 characters.',

            'overview_description.required' => 'Please enter an overview description.',

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
            'process.*.video.mimes'     => 'Process video must be an MP4, MOV, AVI, or WEBM file.',
            'process.*.video.max'       => 'Process video must not exceed 20MB.',
            'process.*.vedio_link.url'      => 'Please enter a valid URL.',
            'process.*.vedio_link.regex'    => 'Please enter a valid YouTube video URL.',

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

            'home_sort_order.integer' => 'Sort order must be a number.',
            'home_sort_order.min'     => 'Sort order must be at least 1.',
            'home_sort_order.unique'  => 'This sort order is already used by another service.',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $service = $this->route('service');

            // ===== Banner Image OR Video: exactly one required, not both, not neither =====
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

            // ===== Process rows: each needs a video source — uploaded file, existing file, OR a YouTube link =====
            $processRows = $this->input('process', []);
            foreach ($processRows as $index => $row) {
                $hasNewVideo = $this->hasFile("process.$index.video");
                $hasExistingVideo = !empty($row['existing_video'] ?? null);
                $hasLink = !empty(trim($row['vedio_link'] ?? ''));

                if (!$hasNewVideo && !$hasExistingVideo && !$hasLink) {
                    $validator->errors()->add("process.$index.video", 'Please upload a process video or provide a YouTube link.');
                }

                if ($hasNewVideo && $hasLink) {
                    $validator->errors()->add("process.$index.vedio_link", 'Please provide either a video file OR a YouTube link, not both.');
                }
            }
        });
    }
}