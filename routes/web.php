<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Mail;
use App\Mail\HelloMail;

Route::get('/', [MainController::class, 'index']);

Route::post('/save_user', [MainController::class, 'save_user']);

Route::get('/getBornToday', [MainController::class, 'getBornToday']);

// Route::get('/mail', function () {
//     Mail::to('laravel179@gmail.com')->send(new HelloMail());
// });


Route::get('lang/{locale}', function ($locale) {
    session(['locale' => $locale]);
    return redirect()->back();
})->name('setLocale');
