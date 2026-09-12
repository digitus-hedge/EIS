<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ContactController extends Controller
{
    protected int $imageWidth = 1200;
    protected int $imageHeight = 500;
    protected int $compressQuality = 70;

    public function edit()
    {
        $contact = Contact::first() ?? new Contact();

        return view('admin.contact.form', compact('contact'));
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'banner_title'   => 'nullable|string|max:255',
            'banner_image'   => 'nullable|image|max:10240',
            'phone'          => 'nullable|string|max:50',
            'email'          => 'nullable|email|max:255',
            'address'        => 'nullable|string|max:1000',
            'contact_image'  => 'nullable|image|max:10240',
        ]);

        $contact = Contact::first() ?? new Contact();

        $contact->banner_title = $data['banner_title'] ?? null;
        $contact->phone = $data['phone'] ?? null;
        $contact->email = $data['email'] ?? null;
        $contact->address = $data['address'] ?? null;

        if ($request->hasFile('banner_image')) {
            if ($contact->banner_image) {
                Storage::disk('public')->delete($contact->banner_image);
            }
            $contact->banner_image = $this->processAndStoreImage($request->file('banner_image'));
        }

        $contact->save();

        return redirect()
            ->route('admin.contact.edit')
            ->with('success', 'Contact section updated successfully.');
    }

    private function processAndStoreImage($file, ?int $width = null, ?int $height = null): string
    {
        $width = $width ?? $this->imageWidth;
        $height = $height ?? $this->imageHeight;

        $filename = 'contact/' . \Illuminate\Support\Str::random(20) . '.webp';

        $manager = new ImageManager(new Driver());
        $image = $manager->read($file);
        $image->cover($width, $height);
        $encoded = $image->toWebp(quality: $this->compressQuality);

        Storage::disk('public')->put($filename, (string) $encoded);

        return $filename;
    }
}