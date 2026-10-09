<?php

namespace App\Http\Requests;

use App\Models\CareerPage;
use Illuminate\Foundation\Http\FormRequest;

/** Validation for Admin > Career > Banner. */
class CareerBannerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // the route is already behind the admin login
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'banner_title'       => trim((string) $this->input('banner_title')),
            'banner_description' => trim((string) $this->input('banner_description')),
            'career_title'       => trim((string) $this->input('career_title')),
            'career_description' => trim((string) $this->input('career_description')),
            'meta_title'         => trim((string) $this->input('meta_title')),
            'meta_description'   => trim((string) $this->input('meta_description')),
        ]);
    }

    public function rules(): array
    {
        return [
            'banner_title'        => ['required', 'string', 'max:255'],
            'banner_description'  => ['nullable', 'string', 'max:1000'],
            'career_title'        => ['required', 'string', 'max:255'],
            'career_description'  => ['required', 'string', 'max:5000'],
            'banner'              => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:10240'],                                   // 10 MB
            'banner_video'        => ['nullable', 'file', 'mimetypes:video/mp4,video/quicktime,video/webm', 'max:20480'],          // 20 MB
            'remove_banner'       => ['nullable', 'boolean'],
            'remove_banner_video' => ['nullable', 'boolean'],
            'meta_title'          => ['nullable', 'string', 'max:60'],
            'meta_description'    => ['nullable', 'string', 'max:160'],
        ];
    }

    /** "Banner Image or Video *": after saving, at least one of the two must be there. */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($validator->errors()->hasAny(['banner', 'banner_video'])) {
                return;
            }

            $page = CareerPage::first();

            $imageStays = $this->hasFile('banner') || (! empty($page?->banner) && ! $this->boolean('remove_banner'));
            $videoStays = $this->hasFile('banner_video') || (! empty($page?->banner_video) && ! $this->boolean('remove_banner_video'));

            if (! $imageStays && ! $videoStays) {
                $validator->errors()->add('banner', 'Please upload a banner image or a banner video.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'banner_title.required' => 'Please enter the banner title.',
            'banner_description.max' => 'The banner description can be at most 1000 characters.',
            'career_title.required' => 'Please enter the career title.',
            'career_description.required' => 'Please enter the career description.',
            'career_description.max' => 'The career description can be at most 5000 characters.',
            'banner.image'          => 'The banner image must be a JPG, PNG or WEBP image.',
            'banner.mimes'          => 'The banner image must be a JPG, PNG or WEBP image.',
            'banner.max'            => 'The banner image must be 10 MB or less.',
            'banner.uploaded'       => 'The banner image could not be uploaded. Please use a file of 10 MB or less.',
            'banner_video.mimetypes' => 'The banner video must be an MP4, MOV or WEBM file.',
            'banner_video.max'      => 'The banner video must be 20 MB or less.',
            'banner_video.uploaded' => 'The banner video could not be uploaded. Please use a file of 20 MB or less.',
        ];
    }
}
