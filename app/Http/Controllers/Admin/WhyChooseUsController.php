<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WhyChooseUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class WhyChooseUsController extends Controller
{
    public function index()
    {
        $why = WhyChooseUs::first() ?? new WhyChooseUs();
        return view('admin.why-choose-us.form', compact('why'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'heading'                => 'required|string|max:255',
            'description'            => 'required|string|max:1000',
            'items'                  => 'required|array|min:1|max:6',
            'items.*.title'          => 'required|string|max:255',
            'items.*.subheading'     => 'required|string|max:255',
            'items.*.description'    => 'required|string|max:1000',
            'items.*.image'          => 'required_without:items.*.existing_image|nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
            'items.*.existing_image' => 'nullable|string',
        ], [
            'heading.required'     => 'Please enter the main heading.',
            'heading.max'          => 'Heading must not exceed 255 characters.',

            'description.required' => 'Please enter the main description.',
            'description.max'      => 'Description must not exceed 1000 characters.',

            'items.required' => 'Please add at least one item.',
            'items.min'       => 'Please add at least one item.',
            'items.max'       => 'You can add a maximum of 6 items.',

            'items.*.title.required'       => 'Please enter a title for item :position.',
            'items.*.title.max'            => 'Title for item :position must not exceed 255 characters.',
            'items.*.subheading.required'  => 'Please enter a subheading for item :position.',
            'items.*.subheading.max'       => 'Subheading for item :position must not exceed 255 characters.',
            'items.*.description.required' => 'Please enter a description for item :position.',
            'items.*.description.max'      => 'Description for item :position must not exceed 1000 characters.',

            'items.*.image.required_without' => 'Please upload an image for item :position.',
            'items.*.image.image'            => 'The file for item :position must be a valid image.',
            'items.*.image.mimes'            => 'The image for item :position must be a JPG, PNG, or WEBP file.',
            'items.*.image.max'              => 'The image for item :position must not exceed 10MB.',
        ]);

        $why = WhyChooseUs::first() ?? new WhyChooseUs();
        $why->heading     = $validated['heading'];
        $why->description = $validated['description'];

        $items = [];
        foreach ($request->input('items', []) as $index => $itemData) {
            $image = $itemData['existing_image'] ?? null;

            if ($request->hasFile("items.$index.image")) {
                if (!empty($image)) {
                    Storage::disk('public')->delete($image);
                }
                $image = $request->file("items.$index.image")->store('why-choose-us', 'public');
            }

            $items[] = [
                'title'       => $itemData['title'],
                'subheading'  => $itemData['subheading'],
                'description' => $itemData['description'],
                'image'       => $image,
            ];
        }

        // Delete images belonging to items that were removed in this save
        $oldImages = collect($why->items ?? [])->pluck('image')->filter();
        $newImages = collect($items)->pluck('image')->filter();
        foreach ($oldImages->diff($newImages) as $removed) {
            Storage::disk('public')->delete($removed);
        }

        $why->items = $items;
        $why->save();

        return redirect()
            ->route('admin.home.why-choose-us')
            ->with('success', 'Why Choose Us section saved successfully.');
    }
}