<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AlternatifController;
use App\Http\Controllers\KriteriaController;
use App\Http\Controllers\PerhitunganController;
use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
// dd(bcrypt('123123'));
Auth::routes();

Route::get('/', [HomeController::class, 'index']);

Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::resource('/user', UserController::class);

Route::resource('/alternatif', AlternatifController::class);

// dd('oke');
Route::resource('/kriteria', KriteriaController::class);
Route::resource('/perhitungan', PerhitunganController::class);
Route::post('/perhitungan/delete_all', [PerhitunganController::class,'delete_all'])->name('perhitungan.delete_all');
Route::get('/pdf', [PerhitunganController::class,'export_pdf'])->name('perhitungan.pdf');


