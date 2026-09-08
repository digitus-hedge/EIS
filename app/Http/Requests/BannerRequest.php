<?php

namespace App\Http\Requests;

use App\Models\Banner;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class BannerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'       => 'required|string|min:3|max:255',
            'description' => 'required|string|max:2000',

            'image_1' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'image_2' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'image_3' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',

            'video'   => 'nullable|mimes:mp4,mov,avi,wmv|max:5120',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'       => 'Please enter a banner title.',
            'title.min'            => 'Title must be at least :min characters.',
            'title.max'            => 'Title cannot exceed :max characters.',

            'description.required' => 'Please enter a banner description.',
            'description.max'      => 'Description cannot exceed :max characters.',

            'image_1.image'        => 'Image 1 must be a valid image file.',
            'image_2.image'        => 'Image 2 must be a valid image file.',
            'image_3.image'        => 'Image 3 must be a valid image file.',

            'image_1.mimes'        => 'Image 1 must be a JPG, PNG, or WEBP file.',
            'image_2.mimes'        => 'Image 2 must be a JPG, PNG, or WEBP file.',
            'image_3.mimes'        => 'Image 3 must be a JPG, PNG, or WEBP file.',

            'image_1.max'          => 'Image 1 must not exceed 2MB.',
            'image_2.max'          => 'Image 2 must not exceed 2MB.',
            'image_3.max'          => 'Image 3 must not exceed 2MB.',

            'video.mimes'          => 'Video must be a file of type: MP4, MOV, AVI, or WMV.',
            'video.max'            => 'Video must not exceed 5MB.',
        ];
    }

    public function attributes(): array
    {
        return [
            'title'       => 'banner title',
            'description' => 'banner description',
            'image_1'     => 'Image 1',
            'image_2'     => 'Image 2',
            'image_3'     => 'Image 3',
            'video'       => 'video',
        ];
    }

    /**
     * Custom conditional validation:
     * Requires AT LEAST ONE media source (Either an image or a video).
     * Works for both creation and editing.
     */
    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator) {
            $banner = Banner::first();

            // Check for newly uploaded images or existing saved images
            $hasNewImage = collect(['image_1', 'image_2', 'image_3'])
                ->contains(fn ($field) => $this->hasFile($field));

            $hasExistingImage = $banner && collect(['image_1', 'image_2', 'image_3'])
                ->contains(fn ($field) => !empty($banner->{$field}));

            $hasImage = $hasNewImage || $hasExistingImage;

            // Check for newly uploaded video or existing saved video
            $hasNewVideo = $this->hasFile('video');
            $hasExistingVideo = $banner && !empty($banner->video);

            $hasVideo = $hasNewVideo || $hasExistingVideo;

            // Validation Rule: If NO image AND NO video exist, show validation errors
            if (!$hasImage && !$hasVideo) {
                $validator->errors()->add('image_1', 'Please upload at least one image or a video for the banner.');
                $validator->errors()->add('video', 'Please upload a video or at least one image for the banner.');
            }
        });
    }
}