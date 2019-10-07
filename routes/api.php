<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => 'api'], function () {
    // Пары на все дни
    Route::post('/getAllCouples', 'Api\ApiController@getAllCouples');

    // Пары на конкретный день
    Route::post('/getCouple', 'Api\ApiController@getCouple');

    // Следующая пара
    Route::post('/getNextCouple', 'Api\ApiController@getNextCouple');

    // Время на сервере
    Route::post('/serverTime', 'Api\ApiController@serverTime');

});
