
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
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index']);


Route::get('/services/{slug}', [ServiceDetailController::class, 'show']);


Route::prefix('admin')->name('admin.')->group(function () {

    // Guest routes (login)
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.submit');

    // Protected routes
    Route::middleware('auth')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('home', [DashboardController::class, 'home'])->name('home');

        // Banner Section routes (singleton — one banner only)
        Route::get('home/banner', [BannerController::class, 'index'])->name('home.banner');       // Shows form directly (pre-filled if exists)
        Route::post('home/banner', [BannerController::class, 'store'])->name('home.banner.store'); // Creates or updates

        Route::get('home/about', [HomeAboutController::class, 'index'])->name('home.about');       // Shows form directly (pre-filled if exists)
        Route::post('home/about', [HomeAboutController::class, 'store'])->name('home.about.store'); // Creates or updates

        Route::get('home/stats', [StatController::class, 'index'])->name('home.stats');
        Route::post('home/stats', [StatController::class, 'store'])->name('home.stats.store');

        Route::get('home/service-section', [ServiceSectionController::class, 'index'])->name('home.services.section');       // Shows form directly (pre-filled if exists)
        Route::post('home/service-section', [ServiceSectionController::class, 'store'])->name('home.services.section.store'); // Creates or updates


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
    });







    //front end
    Route::get('/', function () {
    return view('home');
    })->name('home');

    Route::get('/about', function () {
        return view('web.about');
    })->name('about');
});
