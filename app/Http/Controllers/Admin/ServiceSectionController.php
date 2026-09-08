<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceSectionRequest;
use App\Models\ServiceSection;
use Illuminate\Support\Facades\Storage;

class ServiceSectionController extends Controller
{
  
    public function index()
    {
        $serviceSection = ServiceSection::first() ?? new ServiceSection();
        return view('admin.service_section.form', compact('serviceSection'));
    }

    public function store(ServiceSectionRequest $request)
    {
        $data = $request->validated();

        $serviceSection = ServiceSection::first() ?? new ServiceSection();

        // Handle image separately — don't let fill() overwrite it with the raw UploadedFile
        unset($data['image']);
        $serviceSection->fill($data);

        if ($request->hasFile('image')) {
            // Delete old image if one exists, to avoid orphaned files
            if (!empty($serviceSection->image)) {
                Storage::disk('public')->delete($serviceSection->image);
            }

            $serviceSection->image = $request->file('image')->store('service-section', 'public');
        }

        $serviceSection->save();

        return redirect()
            ->route('admin.home.services.section')
            ->with('success', 'Service section saved successfully.');
    }
}