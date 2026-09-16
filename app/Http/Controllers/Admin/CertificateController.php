<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Certificate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class CertificateController extends Controller
{
    // protected int $imageWidth = 700;
    // protected int $imageHeight = 900;
    protected int $compressQuality = 100;

    public function index()
    {
        $certificates = Certificate::orderBy('id')->get();

        return view('admin.about.certificates', compact('certificates'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:60',
            'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:10240',
        ], [
            'title.required' => 'Please enter a certificate title.',
            'title.max'      => 'Title must not exceed 60 characters.',

            'image.required' => 'Please upload a certificate image.',
            'image.image'    => 'The file must be a valid image.',
            'image.mimes'    => 'The image must be a JPG, PNG, or WEBP file.',
            'image.max'      => 'The image must not exceed 10MB.',
        ]);

        try {
            $imagePath = $this->processAndStoreImage($request->file('image'));

            Certificate::create([
                'title' => $request->title,
                'image' => $imagePath,
            ]);

            gc_collect_cycles();

            return redirect()
                ->route('admin.about.certificates')
                ->with('success', 'Certificate added successfully.');
        } catch (\Throwable $e) {
            \Log::error('Certificate store() FAILED', [
                'message' => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ]);

            return back()->withInput()->with('error', 'Something went wrong: ' . $e->getMessage());
        }
    }


    public function update(Request $request, Certificate $certificate)
{
    $request->validate([
        'title' => 'required|string|max:60',
        'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
    ], [
        'title.required' => 'Please enter a certificate title.',
        'title.max'      => 'Title must not exceed 60 characters.',

        'image.image' => 'The file must be a valid image.',
        'image.mimes' => 'The image must be a JPG, PNG, or WEBP file.',
        'image.max'   => 'The image must not exceed 10MB.',
    ]);

    try {
        $certificate->title = $request->title;

        if ($request->hasFile('image')) {
            if ($certificate->image) {
                Storage::disk('public')->delete($certificate->image);
            }
            $certificate->image = $this->processAndStoreImage($request->file('image'));
        }

        $certificate->save();

        gc_collect_cycles();

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Certificate updated successfully.',
            ]);
        }

        return redirect()
            ->route('admin.about.certificates')
            ->with('success', 'Certificate updated successfully.');
    } catch (\Throwable $e) {
        \Log::error('Certificate update() FAILED', [
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

    public function destroy(Certificate $certificate)
    {
        $certificate->delete(); // image deleted via model event

        return back()->with('success', 'Certificate removed.');
    }

     private function processAndStoreImage($file): string
    {
        $filename = 'about/certificates/' . Str::random(20) . '.webp';
 
        $manager = new ImageManager(new Driver());
 
        $image = $manager->read($file->getPathname());
 
        // No cropping  keeps the image's original aspect ratio and dimensions.
        $encoded = $image->toWebp(quality: $this->compressQuality);
 
        Storage::disk('public')->put($filename, (string) $encoded);
 
        return $filename;
    }

}
