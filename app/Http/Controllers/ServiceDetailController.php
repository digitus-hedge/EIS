<?php

namespace App\Http\Controllers;

use App\Models\Service;

class ServiceDetailController extends Controller
{
    public function show($slug)
    {
        $service = Service::where('slug', $slug)->firstOrFail();

        $relatedServices = Service::where('status', 1)
            ->where('id', '!=', $service->id)
            ->limit(3)
            ->get();

        return view('web.service_details', compact('service', 'relatedServices'));
    }
}
