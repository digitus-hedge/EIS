<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\HomeAbout;

class HomeAboutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'       => 'required|string|min:3|max:70',
            'description' => 'required|string',
            'image'       => 'nullable|image|mimes:jpeg,jpg,png,webp|max:10240',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'       => 'Please enter a title.',
            'title.min'            => 'Title must be at least :min characters.',
            'title.max'            => 'Title cannot exceed :max characters.',

            'description.required' => 'Please enter a description.',
            'description.max' => 'Description must not exceed :max characters.',
            'image.image'          => 'The file must be a valid image.',
            'image.mimes'          => 'Image must be a JPG, PNG, or WEBP file.',
            'image.max'            => 'Image must not exceed 10MB.',
        ];
    }

    public function attributes(): array
    {
        return [
            'title'       => 'title',
            'description' => 'description',
            'image'       => 'image',
        ];
    }

    /**
     * Image required only if there's no existing image already saved (edit mode).
     */
  public function withValidator($validator): void
{
    $validator->after(function ($validator) {
        // ----- Image required (existing check) -----
        $about = HomeAbout::first();

        $hasNewImage = $this->hasFile('image');
        $hasExistingImage = $about && !empty($about->image);

        if (!$hasNewImage && !$hasExistingImage) {
            $validator->errors()->add('image', 'Please upload an image.');
        }

        // ----- Description: validate against plain-text length, not raw HTML -----
        $plainText = trim(strip_tags($this->input('description', '')));

        if ($plainText === '') {
            $validator->errors()->add('description', 'Description is required.');
        } elseif (mb_strlen($plainText) > 1200) {
            $validator->errors()->add('description', 'Description must not exceed 1200 characters.');
        }
    });
}
}
