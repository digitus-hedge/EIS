<?php

namespace App\Http\Controllers;

use App\Models\Service;
use App\Models\ServicePage;

class ServiceDetailController extends Controller
{
    /**
     * Show the main Services listing page (header banner + service grid).
     * Route: GET /services -> services
     */
    public function index()
    {
        $servicePage = ServicePage::first();

        return view('web.services', compact('servicePage'));
    }

    /**
     * Show an individual Service detail page.
     * Route: GET /service/{slug} -> service.details
     */
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