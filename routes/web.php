<?php

use App\Http\Controllers\GetControllers\ApiController as ApiGet;
use App\Http\Controllers\GetControllers\PersonalControllers\ResumeController as ResumeViews;
use App\Http\Controllers\GetControllers\SingleController as SingleViews;
use App\Http\Controllers\PostController\PersonalController as PersonalCore;
use App\Http\Controllers\PostControllers\ResumeController as ResumeCore;
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
Route::get('/logout', [SingleViews::class, 'logout'])->name('logout');

Route::group(['prefix' => 'personal', 'middleware' => 'auth'], function () {
    Route::get('/', [SingleViews::class, 'personal'])->name('personal');
    Route::group(['prefix' => 'resume'], function () {
        Route::get('/', [ResumeViews::class, 'list'])->name('personal.resume.list');
        Route::get('/applicant/resume/{resume}', [ResumeViews::class, 'applicant'])->name('personal.resume.applicant');
    });
});



Route::group(['prefix' => 'api'], function () {
    Route::post('/signup', [PersonalCore::class, 'signup'])->name('api.signup');
    Route::post('/login', [PersonalCore::class, 'login'])->name('api.login');

    Route::get('/city/find', [ApiGet::class, 'cityFind'])->name('api.city.find');
    Route::get('/city/{city}/set', [ApiGet::class, 'citySet'])->name('api.city.set');
    Route::get('/city/define', [ApiGet::class, 'cityDefine'])->name('api.city.define');

    Route::post('/personal/edit', [PersonalCore::class, 'edit'])->name('api.personal.edit');
    Route::post('/password/edit', [PersonalCore::class, 'editPassword'])->name('api.password.edit');

    Route::group(['prefix' => 'resume'], function () {
        Route::post('/resumne/create', [ResumeCore::class, 'create'])->name('api.resume.create');
        Route::group(['prefix' => 'edit'], function () {
            Route::post('/', [ResumeCore::class, 'edit'])->name('api.resume.edit');
            Route::post('/personal', [ResumeCore::class, 'personal'])->name('api.resume.edit.personal');
            Route::post('/contacts', [ResumeCore::class, 'contacts'])->name('api.resume.edit.contacts');
            Route::post('/job', [ResumeCore::class, 'job'])->name('api.resume.edit.job');
            Route::post('/experience', [ResumeCore::class, 'experience'])->name('api.resume.edit.experience');
            Route::post('/experience/item', [ResumeCore::class, 'experienceItem'])->name('api.resume.edit.experience.item');
            Route::post('/experience/item/remove', [ResumeCore::class, 'experienceItemRemove'])->name('api.resume.edit.experience.item.remove');
            Route::post('/education/item', [ResumeCore::class, 'educationItem'])->name('api.resume.edit.education.item');
            Route::post('/education/item/edit', [ResumeCore::class, 'educationItemEdit'])->name('api.resume.edit.education.item.edit');
            Route::post('/education/item/remove', [ResumeCore::class, 'educationItemRemove'])->name('api.resume.edit.education.item.remove');
        });
        Route::post('/publish', [ResumeCore::class, 'publish'])->name('api.resume.publish');
    });
});
