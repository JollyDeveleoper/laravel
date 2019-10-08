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
    Route::any('/couples', [ApiController::class, 'getAllCouples']);

    // Пары на конкретный день
    Route::any('/couples/{day}', [ApiController::class, 'getCouple']);

    // Следующая пара
    Route::any('/nextCouple', [ApiController::class, 'getNextCouple']);

    // Время на сервере
    Route::any('/serverTime', [ApiController::class, 'serverTime']);

});
