<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceRequest;
use App\Models\Service;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ServiceController extends Controller
{
    // Hero/overview/banner image dimensions
    protected int $imageWidth = 760;
    protected int $imageHeight = 500;

    // Feature icon dimensions (small square icons)
    protected int $iconWidth = 120;
    protected int $iconHeight = 120;

    protected int $compressQuality = 100;

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');

        $services = Service::query()
            ->when($search, function ($query, $search) {
                $query->where('banner_title', 'like', "%{$search}%")
                    ->orWhere('banner_description', 'like', "%{$search}%");
            })
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        return view('admin.service.list', compact('services', 'search', 'perPage'));
    }

    public function create()
    {
        $service = new Service();
        return view('admin.service.form', compact('service'));
    }

    // public function store(ServiceRequest $request)
    // {
    //     $data = $request->validated();

    //     $service = new Service();
    //     $this->fillService($service, $data, $request);
    //     $service->save();

    //     return redirect()
    //         ->route('admin.home.services')
    //         ->with('success', 'Service created successfully.');
    // }


    public function store(ServiceRequest $request)
{
    $data = $request->validated();

    $service = new Service();
    $this->fillService($service, $data, $request);
    $service->save();

    if ($request->wantsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Service created successfully.',
            'redirect' => route('admin.home.services'),
        ]);
    }

    return redirect()
        ->route('admin.home.services')
        ->with('success', 'Service created successfully.');
}

    public function edit(Service $service)
    {
        return view('admin.service.form', compact('service'));
    }

    // public function update(ServiceRequest $request, Service $service)
    // {
    //     $data = $request->validated();
    //     $this->fillService($service, $data, $request);
    //     $service->save();

    //     return redirect()
    //         ->route('admin.home.services')
    //         ->with('success', 'Service updated successfully.');
    // }


    public function update(ServiceRequest $request, Service $service)
{
    $data = $request->validated();
    $this->fillService($service, $data, $request);
    $service->save();

    if ($request->wantsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Service updated successfully.',
            'redirect' => route('admin.home.services'),
        ]);
    }

    return redirect()
        ->route('admin.home.services')
        ->with('success', 'Service updated successfully.');
}

    public function destroy(Service $service)
    {
        if ($service->banner_image) {
            Storage::disk('public')->delete($service->banner_image);
        }

        if ($service->overview_image) {
            Storage::disk('public')->delete($service->overview_image);
        }

        foreach ($service->process ?? [] as $row) {
            if (!empty($row['video'])) {
                Storage::disk('public')->delete($row['video']);
            }
            if (!empty($row['thumbnail'])) {
                Storage::disk('public')->delete($row['thumbnail']);
            }
        }

        foreach ($service->features ?? [] as $row) {
            if (!empty($row['icon'])) {
                Storage::disk('public')->delete($row['icon']);
            }
        }
        foreach ($service->gallery ?? [] as $row) {
            if (!empty($row['image'])) {
                Storage::disk('public')->delete($row['image']);
            }
        }
        $service->delete();

        return redirect()
            ->route('admin.home.services')
            ->with('success', 'Service deleted successfully.');
    }

    // private function fillService(Service $service, array $data, Request $request): void
    // {
    //     // ===== Banner =====
    //     $service->banner_title = $data['banner_title'];
    //     $service->slug = Str::slug($data['banner_title']);
    //     $service->banner_description = $data['banner_description'] ?? null;
    //     $service->show_on_home = $request->boolean('show_on_home');

    //     $service->meta_title = $data['meta_title'];
    //     $service->meta_description = $data['meta_description'];

    //     if ($request->hasFile('banner_image')) {
    //         if ($service->banner_image) {
    //             Storage::disk('public')->delete($service->banner_image);
    //         }
    //         $service->banner_image = $this->processAndStoreImage(
    //             $request->file('banner_image'),
    //             $this->imageWidth,
    //             $this->imageHeight
    //         );
    //     }

    //     // ===== Overview =====
    //     $service->overview_title = $data['overview_title'] ?? null;
    //     $service->overview_description = $data['overview_description'] ?? null;

    //     if ($request->hasFile('overview_image')) {
    //         if ($service->overview_image) {
    //             Storage::disk('public')->delete($service->overview_image);
    //         }
    //         $service->overview_image = $this->processAndStoreImage(
    //             $request->file('overview_image'),
    //             $this->imageWidth,
    //             $this->imageHeight
    //         );
    //     }

    //     // ===== Process: repeatable description + video rows (unlimited) =====
    //     $processInput = $data['process'] ?? [];
    //     $oldProcess = $service->process ?? [];
    //     $processedRows = [];

    //    foreach ($processInput as $index => $row) {
    //         $description = trim($row['description'] ?? '');
    //         $hasNewVideo = $request->hasFile("process.$index.video");
    //         $hasNewThumbnail = $request->hasFile("process.$index.thumbnail");

    //         if ($description === '' && !$hasNewVideo && empty($row['existing_video']) && !$hasNewThumbnail && empty($row['existing_thumbnail'])) {
    //             continue;
    //         }

    //         $videoPath = $row['existing_video'] ?? null;
    //         $thumbnailPath = $row['existing_thumbnail'] ?? null;

    //         if ($hasNewVideo) {
    //             if (!empty($oldProcess[$index]['video'])) {
    //                 Storage::disk('public')->delete($oldProcess[$index]['video']);
    //             }
    //             $videoPath = $this->storeVideo($request->file("process.$index.video"));
    //         }

    //         if ($hasNewThumbnail) {
    //             if (!empty($oldProcess[$index]['thumbnail'])) {
    //                 Storage::disk('public')->delete($oldProcess[$index]['thumbnail']);
    //             }
    //             $thumbnailPath = $this->processAndStoreImage(
    //                 $request->file("process.$index.thumbnail"),
    //                 400,
    //                 300,
    //                 'services/thumbnails'
    //             );
    //         }

    //         $processedRows[] = [
    //             'description' => $description,
    //             'video'       => $videoPath,
    //             'thumbnail'   => $thumbnailPath,
    //         ];
    //     }

    //     $service->process = count($processedRows) > 0 ? array_values($processedRows) : null;

    //     // ===== Features: heading + up to 4 rows (icon, title, description) =====
    //     $service->features_heading = $data['features_heading'] ?? null;

    //     // Enforce the 4-row cap server-side regardless of what the client sent
    //     $featuresInput = array_slice($data['features'] ?? [], 0, 4);
    //     $oldFeatures = $service->features ?? [];
    //     $processedFeatures = [];

    //     foreach ($featuresInput as $index => $row) {
    //         $title = trim($row['title'] ?? '');
    //         $description = trim($row['description'] ?? '');
    //         $hasNewIcon = $request->hasFile("features.$index.icon");

    //         if ($title === '' && $description === '' && !$hasNewIcon && empty($row['existing_icon'])) {
    //             continue;
    //         }

    //         $iconPath = $row['existing_icon'] ?? null;

    //         if ($hasNewIcon) {
    //             if (!empty($oldFeatures[$index]['icon'])) {
    //                 Storage::disk('public')->delete($oldFeatures[$index]['icon']);
    //             }
    //             $iconPath = $this->processAndStoreImage(
    //                 $request->file("features.$index.icon"),
    //                 $this->iconWidth,
    //                 $this->iconHeight,
    //                 'services/icons'
    //             );
    //         }

    //         $processedFeatures[] = [
    //             'icon'        => $iconPath,
    //             'title'       => $title,
    //             'description' => $description,
    //         ];
    //     }

    //     $service->features = count($processedFeatures) > 0 ? array_values($processedFeatures) : null;
    // }


    private function fillService(Service $service, array $data, Request $request): void
{
    // ===== Banner =====
    $service->banner_title = $data['banner_title'];
    $service->slug = Str::slug($data['banner_title']);
    $service->banner_description = $data['banner_description'] ?? null;
    $service->show_on_home = $request->boolean('show_on_home');
    $service->home_sort_order = $service->show_on_home
    ? (int) $data['home_sort_order']
    : null;
    $service->meta_title = $data['meta_title'];
    $service->meta_description = $data['meta_description'];

    // Banner Image
    if ($request->boolean('remove_banner_image') && !$request->hasFile('banner_image')) {
        if ($service->banner_image) {
            Storage::disk('public')->delete($service->banner_image);
        }
        $service->banner_image = null;
    }

    if ($request->hasFile('banner_image')) {
        if ($service->banner_image) {
            Storage::disk('public')->delete($service->banner_image);
        }
        $service->banner_image = $this->processAndStoreImage(
            $request->file('banner_image'),
            $this->imageWidth,
            $this->imageHeight
        );
    }

    // Banner Video
    if ($request->boolean('remove_banner_video') && !$request->hasFile('banner_video')) {
        if ($service->banner_video) {
            Storage::disk('public')->delete($service->banner_video);
        }
        $service->banner_video = null;
    }

    if ($request->hasFile('banner_video')) {
        if ($service->banner_video) {
            Storage::disk('public')->delete($service->banner_video);
        }
        $service->banner_video = $this->storeVideo($request->file('banner_video'));
    }

    // Since Banner Image and Banner Video are mutually exclusive (per ServiceRequest validation),
    // uploading one clears the other automatically.
    if ($request->hasFile('banner_image') && $service->banner_video) {
        Storage::disk('public')->delete($service->banner_video);
        $service->banner_video = null;
    }
    if ($request->hasFile('banner_video') && $service->banner_image) {
        Storage::disk('public')->delete($service->banner_image);
        $service->banner_image = null;
    }

    // ===== Overview =====
    $service->overview_title = $data['overview_title'] ?? null;
    $service->overview_description = $data['overview_description'] ?? null;

    if ($request->hasFile('overview_image')) {
        if ($service->overview_image) {
            Storage::disk('public')->delete($service->overview_image);
        }
        $service->overview_image = $this->processAndStoreImage(
            $request->file('overview_image'),
            $this->imageWidth,
            $this->imageHeight
        );
    }

    // ===== Process: repeatable description + video rows (unlimited) =====
  // ===== Process: repeatable description + (video OR YouTube link) rows (unlimited) =====
    $processInput = $data['process'] ?? [];
    $oldProcess = $service->process ?? [];
    $processedRows = [];

    foreach ($processInput as $index => $row) {
        $description = trim($row['description'] ?? '');
        $hasNewVideo = $request->hasFile("process.$index.video");
        $hasNewThumbnail = $request->hasFile("process.$index.thumbnail");
        $vedioLink = trim($row['vedio_link'] ?? '');

        if ($description === '' && !$hasNewVideo && empty($row['existing_video']) && $vedioLink === ''
            && !$hasNewThumbnail && empty($row['existing_thumbnail'])) {
            continue;
        }

        $videoPath = $row['existing_video'] ?? null;
        $thumbnailPath = $row['existing_thumbnail'] ?? null;

        if ($hasNewThumbnail) {
            if (!empty($oldProcess[$index]['thumbnail'])) {
                Storage::disk('public')->delete($oldProcess[$index]['thumbnail']);
            }
            $thumbnailPath = $this->processAndStoreImage(
                $request->file("process.$index.thumbnail"),
                400,
                300,
                'services/thumbnails'
            );
        }

        if ($hasNewVideo) {
            // A newly uploaded file always takes precedence over a link
            if (!empty($oldProcess[$index]['video'])) {
                Storage::disk('public')->delete($oldProcess[$index]['video']);
            }
            $videoPath = $this->storeVideo($request->file("process.$index.video"));
            $vedioLink = ''; // uploaded file wins; drop any link
        } elseif ($vedioLink !== '') {
            // A YouTube link was provided/kept: it replaces any stored video file
            if (!empty($oldProcess[$index]['video'])) {
                Storage::disk('public')->delete($oldProcess[$index]['video']);
            }
            $videoPath = null;
            $vedioLink = $this->normalizeYoutubeUrl($vedioLink);
        }

        $processedRows[] = [
            'description' => $description,
            'video'       => $videoPath,
            'vedio_link'  => $vedioLink !== '' ? $vedioLink : null,
            'thumbnail'   => $thumbnailPath,
        ];
    }

    $service->process = count($processedRows) > 0 ? array_values($processedRows) : null;

    // ===== Features: heading + up to 4 rows (icon, title, description) =====
    $service->features_heading = $data['features_heading'] ?? null;

    $featuresInput = array_slice($data['features'] ?? [], 0, 4);
    $oldFeatures = $service->features ?? [];
    $processedFeatures = [];

    foreach ($featuresInput as $index => $row) {
        $title = trim($row['title'] ?? '');
        $description = trim($row['description'] ?? '');
        $hasNewIcon = $request->hasFile("features.$index.icon");

        if ($title === '' && $description === '' && !$hasNewIcon && empty($row['existing_icon'])) {
            continue;
        }

        $iconPath = $row['existing_icon'] ?? null;

        if ($hasNewIcon) {
            if (!empty($oldFeatures[$index]['icon'])) {
                Storage::disk('public')->delete($oldFeatures[$index]['icon']);
            }
            $iconPath = $this->processAndStoreImage(
                $request->file("features.$index.icon"),
                $this->iconWidth,
                $this->iconHeight,
                'services/icons'
            );
        }

        $processedFeatures[] = [
            'icon'        => $iconPath,
            'title'       => $title,
            'description' => $description,
        ];
    }

    $service->features = count($processedFeatures) > 0 ? array_values($processedFeatures) : null;

    // ===== Gallery: unlimited repeatable images =====
$galleryInput = $data['gallery'] ?? [];
$oldGallery = $service->gallery ?? [];
$processedGallery = [];

foreach ($galleryInput as $index => $row) {
    $hasNewImage = $request->hasFile("gallery.$index.image");

    if (!$hasNewImage && empty($row['existing_image'])) {
        continue;
    }

    $imagePath = $row['existing_image'] ?? null;

    if ($hasNewImage) {
        if (!empty($oldGallery[$index]['image'])) {
            Storage::disk('public')->delete($oldGallery[$index]['image']);
        }
        $imagePath = $this->processAndStoreImage(
            $request->file("gallery.$index.image"),
            $this->imageWidth,
            $this->imageHeight,
            'services/gallery'
        );
    }

    $processedGallery[] = [
        'image' => $imagePath,
    ];
}

// Clean up any old images whose rows were dropped entirely (removed in the UI)
$keptImages = collect($processedGallery)->pluck('image')->filter()->all();
foreach ($oldGallery as $old) {
    if (!empty($old['image']) && !in_array($old['image'], $keptImages, true)) {
        Storage::disk('public')->delete($old['image']);
    }
}

$service->gallery = count($processedGallery) > 0 ? array_values($processedGallery) : null;
}



    private function processAndStoreImage($file, int $width, int $height, string $folder = 'services'): string
    {
        $filename = $folder . '/' . Str::random(20) . '.webp';

        $manager = new ImageManager(new Driver());
        $image = $manager->read($file);
        $image->cover($width, $height);
        $encoded = $image->toWebp(quality: $this->compressQuality);

        Storage::disk('public')->put($filename, (string) $encoded);

        return $filename;
    }

    private function normalizeYoutubeUrl(string $url): string
{
    preg_match(
        '/(?:youtube\.com\/(?:watch\?v=|embed\/|shorts\/)|youtu\.be\/)([a-zA-Z0-9_-]{11})/',
        $url,
        $matches
    );

    $videoId = $matches[1] ?? null;

    return $videoId ? "https://www.youtube.com/watch?v={$videoId}" : $url;
}

    private function storeVideo($file): string
    {
        $name = Str::random(20) . '.' . $file->getClientOriginalExtension();
        return $file->storeAs('services/videos', $name, 'public');
    }
}