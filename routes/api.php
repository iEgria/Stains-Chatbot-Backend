<?php

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
    Route::get('chat', function () {
        return response()->json([
            'data' => [
                [
                    'id' => 1,
                    'chat' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Distinctio ad vero aliquam, repellendus doloremque culpa officia dolorem. Maiores ducimus ad quibusdam quo eum temporibus eos, minus quisquam voluptatibus, ratione tenetur.',
                    'fromMe' => true,
                    'created_at' => '2022-01-05'
                ],
                [
                    'id' => 2,
                    'chat' => 'Lorem ipsum dolor sit amet consectetur adipisicing elit. Distinctio ad vero aliquam, repellendus doloremque culpa officia dolorem. Maiores ducimus ad quibusdam quo eum temporibus eos, minus quisquam voluptatibus, ratione tenetur.',
                    'fromMe' => false,
                    'created_at' => '2022-01-05'
                ],
                [
                    'id' => 3,
                    'chat' => 'Anjay',
                    'fromMe' => true,
                    'created_at' => '2022-01-05'
                ],
                [
                    'id' => 4,
                    'chat' => 'Mabar',
                    'fromMe' => false,
                    'created_at' => '2022-01-05'
                ],
                [
                    'id' => 1,
                    'chat' => 'Hai',
                    'fromMe' => true,
                    'created_at' => '2022-01-05'
                ],
            ]
        ]);
    });
});
