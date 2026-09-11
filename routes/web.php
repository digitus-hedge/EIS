<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\ClientSectionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\HomeAboutController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\ServiceSectionController;
use App\Http\Controllers\Admin\StatController;
use App\Http\Controllers\Admin\WhyChooseUsController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ServiceDetailController;
use App\Http\Controllers\Admin\AboutController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AboutPageController;
use App\Http\Controllers\Admin\ServicePageController;


Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/about', [AboutPageController::class, 'index'])->name('about');

Route::get('/services/{slug}', [ServiceDetailController::class, 'show']);
Route::get('/services', [ServiceDetailController::class, 'index'])->name('services');
 Route::get('/services/{slug}', [ServiceDetailController::class, 'show']);

Route::prefix('admin')->name('admin.')->group(function () {

    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.submit');

    Route::middleware('auth')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::get('home', [DashboardController::class, 'home'])->name('home');

        Route::get('home/banner', [BannerController::class, 'index'])->name('home.banner');
        Route::post('home/banner', [BannerController::class, 'store'])->name('home.banner.store');

        Route::get('home/about', [HomeAboutController::class, 'index'])->name('home.about');
        Route::post('home/about', [HomeAboutController::class, 'store'])->name('home.about.store');

        Route::get('home/stats', [StatController::class, 'index'])->name('home.stats');
        Route::post('home/stats', [StatController::class, 'store'])->name('home.stats.store');

        Route::get('home/service-section', [ServiceSectionController::class, 'index'])->name('home.services.section');
        Route::post('home/service-section', [ServiceSectionController::class, 'store'])->name('home.services.section.store');

        Route::get('home/services', [ServiceController::class, 'index'])->name('home.services');
        Route::get('home/services/create', [ServiceController::class, 'create'])->name('home.services.create');
        Route::post('home/services', [ServiceController::class, 'store'])->name('home.services.store');
        Route::get('home/services/{service}/edit', [ServiceController::class, 'edit'])->name('home.services.edit');
        Route::put('home/services/{service}', [ServiceController::class, 'update'])->name('home.services.update');
        Route::delete('home/services/{service}', [ServiceController::class, 'destroy'])->name('home.services.destroy');


        

        Route::get('home/clients', [ClientSectionController::class, 'index'])->name('home.clients');
        Route::post('home/clients', [ClientSectionController::class, 'store'])->name('home.clients.store');

        Route::get('home/why-choose-us', [WhyChooseUsController::class, 'index'])->name('home.why-choose-us');
        Route::post('home/why-choose-us', [WhyChooseUsController::class, 'store'])->name('home.why-choose-us.store');

        Route::get('about', [DashboardController::class, 'about'])->name('about');
        Route::get('services', [DashboardController::class, 'services'])->name('services');
        Route::get('contacts', [DashboardController::class, 'contacts'])->name('contacts');
        Route::post('logout', [AuthController::class, 'logout'])->name('logout');

        //about us page 
        Route::get('banner', [AboutController::class, 'banner'])->name('about.banner');
        Route::post('banner', [AboutController::class, 'storeBanner'])->name('about.banner.store');
        Route::get('about', [AboutController::class, 'about'])->name('about.about');
        Route::post('about', [AboutController::class, 'storeAbout'])->name('about.about.store');
        Route::get('who-we-are', [AboutController::class, 'whoWeAre'])->name('about.who-we-are');
        Route::post('who-we-are', [AboutController::class, 'storeWhoWeAre'])->name('about.who-we-are.store');

        Route::get('regional-footprint', [AboutController::class, 'regionalFootprint'])->name('about.regional-footprint');
        Route::post('regional-footprint', [AboutController::class, 'storeRegionalLocation'])->name('about.regional-footprint.store');
        Route::post('regional-footprint/{location}/offices', [AboutController::class, 'storeRegionalOffice'])->name('about.regional-footprint.offices.store');
        Route::delete('regional-footprint/{location}', [AboutController::class, 'destroyRegionalLocation'])->name('about.regional-footprint.destroy');
        Route::delete('regional-footprint/offices/{office}', [AboutController::class, 'destroyRegionalOffice'])->name('about.regional-footprint.offices.destroy');

        Route::get('operation', [AboutController::class, 'operation'])->name('about.operation');
        Route::post('operation/videos', [AboutController::class, 'storeOperationVideo'])->name('about.operation.videos.store');
        Route::delete('operation/videos/{video}', [AboutController::class, 'destroyOperationVideo'])->name('about.operation.videos.destroy');

        Route::get('service/banner', [ServicePageController::class, 'banner'])->name('service.banner');
        Route::post('service/banner', [ServicePageController::class, 'storeBanner'])->name('service.banner.store');

        Route::get('service/our-service', [ServicePageController::class, 'ourService'])->name('service.our-service');
        Route::post('service/our-service', [ServicePageController::class, 'storeOurService'])->name('service.our-service.store');
    

    });

});