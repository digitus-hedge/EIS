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
        $services = Service::latest()->get(); // pull all saved services

        return view('web.services', compact('servicePage', 'services'));
    }

    /**
     * Show an individual Service detail page.
     * Route: GET /service/{slug} -> service.details
     */
  
    public function show($slug)
    {
        $service = Service::where('slug', $slug)->firstOrFail();

        $relatedServices = Service::where('id', '!=', $service->id)
            ->latest()
            ->limit(4)
            ->get();

        return view('web.service_detail', compact('service', 'relatedServices'));
    }
    
}