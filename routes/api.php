<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherSkillController;
use App\Http\Controllers\WalletHistoryController;
use App\Http\Controllers\StudentFinancialController;

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
Route::put('/admin/student/{id}', [StudentController::class, 'updateById']); //TODO: require admin authorization for this route
Route::post('/admin/student/{id}/block', [StudentController::class, 'block']); //TODO: require admin authorization for this route
Route::get('/admin/student/{id}/unblock', [StudentController::class, 'unblock']); //TODO: require admin authorization for this route

Route::post('/admin/student/wallet/{id}', [WalletHistoryController::class, 'store']); //TODO: require admin authorization for this route
Route::get('/admin/student/wallet', [WalletHistoryController::class, 'get']); //TODO: require admin authorization for this route

Route::post('/admin/teacher/skill', [TeacherSkillController::class, 'storeByAdmin']);
Route::post('/admin/teacher/skill/{id}', [TeacherSkillController::class, 'updateById']);

Route::post('/admin/student/financial/{id}', [StudentFinancialController::class, 'store']); //TODO: require admin authorization for this route
Route::get('/admin/student/financial', [StudentFinancialController::class, 'get']); //TODO: require admin authorization for this route
Route::put('/admin/student/financial/{id}', [StudentFinancialController::class, 'updateById']); //TODO: require admin authorization for this route
