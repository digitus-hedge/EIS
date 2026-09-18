<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceSectionRequest;
use App\Models\ServiceSection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ServiceSectionController extends Controller
{
    protected int $imageWidth = 1200;
    protected int $imageHeight = 900;   // 4:3 ratio — matches existing frontend aspect-ratio
    protected int $compressQuality = 100;

    public function index()
    {
        $serviceSection = ServiceSection::first() ?? new ServiceSection();

        return view('admin.service_section.form', compact('serviceSection'))
            ->with([
                'imageWidth'  => $this->imageWidth,
                'imageHeight' => $this->imageHeight,
            ]);
    }

    public function store(ServiceSectionRequest $request)
    {
        $data = $request->validated();

        $serviceSection = ServiceSection::first() ?? new ServiceSection();

        unset($data['image']);
        $serviceSection->fill($data);

        if ($request->hasFile('image')) {
            if (!empty($serviceSection->image)) {
                Storage::disk('public')->delete($serviceSection->image);
            }

            $serviceSection->image = $this->processAndStoreImage($request->file('image'));
        }

        $serviceSection->save();

        return redirect()
            ->route('admin.home.services.section')
            ->with('success', 'Service section saved successfully.');
    }

    private function processAndStoreImage($file): string
    {
        $filename = 'service-section/' . Str::random(20) . '.webp';

        $manager = new ImageManager(new Driver());
        $image = $manager->read($file->getPathname());

        // No cropping — fit inside the target box, matches frontend "no crop" goal
        $image->contain($this->imageWidth, $this->imageHeight, background: 'ffffff');

        $encoded = $image->toWebp(quality: $this->compressQuality);
        Storage::disk('public')->put($filename, (string) $encoded);

        return $filename;
    }
}