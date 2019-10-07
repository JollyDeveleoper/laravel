<?php

use App\Http\Controllers\Api\ApiController;
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

Route::group(['middleware' => 'api', 'namespace' => 'Api'], function () {
    // Пары на все дни
    Route::post('/getAllCouples', [ApiController::class, 'getAllCouples']);

    // Пары на конкретный день
    Route::post('/getCouple', [ApiController::class, 'getCouple']);

    // Следующая пара
    Route::post('/getNextCouple', [ApiController::class, 'getNextCouple']);

    // Время на сервере
    Route::post('/serverTime', [ApiController::class, 'serverTime']);

});
