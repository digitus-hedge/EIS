<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ClientSectionRequest;
use App\Models\ClientSection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\Format;

class ClientSectionController extends Controller
{
    protected int $imageWidth = 300;
    protected int $imageHeight = 200;
    protected int $compressQuality = 70;

    /**
     * SHOW FORM — always the single Client section (or empty model if none exists yet)
     */
    public function index()
    {
        $client = ClientSection::first() ?? new ClientSection();
        return view('admin.client.form', compact('client'));
    }

    /**
     * STORE — creates the Client section if none exists, otherwise updates the existing one
     */
    public function store(ClientSectionRequest $request)
    {
        $data = $request->validated();

        $client = ClientSection::first() ?? new ClientSection();
        $client->title = $data['title'];
        $client->description = $data['description'] ?? null;

        $existingImages = $client->images ?? [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                if ($file) {
                    $existingImages[] = $this->processAndStoreImage($file);
                }
            }
        }

        // Remove images marked for deletion
        if ($request->filled('remove_images')) {
            foreach ($request->input('remove_images') as $path) {
                Storage::disk('public')->delete($path);
            }
            $existingImages = array_values(array_diff($existingImages, $request->input('remove_images')));
        }

        $client->images = array_values($existingImages);
        $client->save();

        return redirect()
            ->route('admin.home.clients')
            ->with('success', 'Client section saved successfully.');
    }

 

      private function processAndStoreImage($file): string
{
    $filename = 'clients/' . Str::random(20) . '.webp';

    $manager = new ImageManager(new Driver());

    // Fixed: read() instead of decodePath()
    $image = $manager->read($file->getPathname());

    $image->cover($this->imageWidth, $this->imageHeight);

    // Fixed: toWebp() instead of encodeUsingFormat(Format::WEBP, ...)
    $encoded = $image->toWebp(quality: $this->compressQuality);

    Storage::disk('public')->put($filename, (string) $encoded);

    return $filename;
}
}
