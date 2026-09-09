<?php

namespace App\Http\Controllers;

use App\Models\AboutUs;
use App\Models\RegionalLocation;

class AboutPageController extends Controller
{
    public function index()
    {
        $about = AboutUs::first();

        $locations = RegionalLocation::with('offices')
            ->orderBy('sort_order')
            ->get();

        return view('web.about', compact('about', 'locations'));
    }
}