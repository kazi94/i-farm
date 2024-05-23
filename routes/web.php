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


Route::middleware(['auth'])->group(function () {
    Route::get('/farmer/{farmer}/preconisation/{preconisation}/print', App\Http\Controllers\User\Farmer\FarmerPreconisationPrintController::class)->name('farmer.preconisation.print');
    Route::get('/preconisation/{preconisation}/print', App\Http\Controllers\User\Preconisation\PreconisationPrintController::class)->name('preconisation.print');
});
