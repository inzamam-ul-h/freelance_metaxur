<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AvailabilityController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



Auth::routes();
Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/about', [App\Http\Controllers\HomeController::class, 'view']);
Route::get('/get-slots', [HomeController::class, 'getSlots'])->name('get-slots');
Route::post('/booking',[HomeController::class,'storeBooking'])->name('booking');
Route::view('/about', 'frontend.about')->name('about');
// Route::view('/services', 'frontend.services')->name('services');
Route::view('/faqs', 'frontend.faqs')->name('faqs');
// Route::view('/pricing', 'frontend.pricing')->name('pricing');
Route::view('/contact', 'frontend.contact')->name('contact');
Route::view('/howItWork', 'frontend.howItWork')->name('howItWork');


//services
Route::view('/thesis_writing_&_Editing', 'frontend.services.thesis')->name('thesis');
Route::view('/Research_Assistance_for_Extraordinary_Visa', 'frontend.services.research')->name('research');
Route::view('/Junior_Researcher_Program', 'frontend.services.junior')->name('junior');
Route::view('/technology_&_consulting', 'frontend.services.tech&consulting')->name('t&c');

// Admin routes, require login and admin role
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin', [AvailabilityController::class, 'index'])->name('admin.dashboard');
    Route::post('/admin/availability', [AvailabilityController::class, 'store'])->name('availability.store');
    Route::get('/freelancer/bookings', [AvailabilityController::class, 'viewBookings'])->name('admin.viewBookings');

});
