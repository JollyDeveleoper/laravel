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

Route::group(['middleware' => 'auth'], function() {
    Route::get('schedule', 'ScheduleController@index');

    Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

});

Route::get('/', 'HomeController@welcome');
Route::match(['GET','POST'],'reposter', 'Bot\BotController@index');

Auth::routes();
