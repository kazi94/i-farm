<?php
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\IntrantsImport;
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

Route::get('/import', function () {
    Excel::import(new IntrantsImport, 'test.xlsx');
});
