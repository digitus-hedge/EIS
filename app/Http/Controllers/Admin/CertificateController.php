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
    protected int $imageWidth = 700;
    protected int $imageHeight = 900;
    protected int $compressQuality = 75;

    public function index()
    {
        $certificates = Certificate::orderBy('id')->get();

        return view('admin.about.certificates', compact('certificates'));
    }

    public function store(Request $request)
{
    ini_set('memory_limit', '512M');

    $request->validate([
        'title' => 'required|string|max:255',
        'image' => 'required|image|mimes:jpg,jpeg,png,webp|max:5120',
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
        $image->cover($this->imageWidth, $this->imageHeight);
        $encoded = $image->toWebp(quality: $this->compressQuality);

        Storage::disk('public')->put($filename, (string) $encoded);

        return $filename;
    }
}