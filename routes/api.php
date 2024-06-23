<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\leadController;

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
// Route for index method (GET request)
Route::get('/lead', [leadController::class, 'index']);

// Route for leadpost method (POST request)
Route::post('/leadpost', [leadController::class, 'leadpost']);

// Route for getData method (GET request with 'id' parameter)
Route::get('/lead/{id}', [leadController::class, 'getData']);
