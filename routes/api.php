<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;

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

Route::post('/student/register_request', [StudentController::class, 'registerRequest']);

Route::post('/admin/student', [StudentController::class, 'storeByAdmin']); //TODO: require admin authorization for this route
Route::post('/admin/student/block', [StudentController::class, 'block']); //TODO: require admin authorization for this route
