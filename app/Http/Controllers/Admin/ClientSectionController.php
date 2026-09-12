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

    // Get current array of paths from DB: ["clients/MbEDJh7Ywkbu7bEpAYK5.webp", "clients/jQ64qwlfS6H7sxvw5UA7.webp"]
    $existingImages = $client->images ?? [];

    // 1. Get array of images selected for deletion
    $removeImages = $request->input('remove_images', []);

    if (!empty($removeImages)) {
        // Delete each physical file from storage
        foreach ($removeImages as $path) {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        // Subtract removed paths from the existing array
        $existingImages = array_diff($existingImages, $removeImages);
    }

    // 2. Append newly uploaded images
    if ($request->hasFile('images')) {
        foreach ($request->file('images') as $file) {
            if ($file && $file->isValid()) {
                $existingImages[] = $this->processAndStoreImage($file);
            }
        }
    }

    // 3. Re-index array keys to avoid JSON object conversion in DB
    $finalImages = array_values($existingImages);

    // Save as array if items exist, otherwise set explicitly to null
    $client->images = !empty($finalImages) ? $finalImages : null;

    $client->save();

    return redirect()
        ->route('admin.home.clients')
        ->with('success', 'Client section updated successfully.');
}



  private function processAndStoreImage($file): string
    {
        $filename = 'clients/' . Str::random(20) . '.webp';
 
        $manager = new ImageManager(new Driver());
 
        $image = $manager->read($file->getPathname());
 
        // No cropping  keeps the image's original aspect ratio and dimensions.
        $encoded = $image->toWebp(quality: $this->compressQuality);
 
        Storage::disk('public')->put($filename, (string) $encoded);
 
        return $filename;
    }
  
}
