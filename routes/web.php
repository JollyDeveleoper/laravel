<?php

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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'auth', 'prefix' => 'schedule'], function () {

    Route::post('/edit', 'ScheduleController@edit')->name('edit');
    Route::post('/add', 'ScheduleController@add')->name('add');
    Route::post('/delete', 'ScheduleController@delete')->name('delete');

    Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

});
Route::group(['prefix' => 'schedule'], function () {
    Route::get('/', 'ScheduleController@index')->name('schedule');
    Route::get('/{day}', 'ScheduleController@index');
});


Route::get('/', 'HomeController@welcome');
Route::match(['GET', 'POST'], 'reposter', 'Bot\BotController@index');

Auth::routes([
    'register' => true, // Registration Routes...
    'reset' => true, // Password Reset Routes...
    'verify' => true, // Email Verification Routes...
]);
