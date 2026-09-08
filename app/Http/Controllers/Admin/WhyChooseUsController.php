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
            'heading'                => 'required|string',
            'description'            => 'required|string',
            'items'                  => 'required|array|min:1|max:6',
            'items.*.title'          => 'required|string|max:255',
            'items.*.subheading'     => 'nullable|string|max:255',
            'items.*.description'    => 'required|string',
            'items.*.image'          => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'items.*.existing_image' => 'nullable|string',
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
                'subheading'  => $itemData['subheading'] ?? '',
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