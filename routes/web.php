<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SearchController;

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

Route::controller(SearchController::class)->group(function () {
    Route::get('/', 'index')->name('search.index');
    Route::get('/search', 'index')->name('search.index.alt');
    Route::post('/search', 'search')->name('search.perform');
});