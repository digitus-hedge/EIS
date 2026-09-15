<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServicePage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ServicePageController extends Controller
{
    protected int $imageWidth = 1200;
    protected int $imageHeight = 600;
    protected int $compressQuality = 70;

    /**
     * Show the Service page Banner form.
     * Route: GET /admin/service/banner -> admin.service.banner
     */
    public function banner()
    {
        $servicePage = ServicePage::first() ?? new ServicePage();

        return view('admin.servicePage.banner', compact('servicePage'));
    }

    /**
     * Store/update the banner title + image.
     * Route: POST /admin/service/banner -> admin.service.banner.store
     */
 public function storeBanner(Request $request)
{
    $servicePage = ServicePage::first() ?? new ServicePage();

    $request->validate([
        'banner_title' => 'required|string|max:100',
        'banner'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
        'banner_video' => 'nullable|mimes:mp4,mov,webm|max:20480',
        'remove_banner' => 'nullable|boolean',
        'remove_banner_video' => 'nullable|boolean',
    ], [
        'banner_title.required' => 'Please enter a banner title.',
        'banner_title.max'      => 'Title must not exceed 100 characters.',

        'banner.image'    => 'The file must be a valid image.',
        'banner.mimes'    => 'The banner image must be a JPG, PNG, or WEBP file.',
        'banner.max'      => 'The banner image must not exceed 10MB.',

        'banner_video.mimes' => 'The banner video must be an MP4, MOV, or WEBM file.',
        'banner_video.max'   => 'The banner video must not exceed 20MB.',
    ]);

    // ----- Either image or video required -----
    $hasNewImage = $request->hasFile('banner');
    $hasNewVideo = $request->hasFile('banner_video');
    $hasExistingImage = $servicePage->banner && !$request->boolean('remove_banner');
    $hasExistingVideo = $servicePage->banner_video && !$request->boolean('remove_banner_video');

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
        $servicePage->banner_title = $request->banner_title;

        // ----- Banner Image -----
        if ($request->hasFile('banner')) {
            if ($servicePage->banner) {
                Storage::disk('public')->delete($servicePage->banner);
            }
            $servicePage->banner = $this->processAndStoreImage($request->file('banner'));

            if ($servicePage->banner_video) {
                Storage::disk('public')->delete($servicePage->banner_video);
                $servicePage->banner_video = null;
            }

            gc_collect_cycles();
        } elseif ($request->boolean('remove_banner')) {
            if ($servicePage->banner) {
                Storage::disk('public')->delete($servicePage->banner);
            }
            $servicePage->banner = null;
        }

        // ----- Banner Video -----
        if ($request->hasFile('banner_video')) {
            if ($servicePage->banner_video) {
                Storage::disk('public')->delete($servicePage->banner_video);
            }
            $servicePage->banner_video = $request->file('banner_video')->store('service-page/banner-videos', 'public');

            if ($servicePage->banner) {
                Storage::disk('public')->delete($servicePage->banner);
                $servicePage->banner = null;
            }
        } elseif ($request->boolean('remove_banner_video')) {
            if ($servicePage->banner_video) {
                Storage::disk('public')->delete($servicePage->banner_video);
            }
            $servicePage->banner_video = null;
        }

        $servicePage->save();

        return redirect()
            ->route('admin.service.banner')
            ->with('success', 'Service banner saved successfully.');

    } catch (\Throwable $e) {
        \Log::error('Service banner store() FAILED', [
            'message' => $e->getMessage(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
        ]);

        return back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
    }
}

    /**
     * Show the "Our Services" text section form.
     * Route: GET /admin/service/our-service -> admin.service.our-service
     */
    public function ourService()
    {
        $servicePage = ServicePage::first() ?? new ServicePage();

        return view('admin.servicePage.our-service', compact('servicePage'));
    }

    /**
     * Store/update the "Our Services" title + description.
     * Route: POST /admin/service/our-service -> admin.service.our-service.store
     */
   public function storeOurService(Request $request)
{
    $request->validate([
        'our_service_title'       => 'required|string|max:70',
        'our_service_description' => 'required|string|max:1000',
    ], [
        'our_service_title.required'       => 'Please enter a title.',
        'our_service_title.max'            => 'Title must not exceed 255 characters.',

        'our_service_description.required' => 'Please enter a description.',
        'our_service_description.max'      => 'Description must not exceed 1000 characters.',
    ]);

    try {
        $servicePage = ServicePage::first() ?? new ServicePage();
        $servicePage->our_service_title = $request->our_service_title;
        $servicePage->our_service_description = $request->our_service_description;
        $servicePage->save();

        return redirect()
            ->route('admin.service.our-service')
            ->with('success', 'Our Services section saved successfully.');

    } catch (\Throwable $e) {
        \Log::error('Our Service section store() FAILED', [
            'message' => $e->getMessage(),
            'file'    => $e->getFile(),
            'line'    => $e->getLine(),
        ]);

        return back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
    }
}
    private function processAndStoreImage($file): string
    {
        $filename = 'service/banners/' . Str::random(20) . '.webp';

        $manager = new ImageManager(new Driver());
        $image = $manager->read($file->getPathname());
        $image->cover($this->imageWidth, $this->imageHeight);
        $encoded = $image->toWebp(quality: $this->compressQuality);

        Storage::disk('public')->put($filename, (string) $encoded);

        return $filename;
    }
}