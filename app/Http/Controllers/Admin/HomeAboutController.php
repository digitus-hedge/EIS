<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\HomeAboutRequest;
use App\Models\HomeAbout;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Format;

class HomeAboutController extends Controller
{
    protected int $imageWidth = 1200;
    protected int $imageHeight = 1080;
    protected int $compressQuality = 100;

    /**
     * SHOW FORM — always the single About section (or empty model if none exists yet)
     */
    public function index()
    {
        $about = HomeAbout::first() ?? new HomeAbout();

        return view('admin.home-about.form', compact('about'))
            ->with([
                'imageWidth'  => $this->imageWidth,
                'imageHeight' => $this->imageHeight,
            ]);
    }
    /**
     * STORE — creates the About section if none exists, otherwise updates the existing one
     */
    public function store(HomeAboutRequest $request)
    {
        $data = $request->validated();

        $about = HomeAbout::first() ?? new HomeAbout();
        $about->title = $data['title'];
        $about->description = $data['description'];

        if ($request->hasFile('image')) {
            if ($about->image) {
                Storage::disk('public')->delete($about->image);
            }
            $about->image = $this->processAndStoreImage($request->file('image'));
        }

        $about->save();

        return redirect()
            ->route('admin.home.about')
            ->with('success', 'About section saved successfully.');
    }

    private function processAndStoreImage($file): string
    {
        $filename = 'home-about/' . Str::random(20) . '.webp';

        $manager = new ImageManager(new Driver());
        $image = $manager->read($file->getPathname());

        // No cover(), no contain() — save as-is, just convert format
        $encoded = $image->toWebp(quality: $this->compressQuality);
        Storage::disk('public')->put($filename, (string) $encoded);

        return $filename;
    }
}
