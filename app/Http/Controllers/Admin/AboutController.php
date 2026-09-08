<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class AboutController extends Controller
{
    protected int $imageWidth = 1200;
    protected int $imageHeight = 600;
    protected int $compressQuality = 70;

    /**
     * SHOW FORM — always the single about_us row (or empty model if none exists yet)
     * Route: GET /admin/banner -> admin.about.banner
     */
    public function banner()
    {
        $about = AboutUs::first() ?? new AboutUs();

        return view('admin.about.banner', compact('about'));
    }

    /**
     * STORE — creates the about_us row if none exists, otherwise updates the existing one
     * Route: POST /admin/banner -> admin.about.banner.store
     */
    public function storeBanner(Request $request)
    {
        ini_set('memory_limit', '512M');

        $request->validate([
            'title'  => 'required|string|max:255',
            'banner' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        try {
            $about = AboutUs::first() ?? new AboutUs();
            $about->title = $request->title;

            if ($request->hasFile('banner')) {
                if ($about->banner) {
                    Storage::disk('public')->delete($about->banner);
                }

                $about->banner = $this->processAndStoreImage($request->file('banner'));

                gc_collect_cycles();
            }

            $about->save();

            return redirect()
                ->route('admin.about.banner')
                ->with('success', 'Banner saved successfully.');

        } catch (\Throwable $e) {
            \Log::error('About banner store() FAILED', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    private function processAndStoreImage($file, string $folder = 'about/banners'): string
    {
        $filename = $folder . '/' . Str::random(20) . '.webp';

        $manager = new ImageManager(new Driver());

        $image = $manager->read($file->getPathname());

        $image->cover($this->imageWidth, $this->imageHeight);

        $encoded = $image->toWebp(quality: $this->compressQuality);

        Storage::disk('public')->put($filename, (string) $encoded);

        return $filename;
    }

    /**
     * Show the "About" content page (title + description).
     * Route: GET /admin/about -> admin.about.about
     */
    public function about()
    {
        $about = AboutUs::first() ?? new AboutUs();

        return view('admin.about.about', compact('about'));
    }

    /**
     * Store/update the About title + description.
     * Route: POST /admin/about -> admin.about.about.store
     */
    public function storeAbout(Request $request)
    {
        $request->validate([
            'about_title' => 'required|string|max:255',
            'about_desc'  => 'required|string',
        ]);

        try {
            $about = AboutUs::first() ?? new AboutUs();
            $about->about_title = $request->about_title;
            $about->about_desc  = $request->about_desc;
            $about->save();

            return redirect()
                ->route('admin.about.about')
                ->with('success', 'About section saved successfully.');

        } catch (\Throwable $e) {
            \Log::error('About section store() FAILED', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    /**
     * Show the "Who We Are" page.
     * Route: GET /admin/who-we-are -> admin.about.who-we-are
     */
    public function whoWeAre()
    {
        $about = AboutUs::first() ?? new AboutUs();

        return view('admin.about.who-we-are', compact('about'));
    }

    /**
     * Store/update the Who We Are description + image.
     * Route: POST /admin/who-we-are -> admin.about.who-we-are.store
     */
    public function storeWhoWeAre(Request $request)
    {
        ini_set('memory_limit', '512M');

        $request->validate([
            'who_we_are_desc' => 'required|string',
            'image'           => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        ]);

        try {
            $about = AboutUs::first() ?? new AboutUs();
            $about->who_we_are_desc = $request->who_we_are_desc;

            if ($request->hasFile('image')) {
                if ($about->image) {
                    Storage::disk('public')->delete($about->image);
                }

                $about->image = $this->processAndStoreImage($request->file('image'), 'about/who-we-are');

                gc_collect_cycles();
            }

            $about->save();

            return redirect()
                ->route('admin.about.who-we-are')
                ->with('success', 'Who We Are section saved successfully.');

        } catch (\Throwable $e) {
            \Log::error('Who We Are store() FAILED', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }

    /**
     * Show the "Regional Footprint" page.
     * Route: GET /admin/about/regional-footprint -> admin.about.regional-footprint
     */
    public function regionalFootprint()
    {
        return view('admin.about.regional-footprint');
    }

    /**
     * Show the "Operation" page.
     * Route: GET /admin/about/operation -> admin.about.operation
     */
    public function operation()
    {
        return view('admin.about.operation');
    }

    /**
     * Show the "Why Choose Us" page.
     * Route: GET /admin/about/why-choose-us -> admin.about.why-choose-us
     */
    public function whyChooseUs()
    {
        return view('admin.about.why-choose-us');
    }
}