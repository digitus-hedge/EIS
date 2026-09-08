<?php

namespace App\Http\Controllers;

use App\Models\AboutUs;

class AboutPageController extends Controller
{
    public function index()
    {
        $about = AboutUs::first();

        return view('web.about', compact('about'));
    }
}