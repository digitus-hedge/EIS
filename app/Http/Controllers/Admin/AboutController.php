<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutUs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use App\Models\RegionalLocation;
use App\Models\RegionalOffice;
use Illuminate\Support\Facades\DB;
use App\Models\OperationVideo;

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
 */
public function regionalFootprint()
{
    $locations = RegionalLocation::with('offices')
        ->orderBy('sort_order')
        ->get();

    return view('admin.about.regional-footprint', compact('locations'));
}

/**
 * Store a new location (from map search) with its offices.
 */
public function storeRegionalLocation(Request $request)
{
    ini_set('memory_limit', '512M');

    $request->validate([
        'title'                 => 'required|string|max:255',
        'address'               => 'nullable|string|max:255',
        'latitude'              => 'required|numeric',
        'longitude'             => 'required|numeric',
        'place_id'              => 'nullable|string|max:255',
        'offices'               => 'required|array|min:1',
        'offices.*.title'       => 'required|string|max:255',
        'offices.*.description' => 'nullable|string',
        'offices.*.image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
    ]);

    try {
        DB::beginTransaction();

        $location = RegionalLocation::create([
            'title'      => $request->title,
            'address'    => $request->address,
            'latitude'   => $request->latitude,
            'longitude'  => $request->longitude,
            'place_id'   => $request->place_id,
            'sort_order' => RegionalLocation::max('sort_order') + 1,
        ]);

        foreach ($request->offices as $index => $officeData) {
            $imagePath = null;

            if ($request->hasFile("offices.$index.image")) {
                $imagePath = $this->processAndStoreImage(
                    $request->file("offices.$index.image"),
                    'about/regional-offices'
                );
            }

            RegionalOffice::create([
                'regional_location_id' => $location->id,
                'title'                => $officeData['title'],
                'description'          => $officeData['description'] ?? null,
                'image'                => $imagePath,
                'sort_order'           => $index,
            ]);
        }

        DB::commit();
        gc_collect_cycles();

        return redirect()
            ->route('admin.about.regional-footprint')
            ->with('success', 'Location and offices added successfully.');

    } catch (\Throwable $e) {
        DB::rollBack();

        \Log::error('Regional location store() FAILED', [
            'message' => $e->getMessage(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
        ]);

        return back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
    }
}

/**
 * Add another office to an existing location.
 */
public function storeRegionalOffice(Request $request, RegionalLocation $location)
{
    ini_set('memory_limit', '512M');

    $request->validate([
        'title'       => 'required|string|max:255',
        'description' => 'nullable|string',
        'image'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
    ]);

    try {
        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $this->processAndStoreImage($request->file('image'), 'about/regional-offices');
        }

        RegionalOffice::create([
            'regional_location_id' => $location->id,
            'title'                => $request->title,
            'description'          => $request->description,
            'image'                => $imagePath,
            'sort_order'           => $location->offices()->max('sort_order') + 1,
        ]);

        gc_collect_cycles();

        return redirect()
            ->route('admin.about.regional-footprint')
            ->with('success', 'Office added successfully.');

    } catch (\Throwable $e) {
        \Log::error('Regional office store() FAILED', [
            'message' => $e->getMessage(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
        ]);

        return back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
    }
}

public function destroyRegionalLocation(RegionalLocation $location)
{
    foreach ($location->offices as $office) {
        if ($office->image) {
            Storage::disk('public')->delete($office->image);
        }
    }

    $location->delete(); // offices cascade-delete via FK

    return back()->with('success', 'Location removed.');
}

public function destroyRegionalOffice(RegionalOffice $office)
{
    $office->delete(); // image deleted via model event

    return back()->with('success', 'Office removed.');
}

  /**
 * Show the "Operation" page.
 * Route: GET /admin/operation -> admin.about.operation
 */
public function operation()
{
    $videos = OperationVideo::orderByDesc('is_main')->orderBy('id')->get();

    return view('admin.about.operation', compact('videos'));
}

/**
 * Store a new operation video (main hero video or a carousel clip).
 */
public function storeOperationVideo(Request $request)
{
    ini_set('memory_limit', '512M');

    $request->validate([
        'title'       => 'required|string|max:255',
        'description' => 'nullable|string',
        'thumbnail'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120',
        'video'       => 'nullable|mimes:mp4,mov,webm|max:51200', // 50MB
        'is_main'     => 'nullable|boolean',
    ]);

    try {
        $isMain = $request->boolean('is_main');

        // Only one main hero video allowed — demote any existing one
        if ($isMain) {
            OperationVideo::where('is_main', true)->update(['is_main' => false]);
        }

        $thumbnailPath = null;
        if ($request->hasFile('thumbnail')) {
            $thumbnailPath = $this->processAndStoreImage($request->file('thumbnail'), 'operation/thumbnails');
        }

        $videoPath = null;
        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store('operation/videos', 'public');
        }

        OperationVideo::create([
            'title'       => $request->title,
            'description' => $request->description,
            'thumbnail'   => $thumbnailPath,
            'video'       => $videoPath,
            'is_main'     => $isMain,
        ]);

        gc_collect_cycles();

        return redirect()
            ->route('admin.about.operation')
            ->with('success', 'Video added successfully.');

    } catch (\Throwable $e) {
        \Log::error('Operation video store() FAILED', [
            'message' => $e->getMessage(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
        ]);

        return back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
    }
}

public function destroyOperationVideo(OperationVideo $video)
{
    $video->delete(); // thumbnail + video file deleted via model event

    return back()->with('success', 'Video removed.');
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