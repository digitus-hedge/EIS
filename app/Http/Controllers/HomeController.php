<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\ClientSection;
use App\Models\HomeAbout;
use App\Models\Service;
use App\Models\ServiceSection;
use App\Models\Stat;
use App\Models\WhyChooseUs;

class HomeController extends Controller
{
    public function index()
    {
        $banner = Banner::first();
        $about  = HomeAbout::first();
        $stat   = Stat::first();
        $serviceSection = ServiceSection::first();
        $services = Service::where('status', 1)
            ->get();
        $clientSection = ClientSection::first();
        $whyChooseUs = WhyChooseUs::first();

        return view('web.home', compact('banner', 'about', 'stat', 'serviceSection', 'services', 'clientSection', 'whyChooseUs'));
    }
}