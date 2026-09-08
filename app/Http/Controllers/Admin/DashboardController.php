<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
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