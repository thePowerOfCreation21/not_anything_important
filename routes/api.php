<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\TeacherSkillController;
use App\Http\Controllers\WalletHistoryController;
use App\Http\Controllers\EducationalYearController;
use App\Http\Controllers\StudentFinancialController;
use App\Http\Controllers\GeneralStatisticController;
use App\Http\Controllers\TeacherFinancialController;
use App\Http\Controllers\CourseController;

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


Route::post('/admin/login', [AdminController::class, 'login']);

Route::post('/student/register_request', [StudentController::class, 'registerRequest']);

Route::get('/educational_year', [EducationalYearController::class, 'get']);

Route::group([
    'middleware' => ['auth:admin']
], function(){
    Route::post('/admin/student/wallet', [WalletHistoryController::class, 'store']);
    Route::get('/admin/student/wallet', [WalletHistoryController::class, 'get']);

    Route::post('/admin/student/financial', [StudentFinancialController::class, 'store']);
    Route::put('/admin/student/financial/{id}', [StudentFinancialController::class, 'updateById']);
    Route::delete('/admin/student/financial/{id}', [StudentFinancialController::class, 'deleteById']);
    Route::get('/admin/student/financial', [StudentFinancialController::class, 'get']);
    Route::get('/admin/student/financial/{id}', [StudentFinancialController::class, 'getById']);

    Route::post('/admin/student', [StudentController::class, 'storeByAdmin']);
    Route::get('/admin/student', [StudentController::class, 'get']);
    Route::get('/admin/student/{id}', [StudentController::class, 'getById']);
    Route::put('/admin/student/{id}', [StudentController::class, 'updateById']);
    Route::post('/admin/student/{id}/block', [StudentController::class, 'block']);
    Route::get('/admin/student/{id}/unblock', [StudentController::class, 'unblock']);
    Route::get('/admin/student/{id}/accept', [StudentController::class, 'acceptById']);
    Route::get('/admin/student/{id}/reject', [StudentController::class, 'rejectById']);

    Route::post('/admin/teacher/skill', [TeacherSkillController::class, 'storeByAdmin']);
    Route::put('/admin/teacher/skill/{id}', [TeacherSkillController::class, 'updateById']);
    Route::delete('/admin/teacher/skill/{id}', [TeacherSkillController::class, 'deleteById']);
    Route::get('/admin/teacher/skill', [TeacherSkillController::class, 'get']);
    Route::get('/admin/teacher/skill/{id}', [TeacherSkillController::class, 'getById']);

    Route::get('/admin/general_statistic', [GeneralStatisticController::class, 'get']);

    Route::post('/admin/course', [CourseController::class, 'storeByAdmin']);
    Route::put('/admin/course/{id}', [CourseController::class, 'updateById']);
    Route::delete('/admin/course/{id}', [CourseController::class, 'deleteById']);
    Route::get('/admin/course', [CourseController::class, 'get']);
    Route::get('/admin/course/{id}', [CourseController::class, 'getById']);

    Route::post('/admin', [AdminController::class, 'register']);
    Route::put('/admin/{id}', [AdminController::class, 'updateById']);
    Route::get('/admin', [AdminController::class, 'get']);
    Route::get('/admin/{id}', [AdminController::class, 'getById']);
    Route::delete('/admin/{id}', [AdminController::class, 'deleteById']);

    Route::post('/admin/teacher/financial', [TeacherFinancialController::class, 'store']);
    Route::get('/admin/teacher/financial', [TeacherFinancialController::class, 'get']);
    Route::get('/admin/teacher/financial/{id}', [TeacherFinancialController::class, 'getById']);
});
