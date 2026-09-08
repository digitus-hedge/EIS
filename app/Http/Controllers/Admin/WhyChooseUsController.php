<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\WhyChooseUsRequest;
use App\Models\WhyChooseUs;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class WhyChooseUsController extends Controller
{
    protected int $imageWidth = 700;
    protected int $imageHeight = 800;
    protected int $compressQuality = 70;

    /**
     * SHOW FORM — always the single Why Choose Us section (or empty model if none exists yet)
     */
    public function index()
    {
        $why = WhyChooseUs::first() ?? new WhyChooseUs();
        return view('admin.why-choose-us.form', compact('why'));
    }

    /**
     * STORE — creates the section if none exists, otherwise updates the existing one
     */
    public function store(WhyChooseUsRequest $request)
    {
        $data = $request->validated();

        $why = WhyChooseUs::first() ?? new WhyChooseUs();
        $why->heading = $data['heading'];
        $why->description = $data['description'] ?? null;

        $why->mission_title = $data['mission_title'] ?? null;
        $why->mission_description = $data['mission_description'] ?? null;

        $why->vision_title = $data['vision_title'] ?? null;
        $why->vision_description = $data['vision_description'] ?? null;

        $why->values_title = $data['values_title'] ?? null;
        $why->values_description = $data['values_description'] ?? null;

        $why->commitment_title = $data['commitment_title'] ?? null;
        $why->commitment_description = $data['commitment_description'] ?? null;

        foreach (['mission_image', 'vision_image', 'values_image'] as $field) {
            if ($request->hasFile($field)) {
                if ($why->{$field}) {
                    Storage::disk('public')->delete($why->{$field});
                }
                $why->{$field} = $this->processAndStoreImage($request->file($field));
            }
        }

        $why->save();

        return redirect()
            ->route('admin.home.why-choose-us')
            ->with('success', 'Why Choose Us section saved successfully.');
    }

    private function processAndStoreImage($file): string
    {
        $filename = 'why-choose-us/' . Str::random(20) . '.webp';

        $manager = new ImageManager(new Driver());
        $image = $manager->read($file);
        $image->cover($this->imageWidth, $this->imageHeight);
        $encoded = $image->toWebp(quality: $this->compressQuality);

        Storage::disk('public')->put($filename, (string) $encoded);

        return $filename;
    }
}
