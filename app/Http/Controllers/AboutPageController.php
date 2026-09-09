<?php

namespace App\Http\Controllers;

use App\Models\AboutUs;
use App\Models\RegionalLocation;
use App\Models\OperationVideo;
use App\Models\WhyChooseUs;

class AboutPageController extends Controller
{
    public function index()
    {
        $about = AboutUs::first();

        $locations = RegionalLocation::with('offices')
            ->orderBy('sort_order')
            ->get();

        $operationVideos = OperationVideo::orderBy('id')->get();
        $mainVideo = $operationVideos->first();

        $whyChooseUs = WhyChooseUs::first();
        $capabilityItems = collect($whyChooseUs->items ?? [])->values();

        return view('web.about', compact(
            'about', 'locations', 'mainVideo', 'operationVideos', 'capabilityItems'
        ));
    }
}