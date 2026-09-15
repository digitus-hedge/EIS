<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use App\Models\OperationVideo;
use App\Models\RegionalLocation;
use App\Models\Certificate;

class DashboardController extends Controller
{
     public function index()
    {
        $counts = [
            'services'          => Service::count(),
            'operations'        => OperationVideo::count(),
            'regionals'         => RegionalLocation::count(),
            'certificates'      => Certificate::count(),
        
        ];

        return view('admin.dashboard', compact('counts'));
    }

    public function home()
    {
        return view('admin.home');
    }

    public function homeBanner()
{
    return view('admin.home-banner');
}

public function homeAbout()
{
    return view('admin.home-about');
}

    public function about()
    {
        return view('admin.about');
    }

    public function services()
    {
        return view('admin.services');
    }

    public function contacts()
    {
        return view('admin.contacts');
    }
}