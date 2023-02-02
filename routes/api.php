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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('ask', function (
    Request $request,
    \App\Services\InferenceRunner $inferenceRunner,
    \App\Services\ChunkHolder $chunkHolder
) {
    if (empty($request->input('question'))) {
        return [];
    }

    $chunks = $inferenceRunner->run($request->input('question'));

    if (! count($chunks)) {
        return [];
    }

    return $chunkHolder->ask($chunks, 70);
})->name('api.ask');
