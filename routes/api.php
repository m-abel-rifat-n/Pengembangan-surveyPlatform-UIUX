<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/nasa-tlx/submit', [\App\Http\Controllers\API\NasaTlxApiController::class, 'store'])->name('api.nasa-tlx.submit');
Route::post('/visawi-s/submit', [\App\Http\Controllers\API\VisawiSApiController::class, 'store'])->name('api.visawi-s.submit');