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

    $duplicateWarning = $this->sortOrderDuplicateWarning($service);

    if ($request->wantsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Service created successfully.',
            'warning' => $duplicateWarning,
            'redirect' => route('admin.home.services'),
        ]);
    }

    return redirect()
        ->route('admin.home.services')
        ->with('success', 'Service created successfully.')
        ->with('warning', $duplicateWarning);
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

    $duplicateWarning = $this->sortOrderDuplicateWarning($service);

    if ($request->wantsJson()) {
        return response()->json([
            'success' => true,
            'message' => 'Service updated successfully.',
            'warning' => $duplicateWarning,
            'redirect' => route('admin.home.services'),
        ]);
    }

    return redirect()
        ->route('admin.home.services')
        ->with('success', 'Service updated successfully.')
        ->with('warning', $duplicateWarning);
}

    public function destroy(Service $service)
    {
        if ($service->banner_image) {
            Storage::disk('public')->delete($service->banner_image);
        }

        if ($service->overview_image) {
            Storage::disk('public')->delete($service->overview_image);
        }

        if ($service->process_image) {
            Storage::disk('public')->delete($service->process_image);
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


    private function fillService(Service $service, array $data, Request $request): void
{
    // ===== Banner =====
    $service->banner_title = $data['banner_title'];
    $service->slug = Str::slug($data['banner_title']);
    $service->banner_description = $data['banner_description'] ?? null;
    $service->show_on_home = $request->boolean('show_on_home');
    $service->home_sort_order = isset($data['home_sort_order']) && $data['home_sort_order'] !== ''
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

    // ===== Process =====
    $service->process_title = $data['process_title'] ?? null;
    // CKEditor HTML: keep formatting tags only, strip scripts and anything else
    $service->process_description = isset($data['process_description'])
    ? strip_tags($data['process_description'], '<p><br><strong><b><em><i><u><ul><ol><li><a><h2><h3><h4><blockquote>')
    : null;

    if ($request->hasFile('process_image')) {
        if ($service->process_image) {
            Storage::disk('public')->delete($service->process_image);
        }
        $service->process_image = $this->processAndStoreImage(
            $request->file('process_image'),
            $this->imageWidth,
            $this->imageHeight
        );
    }

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

    private function sortOrderDuplicateWarning(Service $service): ?string
{
    if (is_null($service->home_sort_order)) {
        return null;
    }

    $isDuplicate = Service::where('home_sort_order', $service->home_sort_order)
        ->where('id', '!=', $service->id)
        ->exists();

    return $isDuplicate
        ? "Sort order {$service->home_sort_order} is already used by another service."
        : null;
}
}