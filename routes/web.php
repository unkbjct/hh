<?php

use App\Http\Controllers\GetControllers\ApiController as ApiGet;
use App\Http\Controllers\GetControllers\SingleController as SingleViews;
use App\Http\Controllers\PostController\PersonalController as PersonalCore;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::get('/', [SingleViews::class, 'welcome'])->name('home');

Route::get('/signup', [SingleViews::class, 'signup'])->name('signup');

Route::get('/login', [SingleViews::class, 'login'])->name('login');


Route::get('/logout', function () {
    Auth::logout();
    return redirect()->back();
})->name('logout');

Route::group(['prefix' => 'api'], function () {
    Route::post('/signup', [PersonalCore::class, 'signup'])->name('api.signup');
    Route::post('/login', [PersonalCore::class, 'login'])->name('api.login');
    Route::get('/city/find', [ApiGet::class, 'cityFind'])->name('api.city.find');
    Route::get('/city/{city}/set', [ApiGet::class, 'citySet'])->name('api.city.set');
    Route::get('/city/define', [ApiGet::class, 'cityDefine'])->name('api.city.define');
});
