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
use Illuminate\Validation\Rule;
class AboutController extends Controller
{
    protected int $imageWidth = 1200;
    protected int $imageHeight = 600;
    protected int $compressQuality = 100;

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
    $about = AboutUs::first() ?? new AboutUs();

    $request->validate([
        'title'        => 'required|string|max:60',
        'banner'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
        'banner_video' => 'nullable|mimes:mp4,mov,webm|max:20480',
        'remove_banner' => 'nullable|boolean',
        'remove_banner_video' => 'nullable|boolean',
    ], [
        'title.required'  => 'Please enter a title.',
        'title.max'       => 'Title must not exceed 60 characters.',

        'banner.image'    => 'The file must be a valid image.',
        'banner.mimes'    => 'The banner image must be a JPG, PNG, or WEBP file.',
        'banner.max'      => 'The banner image must not exceed 10MB.',

        'banner_video.mimes' => 'The banner video must be an MP4, MOV, or WEBM file.',
        'banner_video.max'   => 'The banner video must not exceed 20MB.',
    ]);

    // ----- Either image or video required -----
    $hasNewImage = $request->hasFile('banner');
    $hasNewVideo = $request->hasFile('banner_video');
    $hasExistingImage = $about->banner && !$request->boolean('remove_banner');
    $hasExistingVideo = $about->banner_video && !$request->boolean('remove_banner_video');

    if (!($hasNewImage || $hasExistingImage) && !($hasNewVideo || $hasExistingVideo)) {
        $message = 'Please upload either a Banner Image or a Banner Video.';

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'message' => $message,
                'errors' => [
                    'banner' => [$message],
                ],
            ], 422);
        }

        return back()->withInput()->withErrors([
            'banner' => $message,
        ]);
    }

    try {
        $about->title = $request->title;

        // ----- Banner Image -----
        if ($request->hasFile('banner')) {
            if ($about->banner) {
                Storage::disk('public')->delete($about->banner);
            }
            $about->banner = $this->processAndStoreImage($request->file('banner'));

            if ($about->banner_video) {
                Storage::disk('public')->delete($about->banner_video);
                $about->banner_video = null;
            }

            gc_collect_cycles();
        } elseif ($request->boolean('remove_banner')) {
            if ($about->banner) {
                Storage::disk('public')->delete($about->banner);
            }
            $about->banner = null;
        }

        // ----- Banner Video -----
        if ($request->hasFile('banner_video')) {
            if ($about->banner_video) {
                Storage::disk('public')->delete($about->banner_video);
            }
            $about->banner_video = $request->file('banner_video')->store('about/banner-videos', 'public');

            if ($about->banner) {
                Storage::disk('public')->delete($about->banner);
                $about->banner = null;
            }
        } elseif ($request->boolean('remove_banner_video')) {
            if ($about->banner_video) {
                Storage::disk('public')->delete($about->banner_video);
            }
            $about->banner_video = null;
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
            'about_title' => 'required|string|max:75',
            'about_desc'  => 'required|string|max:550',
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
    $about = AboutUs::first() ?? new AboutUs();

    $request->validate([
        'who_we_are_desc'             => 'required|string|max:1000',
        'who_we_are_meta_title'       => 'required|string|max:80',
        'who_we_are_meta_description' => 'required|string|max:200',
        'image'                       => $about->image
                                            ? 'nullable|image|mimes:jpg,jpeg,png,webp|max:5120'
                                            : 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
    ], [
        'who_we_are_desc.required' => 'Please enter the who we are description.',
        'who_we_are_desc.max'      => 'Description must not exceed 1000 characters.',

        'who_we_are_meta_title.required'       => 'Please enter a meta title.',
        'who_we_are_meta_title.max'            => 'Meta title must not exceed 255 characters.',
        'who_we_are_meta_description.required' => 'Please enter a meta description.',
        'who_we_are_meta_description.max'      => 'Meta description must not exceed 500 characters.',

        'image.required' => 'Please upload an image.',
        'image.image'    => 'The file must be a valid image.',
        'image.mimes'    => 'The image must be a JPG, PNG, or WEBP file.',
        'image.max'      => 'The image must not exceed 5MB.',
    ]);

    try {
        $about->who_we_are_desc             = $request->who_we_are_desc;
        $about->who_we_are_meta_title       = $request->who_we_are_meta_title;
        $about->who_we_are_meta_description = $request->who_we_are_meta_description;

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
  $data = $request->validate([
    'title'                 => 'required|string|max:70',
    'address'               => 'nullable|string|max:255',
    'latitude'              => 'required|numeric',
    'longitude'             => 'required|numeric',
    'place_id'              => 'nullable|string|max:255',
    'offices'               => 'required|array|min:1',
    'offices.*.title'       => 'required|string|max:255',
    'offices.*.description' => 'required|string',
    'offices.*.image'       => 'required|image|mimes:jpg,jpeg,png,webp|max:10240',
], [
    'title.required'    => 'Please enter a location title.',
    'title.max'         => 'Location title must not exceed 70 characters.',

    'address.max'       => 'Address must not exceed 255 characters.',

    'latitude.required'  => 'Please select a location on the map.',
    'latitude.numeric'   => 'Latitude must be a valid number.',
    'longitude.required' => 'Please select a location on the map.',
    'longitude.numeric'  => 'Longitude must be a valid number.',

    'offices.required' => 'Please add at least one office.',
    'offices.min'      => 'Please add at least one office.',

    'offices.*.title.required' => 'Office title is required.',
    'offices.*.title.max'      => 'Office title must not exceed 255 characters.',

    'offices.*.description.required' => 'Office description is required.',

    'offices.*.image.required' => 'Office image is required.',
    'offices.*.image.image' => 'Office image must be a valid image.',
    'offices.*.image.mimes' => 'Office image must be a JPG, PNG, or WEBP file.',
    'offices.*.image.max'   => 'Office image must not exceed 10MB.',
]);

    try {
        DB::beginTransaction();

        $location = RegionalLocation::create([
            'title'      => $data['title'],
            'address'    => $data['address'] ?? null,
            'latitude'   => $data['latitude'],
            'longitude'  => $data['longitude'],
            'place_id'   => $data['place_id'] ?? null,
            'sort_order' => RegionalLocation::max('sort_order') + 1,
        ]);

        foreach ($data['offices'] as $index => $officeData) {
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
    $request->validate([
        'title'       => 'required|string|max:40',
        'description' => 'required|string',
        'image'       => 'required|image|mimes:jpg,jpeg,png,webp|max:10240',
    ], [
        'title.required' => 'Please enter an office title.',
        'title.max'      => 'Office title must not exceed 40 characters.',

        'description.required' => 'Please enter an office description.',

        'image.required' => 'Please upload an office image.',
        'image.image'    => 'The file must be a valid image.',
        'image.mimes'    => 'The image must be a JPG, PNG, or WEBP file.',
        'image.max'      => 'The image must not exceed 10MB.',
    ]);

    try {
        $imagePath = $this->processAndStoreImage($request->file('image'), 'about/regional-offices');

        RegionalOffice::create([
            'regional_location_id' => $location->id,
            'title'                => $request->title,
            'description'          => $request->description,
            'image'                => $imagePath,
            'sort_order'           => $location->offices()->max('sort_order') + 1,
        ]);

        gc_collect_cycles();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Office added successfully.',
            ]);
        }

        return redirect()
            ->route('admin.about.regional-footprint')
            ->with('success', 'Office added successfully.');

    } catch (\Throwable $e) {
        \Log::error('Regional office store() FAILED', [
            'message' => $e->getMessage(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
        ]);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Something went wrong.'], 500);
        }

        return back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
    }
}

public function updateRegionalOffice(Request $request, RegionalOffice $office)
{
    $request->validate([
        'title'       => 'required|string|max:255',
        'description' => 'required|string',
        'image'       => $office->image
                            ? 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240'
                            : 'required|image|mimes:jpg,jpeg,png,webp|max:10240',
    ], [
        'title.required' => 'Please enter an office title.',
        'title.max'      => 'Office title must not exceed 255 characters.',

        'description.required' => 'Please enter an office description.',

        'image.required' => 'Please upload an office image.',
        'image.image'    => 'The file must be a valid image.',
        'image.mimes'    => 'The image must be a JPG, PNG, or WEBP file.',
        'image.max'      => 'The image must not exceed 10MB.',
    ]);

    try {
        $office->title = $request->title;
        $office->description = $request->description;

        if ($request->hasFile('image')) {
            if ($office->image) {
                Storage::disk('public')->delete($office->image);
            }
            $office->image = $this->processAndStoreImage($request->file('image'), 'about/regional-offices');
        }

        $office->save();

        gc_collect_cycles();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Regional Location updated successfully.',
            ]);
        }

        return redirect()
            ->route('admin.about.regional-footprint')
            ->with('success', 'Regional Location updated successfully.');

    } catch (\Throwable $e) {
        \Log::error('Regional office update() FAILED', [
            'message' => $e->getMessage(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
        ]);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Something went wrong.'], 500);
        }

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
    $videos = OperationVideo::orderBy('id')->get();

    return view('admin.about.operation', compact('videos'));
}

/**
 * Store a new operation video (main hero video or a carousel clip).
 */



public function storeOperationVideo(Request $request)
{
    $request->validate([
        'title'       => 'required|string|max:50',
        'description' => 'required|string|max:120',
        'thumbnail'   => 'required|image|mimes:jpg,jpeg,png,webp|max:10240',
        'video'       => [
            Rule::requiredIf(fn () => !$request->filled('vedio_link')),
            'nullable',
            'prohibits:vedio_link',
            'mimes:mp4,mov,webm',
            'max:20480',
        ],
        'vedio_link'  => [
            Rule::requiredIf(fn () => !$request->hasFile('video')),
            'nullable',
            'prohibits:video',
            'url',
            'regex:/^(https?:\/\/)?(www\.)?(youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)[a-zA-Z0-9_-]{11}(&.*)?$/',
        ],
    ], [
        'title.required'       => 'Please enter a title.',
        'title.max'            => 'Title must not exceed 50 characters.',

        'description.required' => 'Please enter a description.',
        'description.max'      => 'Description must not exceed 120 characters.',

        'thumbnail.required' => 'Please upload a thumbnail image.',
        'thumbnail.image'    => 'The thumbnail must be a valid image.',
        'thumbnail.mimes'    => 'The thumbnail must be a JPG, PNG, or WEBP file.',
        'thumbnail.max'      => 'The thumbnail must not exceed 10MB.',

        'video.required' => 'Please upload a video file or provide a YouTube link.',
        'video.prohibits' => 'Please provide either a video file OR a YouTube link, not both.',
        'video.mimes'    => 'The video must be an MP4, MOV, or WEBM file.',
        'video.max'      => 'The video must not exceed 20MB.',

        'vedio_link.required' => 'Please upload a video file or provide a YouTube link.',
        'vedio_link.prohibits' => 'Please provide either a video file OR a YouTube link, not both.',
        'vedio_link.url'      => 'Please enter a valid URL.',
        'vedio_link.regex'    => 'Please enter a valid YouTube video URL.',
    ]);

    try {
        $thumbnailPath = $this->processAndStoreImage($request->file('thumbnail'), 'operation/thumbnails');

        $videoPath = null;
        if ($request->hasFile('video')) {
            $videoPath = $request->file('video')->store('operation/videos', 'public');
        }

        OperationVideo::create([
            'title'       => $request->title,
            'description' => $request->description,
            'thumbnail'   => $thumbnailPath,
            'video'       => $videoPath,
            'vedio_link'  => $request->filled('vedio_link') ? $this->normalizeYoutubeUrl($request->vedio_link) : null,
        ]);

        gc_collect_cycles();

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'message' => 'Video added successfully.']);
        }

        return redirect()
            ->route('admin.about.operation')
            ->with('success', 'Video added successfully.');

    } catch (\Throwable $e) {
        \Log::error('Operation video store() FAILED', [
            'message' => $e->getMessage(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
        ]);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Something went wrong.'], 500);
        }

        return back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
    }
}

public function updateOperationVideo(Request $request, OperationVideo $video)
{
    $request->validate([
        'title'       => 'required|string|max:50',
        'description' => 'required|string|max:120',
        'thumbnail'   => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
        'video'       => [
            'nullable',
            'prohibits:vedio_link',
            'mimes:mp4,mov,webm',
            'max:20480',
        ],
        'vedio_link'  => [
            'nullable',
            'prohibits:video',
            'url',
            'regex:/^(https?:\/\/)?(www\.)?(youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)[a-zA-Z0-9_-]{11}(&.*)?$/',
        ],
    ], [
        'title.required'       => 'Please enter a title.',
        'title.max'            => 'Title must not exceed 50 characters.',

        'description.required' => 'Please enter a description.',
        'description.max'      => 'Description must not exceed 120 characters.',

        'thumbnail.image' => 'The thumbnail must be a valid image.',
        'thumbnail.mimes' => 'The thumbnail must be a JPG, PNG, or WEBP file.',
        'thumbnail.max'   => 'The thumbnail must not exceed 10MB.',

        'video.prohibits' => 'Please provide either a video file OR a YouTube link, not both.',
        'video.mimes' => 'The video must be an MP4, MOV, or WEBM file.',
        'video.max'   => 'The video must not exceed 20MB.',

        'vedio_link.prohibits' => 'Please provide either a video file OR a YouTube link, not both.',
        'vedio_link.url'   => 'Please enter a valid URL.',
        'vedio_link.regex' => 'Please enter a valid YouTube video URL.',
    ]);

    try {
        $video->title = $request->title;
        $video->description = $request->description;

        if ($request->hasFile('thumbnail')) {
            if ($video->thumbnail) {
                Storage::disk('public')->delete($video->thumbnail);
            }
            $video->thumbnail = $this->processAndStoreImage($request->file('thumbnail'), 'operation/thumbnails');
        }

        // If a new video file is uploaded, it takes over from any existing YouTube link
        if ($request->hasFile('video')) {
            if ($video->video) {
                Storage::disk('public')->delete($video->video);
            }
            $video->video = $request->file('video')->store('operation/videos', 'public');
            $video->vedio_link = null;
        }

        // If a YouTube link is given/changed, it takes over from any existing uploaded file
        if ($request->filled('vedio_link')) {
            if ($video->video) {
                Storage::disk('public')->delete($video->video);
                $video->video = null;
            }
            $video->vedio_link = $this->normalizeYoutubeUrl($request->vedio_link);
        }

        $video->save();

        gc_collect_cycles();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Video updated successfully.',
            ]);
        }

        return redirect()
            ->route('admin.about.operation')
            ->with('success', 'Video updated successfully.');

    } catch (\Throwable $e) {
        \Log::error('Operation video update() FAILED', [
            'message' => $e->getMessage(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
        ]);

        if ($request->wantsJson()) {
            return response()->json(['message' => 'Something went wrong.'], 500);
        }

        return back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
    }
}

private function normalizeYoutubeUrl(string $url): string
{
    preg_match(
        '/(?:youtube\.com\/watch\?v=|youtu\.be\/|youtube\.com\/embed\/)([a-zA-Z0-9_-]{11})/',
        $url,
        $matches
    );

    $videoId = $matches[1] ?? null;

    return $videoId ? "https://www.youtube.com/watch?v={$videoId}" : $url;
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