<?php

namespace App\Http\Requests;

use App\Models\ClientSection;
use Illuminate\Foundation\Http\FormRequest;

class ClientSectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title'          => 'required|string|max:255',
            'description'    => 'required|string|max:1000',
            'images'         => 'nullable|array',
            'images.*'       => 'nullable|image|mimes:jpeg,jpg,png,webp|max:2048',
            'remove_images'  => 'nullable|array',
        ];
    }

    public function messages(): array
    {
        return [
            'title.required'       => 'Please enter a title.',
            'title.max'            => 'Title cannot exceed 255 characters.',

            'description.required' => 'Please enter a description.',
            'description.max'      => 'Description cannot exceed 1000 characters.',

            'images.*.image'       => 'Each file must be a valid image file.',
            'images.*.mimes'       => 'Images must be in JPG, PNG, or WEBP format.',
            'images.*.max'         => 'Each image must not exceed 2MB in size.',
        ];
    }

    /**
     * Custom validation logic to ensure at least one image remains
     * across stored images, removals, and new uploads.
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $client = ClientSection::first();

            // 1. Get existing stored images from DB
            $existingImages = $client->images ?? [];

            // 2. Subtract images checked for removal
            $removedImages = $this->input('remove_images', []);
            $remainingExisting = array_diff($existingImages, $removedImages);

            // 3. Count newly uploaded files
            $newImagesCount = 0;
            if ($this->hasFile('images')) {
                $newImagesCount = count(array_filter($this->file('images')));
            }

            // 4. Validate total images available
            $totalImages = count($remainingExisting) + $newImagesCount;

            if ($totalImages < 1) {
                $validator->errors()->add('images', 'Please upload at least one client logo/image.');
            }
        });
    }
}