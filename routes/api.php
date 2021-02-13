<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\V1\LineController;

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

// メッセージ送信
Route::get('/v1/delivery', [LineController::class, 'delivery']);

// Webhookでメッセージを受け取る受け口
Route::post('/v1/callback', [LineController::class, 'callback'])
    ->middleware('signedbyline');

