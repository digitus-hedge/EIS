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
    protected int $imageHeight = 600;
    protected int $compressQuality = 100;

    public function edit()
    {
        $contact = Contact::first() ?? new Contact();

        return view('admin.contact.form', compact('contact'));
    }

    public function update(Request $request)
    {
        $contact = Contact::first() ?? new Contact();

        $data = $request->validate([
            'banner_title'      => 'required|string|max:255',
            'banner_image'      => 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240',
            'banner_video'      => 'nullable|mimes:mp4,mov,webm|max:20480',
            'remove_banner_image'  => 'nullable|boolean',
            'remove_banner_video'  => 'nullable|boolean',
            'phone'             => 'required|string|max:50',
            'email'             => 'required|email|max:255',
            'address'           => 'required|string|max:1000',
            'contact_image'     => $contact->contact_image
                ? 'nullable|image|mimes:jpg,jpeg,png,webp|max:10240'
                : 'required|image|mimes:jpg,jpeg,png,webp|max:10240',
            'remove_contact_image' => 'nullable|boolean',
            'meta_title'        => 'nullable|string|max:80',
            'meta_description'  => 'nullable|string|max:200',
        ], [
            'banner_title.required' => 'Please enter a banner title.',
            'banner_title.max'      => 'Banner title must not exceed 255 characters.',

            'banner_image.image'    => 'The banner image must be a valid image.',
            'banner_image.mimes'    => 'The banner image must be a JPG, PNG, or WEBP file.',
            'banner_image.max'      => 'The banner image must not exceed 10MB.',

            'banner_video.mimes'    => 'The banner video must be an MP4, MOV, or WEBM file.',
            'banner_video.max'      => 'The banner video must not exceed 20MB.',

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

            'meta_title.max'       => 'Meta title must not exceed 80 characters.',
            'meta_description.max' => 'Meta description must not exceed 200 characters.',
        ]);

        // ----- Either banner_image or banner_video required -----
       // ----- Either banner_image or banner_video required -----
$hasNewBannerImage = $request->hasFile('banner_image');
$hasNewBannerVideo = $request->hasFile('banner_video');
$hasExistingBannerImage = $contact->banner_image && !$request->boolean('remove_banner_image');
$hasExistingBannerVideo = $contact->banner_video && !$request->boolean('remove_banner_video');

if (!($hasNewBannerImage || $hasExistingBannerImage) && !($hasNewBannerVideo || $hasExistingBannerVideo)) {
    $message = 'Please upload either a Banner Image or a Banner Video.';

    if ($request->expectsJson() || $request->ajax()) {
        return response()->json([
            'message' => $message,
            'errors' => [
                'banner_image' => [$message],
            ],
        ], 422);
    }

    return back()->withInput()->withErrors([
        'banner_image' => $message,
    ]);
}

        $contact->banner_title = $data['banner_title'];
        $contact->phone = $data['phone'];
        $contact->email = $data['email'];
        $contact->address = $data['address'];
        $contact->meta_title = $data['meta_title'] ?? null;
        $contact->meta_description = $data['meta_description'] ?? null;

        // ----- Banner image -----
        if ($request->hasFile('banner_image')) {
            if ($contact->banner_image) {
                Storage::disk('public')->delete($contact->banner_image);
            }
            $contact->banner_image = $this->processAndStoreImage($request->file('banner_image'));

            // uploading a new image clears any existing video (mutually exclusive)
            if ($contact->banner_video) {
                Storage::disk('public')->delete($contact->banner_video);
                $contact->banner_video = null;
            }
        } elseif ($request->boolean('remove_banner_image')) {
            if ($contact->banner_image) {
                Storage::disk('public')->delete($contact->banner_image);
            }
            $contact->banner_image = null;
        }

        // ----- Banner video -----
        if ($request->hasFile('banner_video')) {
            if ($contact->banner_video) {
                Storage::disk('public')->delete($contact->banner_video);
            }
            $contact->banner_video = $request->file('banner_video')->store('contact/banner-videos', 'public');

            // uploading a new video clears any existing image (mutually exclusive)
            if ($contact->banner_image) {
                Storage::disk('public')->delete($contact->banner_image);
                $contact->banner_image = null;
            }
        } elseif ($request->boolean('remove_banner_video')) {
            if ($contact->banner_video) {
                Storage::disk('public')->delete($contact->banner_video);
            }
            $contact->banner_video = null;
        }

        // ----- Contact image (unchanged) -----
        if ($request->boolean('remove_contact_image') && !$request->hasFile('contact_image')) {
            if ($contact->contact_image) {
                Storage::disk('public')->delete($contact->contact_image);
            }
            $contact->contact_image = null;
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
