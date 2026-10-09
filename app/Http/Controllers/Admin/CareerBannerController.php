<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CareerBannerRequest;
use App\Models\CareerPage;
use Illuminate\Support\Facades\Storage;

/** Career > Banner: one form, one saved record (no list, no delete). */
class CareerBannerController extends Controller
{
    public function edit()
    {
        $careerPage = CareerPage::first() ?? new CareerPage();

        return view('admin.career.banner', compact('careerPage'));
    }

    public function store(CareerBannerRequest $request)
    {
        $careerPage = CareerPage::first() ?? new CareerPage();

        $careerPage->banner_title       = $request->input('banner_title');
        $careerPage->banner_description = $request->input('banner_description') ?: null;
        $careerPage->career_title       = $request->input('career_title');
        $careerPage->career_description = $request->input('career_description');
        $careerPage->meta_title         = $request->input('meta_title') ?: null;
        $careerPage->meta_description   = $request->input('meta_description') ?: null;

        // image and video work the same way: a new file replaces the old one, the x button removes it
        $oldFiles = [];
        foreach (['banner', 'banner_video'] as $field) {
            if ($request->hasFile($field)) {
                $oldFiles[]         = $careerPage->$field;
                $careerPage->$field = $request->file($field)->store('career', 'public');
            } elseif ($request->boolean('remove_' . $field)) {
                $oldFiles[]         = $careerPage->$field;
                $careerPage->$field = null;
            }
        }

        $careerPage->save();

        foreach (array_filter($oldFiles) as $path) {
            Storage::disk('public')->delete($path);
        }

        $message = 'Career banner updated successfully.';

        if ($request->expectsJson()) {
            return response()->json(['status' => true, 'message' => $message]);
        }

        return redirect()->route('admin.career.banner')->with('success', $message);
    }
}
