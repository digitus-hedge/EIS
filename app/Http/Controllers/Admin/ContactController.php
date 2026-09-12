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
    $contact = Contact::first() ?? new Contact();

    $data = $request->validate([
        'banner_title'  => 'required|string|max:255',
        'banner_image'  => $contact->banner_image
                                ? 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240'
                                : 'required|image|mimes:jpg,jpeg,png,webp|max:10240',
        'phone'         => 'required|string|max:50',
        'email'         => 'required|email|max:255',
        'address'       => 'required|string|max:1000',
        'contact_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
    ], [
        'banner_title.required' => 'Please enter a banner title.',
        'banner_title.max'      => 'Banner title must not exceed 255 characters.',

        'banner_image.required' => 'Please upload a banner image.',
        'banner_image.image'    => 'The banner image must be a valid image.',
        'banner_image.mimes'    => 'The banner image must be a JPG, PNG, or WEBP file.',
        'banner_image.max'      => 'The banner image must not exceed 10MB.',

        'phone.required' => 'Please enter a phone number.',
        'phone.max'       => 'Phone number must not exceed 50 characters.',

        'email.required' => 'Please enter an email address.',
        'email.email'     => 'Please enter a valid email address.',
        'email.max'       => 'Email must not exceed 255 characters.',

        'address.required' => 'Please enter an address.',
        'address.max'       => 'Address must not exceed 1000 characters.',

        'contact_image.image' => 'The photo must be a valid image.',
        'contact_image.mimes' => 'The photo must be a JPG, PNG, or WEBP file.',
        'contact_image.max'   => 'The photo must not exceed 10MB.',
    ]);

    $contact->banner_title = $data['banner_title'];
    $contact->phone = $data['phone'];
    $contact->email = $data['email'];
    $contact->address = $data['address'];

    if ($request->hasFile('banner_image')) {
        if ($contact->banner_image) {
            Storage::disk('public')->delete($contact->banner_image);
        }
        $contact->banner_image = $this->processAndStoreImage($request->file('banner_image'));
    }

    if ($request->hasFile('contact_image')) {
        if ($contact->contact_image) {
            Storage::disk('public')->delete($contact->contact_image);
        }
        $contact->contact_image = $this->processAndStoreImage($request->file('contact_image'));
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