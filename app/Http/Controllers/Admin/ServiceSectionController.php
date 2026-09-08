<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ServiceSectionRequest;
use App\Models\ServiceSection;

class ServiceSectionController extends Controller
{
    /**
     * SHOW FORM — always the single Service Section (or empty model if none exists yet)
     */
    public function index()
    {
        $serviceSection = ServiceSection::first() ?? new ServiceSection();
        return view('admin.service_section.form', compact('serviceSection'));
    }

    /**
     * STORE — creates the Service Section if none exists, otherwise updates the existing one
     */
    public function store(ServiceSectionRequest $request)
    {
        $data = $request->validated();

        $serviceSection = ServiceSection::first() ?? new ServiceSection();
        $serviceSection->fill($data);
        $serviceSection->save();

        return redirect()
            ->route('admin.home.services.section')
            ->with('success', 'Service section saved successfully.');
    }
}
