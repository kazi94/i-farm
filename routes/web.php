<?php

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

Route::get('/', function () {
    return view('welcome');
});


Route::get('/dowload-pdf', App\Http\Controllers\TestController::class)->name('download-pdf');

Route::middleware(['auth'])->group(function () {
    Route::get('/farmer/{farmer}/preconisation/{preconisation}/print-fr', App\Http\Controllers\User\Farmer\FarmerPreconisationPrintFrController::class)->name('farmer.preconisation-fr.print');
    Route::get('/farmer/{farmer}/preconisation/{preconisation}/print-ar', App\Http\Controllers\User\Farmer\FarmerPreconisationPrintArController::class)->name('farmer.preconisation-ar.print');
});
