<?php

use App\Http\Controllers\ChatController;
use App\Http\Controllers\IntroController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('intro', [IntroController::class, 'store']);

Route::prefix('message')->group(function () {
    Route::post('send', function () {
        return json_encode([
            "status" => "success",
            "data" => [
                'chat' => [
                    "id" => 1,
                    "chat" => "Hai",
                    "fromMe" => true,
                    "created_at" => "2022-01-05"
                ],
                'jawaban' => [
                    'id' => 2,
                    'chat' => 'kenal toh?',
                    'fromMe' => false,
                    'created_at' => '2022-01-05'
                ]
            ]
        ]);
    });
    Route::post('reply', function () {
        return json_encode(["status" => "success"]);
    });
    Route::post('send', function () {
        return json_encode([
            "status" => "success",
            "data" => [
                'chat' => [
                    "id" => 1,
                    "chat" => "Hai",
                    "fromMe" => true,
                    "created_at" => "2022-01-05"
                ],
                'jawaban' => [
                    'id' => 2,
                    'chat' => 'kenal toh?',
                    'fromMe' => false,
                    'created_at' => '2022-01-05'
                ]
            ]
        ]);
    });
    Route::post('send', [ChatController::class, 'store']);
    Route::get('chat', [ChatController::class, 'show']);
});
