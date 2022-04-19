<?php

use App\Http\Controllers\KafkaConsumerController;
use App\Http\Controllers\KafkaProducerController;
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

Route::get('start-consumer', [KafkaConsumerController::class, 'StartConsumer']);
//http://localhost/laravel-lafka-example/public/api/start-consumer

Route::get('produce-message', [KafkaProducerController::class, 'ProduceSingleMessage']);
//http://localhost/laravel-lafka-example/public/api/produce-message
