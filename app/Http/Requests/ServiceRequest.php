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
        $isUpdate = $this->isMethod('put') || $this->isMethod('patch');

        return [
            'title'                     => 'required|string|max:255',
            'description'               => 'required|string|max:500',
            'process_description'       => 'required|string',

            'technical_scope'           => 'required|array|min:1',
            'technical_scope.*'         => 'required|string|max:255',

            'specifications'                 => 'required|array|min:1',
            'specifications.*.specification' => 'required|string|max:255',
            'specifications.*.details'       => 'required|string|max:255',
            'specifications.*.compliance'    => 'required|string|max:100',

            'image'        => 'nullable|image|mimes:jpeg,jpg,png,webp|max:10240',
            'banner_image' => 'nullable|image|mimes:jpeg,jpg,png,webp|max:10240',

            'meta_title'       => 'nullable|string|max:60',
            'meta_description' => 'nullable|string|max:160',

            'gallery'          => [$isUpdate ? 'nullable' : 'required', 'array', 'max:6'],
            'gallery.*'        => 'image|mimes:jpeg,jpg,png,webp|max:10240',

            'remove_gallery'   => 'nullable|array',
            'remove_gallery.*' => 'string',

            // ===== Inspection Process: heading + description mandatory per row =====
            // Image mandatory too, but enforced in withValidator() below since it
            // must accept EITHER a new upload OR an existing saved image (on update).
            'inspection_process'                 => 'required|array|min:1',
            'inspection_process.*.heading'       => 'required|string|max:255',
            'inspection_process.*.description'   => 'required|string',
            'inspection_process.*.image'         => 'nullable|image|mimes:jpeg,jpg,png,webp|max:10240',
            'inspection_process.*.existing_image' => 'nullable|string',

            'sort_order'       => 'nullable|integer|min:0',
            'status'           => 'nullable|boolean',
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $service = $this->route('service');

            // ===== Gallery: combined total check =====
            $existingGalleryCount = $service ? count($service->gallery ?? []) : 0;
            $removeCount = count($this->input('remove_gallery', []));
            $newGalleryCount = count($this->file('gallery', []));

            $remainingExisting = max(0, $existingGalleryCount - $removeCount);
            $finalGalleryCount = $remainingExisting + $newGalleryCount;

            if ($finalGalleryCount > 6) {
                $validator->errors()->add(
                    'gallery',
                    "Total gallery images cannot exceed 6. You currently have {$remainingExisting} remaining plus {$newGalleryCount} new (total {$finalGalleryCount})."
                );
            }

            // ===== Hero image: must exist either as upload or already saved =====
            $hasExistingImage = $service && $service->image;
            $hasNewImageUpload = $this->hasFile('image');

            if (!$hasExistingImage && !$hasNewImageUpload) {
                $validator->errors()->add('image', 'The hero/card image field is required.');
            }

            // ===== Banner image: must exist either as upload or already saved =====
            $hasExistingBanner = $service && $service->banner_image;
            $hasNewBannerUpload = $this->hasFile('banner_image');

            if (!$hasExistingBanner && !$hasNewBannerUpload) {
                $validator->errors()->add('banner_image', 'The banner image field is required.');
            }

            // ===== Inspection Process: each row's image must exist (new upload OR existing kept) =====
            $inspectionRows = $this->input('inspection_process', []);
            $inspectionFiles = $this->file('inspection_process', []);

            foreach ($inspectionRows as $index => $row) {
                $hasNewImage = isset($inspectionFiles[$index]['image']) && $inspectionFiles[$index]['image'] !== null;
                $hasExistingImage = !empty($row['existing_image']);

                if (!$hasNewImage && !$hasExistingImage) {
                    $validator->errors()->add(
                        "inspection_process.$index.image",
                        'An image is required for each inspection process step (row ' . ($index + 1) . ').'
                    );
                }
            }
        });
    }
}