<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\NewsController;
use App\Http\Controllers\StatController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ClassController;
use App\Http\Controllers\OtherController;
use App\Http\Controllers\AdviceController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\SurveyController;
use App\Http\Controllers\HeaderController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\AboutUsController;
use App\Http\Controllers\FoodDateController;
use App\Http\Controllers\FinancialController;
use App\Http\Controllers\ClassFileController;
use App\Http\Controllers\GuestPageController;
use App\Http\Controllers\AdviceHourController;
use App\Http\Controllers\AdviceDateController;
use App\Http\Controllers\ClassScoreController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\ReportCardController;
use App\Http\Controllers\ClassCourseController;
use App\Http\Controllers\ClassReportsController;
use App\Http\Controllers\TeacherSkillController;
use App\Http\Controllers\GalleryImageController;
use App\Http\Controllers\SurveyOptionController;
use App\Http\Controllers\SurveyAnswerController;
use App\Http\Controllers\ClassMessagesController;
use App\Http\Controllers\WalletHistoryController;
use App\Http\Controllers\FinancialTypeController;
use App\Http\Controllers\ReportCardExamController;
use App\Http\Controllers\SurveyCategoryController;
use App\Http\Controllers\MessageTemplateController;
use App\Http\Controllers\EducationalYearController;
use App\Http\Controllers\TeacherEntranceController;
use App\Http\Controllers\HomePageContentController;
use App\Http\Controllers\StudentFinancialController;
use App\Http\Controllers\GeneralStatisticController;
use App\Http\Controllers\TeacherFinancialController;
use App\Http\Controllers\ContactUsContentController;
use App\Http\Controllers\InventoryProductController;
use App\Http\Controllers\StudentDisciplineController;
use App\Http\Controllers\AttendanceStudentController;
use App\Http\Controllers\ClassScoreStudentController;
use App\Http\Controllers\StudentReportCardController;
use App\Http\Controllers\ClassMessageStudentController;
use App\Http\Controllers\MessageReceiverPivotController;
use App\Http\Controllers\TeacherWorkExperienceController;
use App\Http\Controllers\TeacherEntranceHistoryController;
use App\Http\Controllers\InventoryProductHistoryController;

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

Route::post('/student/login', [StudentController::class, 'login']);
Route::post('/student/send_otp', [StudentController::class, 'sendOtp']);
Route::post('/student/check_otp', [StudentController::class, 'checkOtp']);

Route::post('/student/register_request', [StudentController::class, 'registerRequest']);

Route::post('/teacher/register_request', [TeacherController::class, 'registerRequest']);

Route::get('/educational_year', [EducationalYearController::class, 'get']);

Route::get('/contact_us_content', [ContactUsContentController::class, 'get']);

Route::get('/gallery_image', [GalleryImageController::class, 'get']);
Route::get('/gallery_image/{id}', [GalleryImageController::class, 'getById']);

Route::get('/about_us', [AboutUsController::class, 'get']);

Route::get('/home_page_content', [HomePageContentController::class, 'get']);

Route::get('/header', [HeaderController::class, 'get']);

Route::get('/news', [NewsController::class, 'get']);
Route::get('/news/{id}', [NewsController::class, 'getById']);

Route::post('/teacher/login', [TeacherController::class, 'login']);

Route::get('/user_type', [OtherController::class, 'getUserType'])->middleware(['auth:sanctum']);

Route::get('/page/home', [GuestPageController::class, 'getHomePage']);

Route::group([
    'middleware' => ['auth:admin']
], function(){
    Route::get('/admin/info', [AdminController::class, 'getInfo']);
    Route::get('/admin/student_report_card', [StudentReportCardController::class, 'get']);
    Route::get('/admin/student_report_card/{id}', [StudentReportCardController::class, 'getById']);

    Route::post('/admin/report_card', [ReportCardController::class, 'store']);
    Route::put('/admin/report_card/{id}', [ReportCardController::class, 'updateById']);
    Route::get('/admin/report_card', [ReportCardController::class, 'get']);
    Route::get('/admin/report_card/{id}', [ReportCardController::class, 'getById']);
    Route::get('/admin/report_card/issue', [ReportCardController::class, 'issueReportCards']);

    Route::get('/admin/report_card_exam', [ReportCardExamController::class, 'get']);
    Route::get('/admin/report_card_exam/{id}', [ReportCardExamController::class, 'getById']);
    Route::put('/admin/report_card_exam/{id}', [ReportCardExamController::class, 'updateById']);

    Route::post('/admin/news', [NewsController::class, 'store']);
    Route::put('/admin/news/{id}', [NewsController::class, 'updateById']);
    Route::delete('/admin/news/{id}', [NewsController::class, 'deleteById']);

    Route::put('/admin/about_us', [AboutUsController::class, 'update']);

    Route::put('/admin/home_page_content', [HomePageContentController::class, 'update']);

    Route::put('/admin/header', [HeaderController::class, 'update']);

    Route::post('/admin/food_date', [FoodDateController::class, 'store']);

    Route::get('/admin/survey_category', [SurveyCategoryController::class, 'get']);
    Route::get('/admin/survey_category/{id}', [SurveyCategoryController::class, 'getById']);

    Route::post('/admin/survey', [SurveyController::class, 'store']);
    Route::get('/admin/survey', [SurveyController::class, 'get']);
    Route::get('/admin/survey/{id}', [SurveyController::class, 'getById']);
    Route::put('/admin/survey/{id}', [SurveyController::class, 'updateById']);

    Route::put('/admin/survey_option/{id}', [SurveyOptionController::class, 'updateById']);

    Route::post('/admin/survey_category', [SurveyCategoryController::class, 'store']);
    Route::put('/admin/survey_category/{id}', [SurveyCategoryController::class, 'updateById']);

    Route::post('/admin/teacher_entrance', [TeacherEntranceController::class, 'store']);
    Route::get('/admin/teacher_entrance', [TeacherEntranceController::class, 'get']);
    Route::get('/admin/teacher_entrance/{id}', [TeacherEntranceController::class, 'getById']);
    Route::put('/admin/teacher_entrance/{id}', [TeacherEntranceController::class, 'updateById']);
    Route::delete('/admin/teacher_entrance/{id}', [TeacherEntranceController::class, 'deleteById']);

    Route::post('/admin/teacher_entrance_history', [TeacherEntranceHistoryController::class, 'store']);
    Route::put('/admin/teacher_entrance_history/{id}', [TeacherEntranceHistoryController::class, 'updateById']);
    Route::get('/admin/teacher_entrance_history', [TeacherEntranceHistoryController::class, 'get']);
    Route::get('/admin/teacher_entrance_history/{id}', [TeacherEntranceHistoryController::class, 'getById']);
    Route::delete('/admin/teacher_entrance_history/{id}', [TeacherEntranceHistoryController::class, 'deleteById']);

    Route::post('/admin/inventory_product', [InventoryProductController::class, 'store']);
    Route::get('/admin/inventory_product', [InventoryProductController::class, 'get']);
    Route::get('/admin/inventory_product/{id}', [InventoryProductController::class, 'getById']);
    Route::put('/admin/inventory_product/{id}', [InventoryProductController::class, 'updateById']);
    Route::delete('/admin/inventory_product/{id}', [InventoryProductController::class, 'deleteById']);

    Route::post('/admin/gallery_image', [GalleryImageController::class, 'store']);
    Route::delete('/admin/gallery_image/{id}', [GalleryImageController::class, 'deleteById']);

    Route::post('/admin/inventory_product_history', [InventoryProductHistoryController::class, 'store']);
    Route::get('/admin/inventory_product_history', [InventoryProductHistoryController::class, 'get']);
    Route::delete('/admin/inventory_product_history/{id}', [InventoryProductHistoryController::class, 'deleteById']);

    Route::put('/admin/contact_us_content', [ContactUsContentController::class, 'update']);

    Route::post('/admin/student/wallet', [WalletHistoryController::class, 'store']);
    Route::get('/admin/student/wallet', [WalletHistoryController::class, 'get']);

    Route::post('/admin/student/financial/update_paid_by_list', [StudentFinancialController::class, 'updatePaidByList']);
    Route::post('/admin/student/financial/send_sms', [StudentFinancialController::class, 'sendSms']);
    Route::post('/admin/student/financial', [StudentFinancialController::class, 'store']);
    Route::put('/admin/student/financial/{id}', [StudentFinancialController::class, 'updateById']);
    Route::delete('/admin/student/financial/{id}', [StudentFinancialController::class, 'deleteById']);
    Route::get('/admin/student/financial', [StudentFinancialController::class, 'get']);
    Route::get('/admin/student/financial/{id}', [StudentFinancialController::class, 'getById']);

    Route::post('/admin/class/message', [ClassMessagesController::class, 'store']);
    Route::delete('/admin/class/message/{id}', [ClassMessagesController::class, 'deleteById']);

    Route::post('/admin/class/reports', [ClassReportsController::class, 'store']);
    Route::get('/admin/class/reports', [ClassReportsController::class, 'get']);
    Route::get('/admin/class/reports/custom_format', [ClassReportsController::class, 'getInCustomFormat']);
    Route::get('/admin/class/reports/group_by_date', [ClassReportsController::class, 'getGroupByDate']);
    Route::get('/admin/class/reports/{id}', [ClassReportsController::class, 'getById']);
    Route::put('/admin/class/reports/{id}', [ClassReportsController::class, 'updateById']);

    Route::post('/admin/class_course', [ClassCourseController::class, 'store']);
    Route::put('/admin/class_course/{id}', [ClassCourseController::class, 'updateById']);
    Route::get('/admin/class_course', [ClassCourseController::class, 'get']);
    Route::get('/admin/class_course/{id}', [ClassCourseController::class, 'getById']);
    Route::delete('/admin/class_course/{id}', [ClassCourseController::class, 'deleteById']);

    Route::post('/admin/class/score', [ClassScoreController::class, 'storeByAdmin']);
    Route::get('/admin/class/score/group_by_date', [ClassScoreController::class, 'getGroupByDate']);
    Route::put('/admin/class/score/{id}', [ClassScoreController::class, 'updateById']);
    Route::delete('/admin/class/score/{id}', [ClassScoreController::class, 'deleteById']);
    Route::get('/admin/class/score/{id}', [ClassScoreController::class, 'getById']);
    Route::get('/admin/class/score', [ClassScoreController::class, 'get']);

    Route::get('/admin/class_score_student', [ClassScoreStudentController::class, 'get']);

    Route::post('/admin/class_file', [ClassFileController::class, 'storeByAdmin']);
    Route::get('/admin/class_file', [ClassFileController::class, 'getByAdmin']);
    Route::get('/admin/class_file/{id}', [ClassFileController::class, 'getById']);
    Route::delete('/admin/class_file/{id}', [ClassFileController::class, 'deleteById']);
    Route::put('/admin/class_file/{id}', [ClassFileController::class, 'updateByIdByAdmin']);

    Route::post('/admin/class', [ClassController::class, 'storeByAdmin']);
    Route::get('/admin/class', [ClassController::class, 'get']);
    // Route::post('/admin/class/course', [ClassController::class, 'addCoursesToClass']);
    Route::delete('/admin/class/course', [ClassController::class, 'deleteCourseFromClass']);
    Route::post('/admin/class/student', [ClassController::class, 'addStudentsToClass']);
    Route::delete('/admin/class/student', [ClassController::class, 'deleteStudentFromClass']);
    Route::get('/admin/class/{id}', [ClassController::class, 'getById']);
    Route::post('/admin/class/{id}', [ClassController::class, 'updateById']);
    Route::delete('/admin/class/{id}', [ClassController::class, 'deleteById']);

    Route::post('/admin/student/discipline', [StudentDisciplineController::class, 'store']);
    Route::get('/admin/student/discipline', [StudentDisciplineController::class, 'get']);
    Route::get('/admin/student/discipline/{id}', [StudentDisciplineController::class, 'getById']);
    Route::put('/admin/student/discipline/{id}', [StudentDisciplineController::class, 'updateById']);
    Route::delete('/admin/student/discipline/{id}', [StudentDisciplineController::class, 'deleteById']);

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

    Route::post('/admin/teacher/experience', [TeacherWorkExperienceController::class, 'storeByAdmin']);
    Route::put('/admin/teacher/experience/{id}', [TeacherWorkExperienceController::class, 'updateById']);
    Route::delete('/admin/teacher/experience/{id}', [TeacherWorkExperienceController::class, 'deleteById']);
    Route::get('/admin/teacher/experience', [TeacherWorkExperienceController::class, 'get']);
    Route::get('/admin/teacher/experience/{id}', [TeacherWorkExperienceController::class, 'getById']);

    Route::get('/admin/general_statistic', [GeneralStatisticController::class, 'get']);

    Route::post('/admin/course', [CourseController::class, 'storeByAdmin']);
    Route::put('/admin/course/{id}', [CourseController::class, 'updateById']);
    Route::delete('/admin/course/{id}', [CourseController::class, 'deleteById']);
    Route::get('/admin/course', [CourseController::class, 'get']);
    Route::get('/admin/course/{id}', [CourseController::class, 'getById']);

    Route::post('/admin/teacher/financial', [TeacherFinancialController::class, 'store']);
    Route::get('/admin/teacher/financial', [TeacherFinancialController::class, 'get']);
    Route::get('/admin/teacher/financial/{id}', [TeacherFinancialController::class, 'getById']);
    Route::put('/admin/teacher/financial/{id}', [TeacherFinancialController::class, 'updateById']);
    Route::delete('/admin/teacher/financial/{id}', [TeacherFinancialController::class, 'deleteById']);

    Route::post('/admin/attendance', [AttendanceController::class, 'storeByAdmin']);
    Route::get('/admin/attendance', [AttendanceController::class, 'get']);
    Route::get('/admin/attendance/{id}', [AttendanceController::class, 'getById']);
    Route::delete('/admin/attendance/{id}', [AttendanceController::class, 'deleteByIdgit']);
    Route::put('/admin/attendance/{id}', [AttendanceController::class, 'updateById']);
    Route::get('/admin/attendance_group_by_date', [AttendanceController::class, 'getGroupByDate']);

    Route::get('/admin/attendance_student', [AttendanceStudentController::class, 'get']);

    Route::post('/admin/teacher', [TeacherController::class, 'store']);
    Route::get('/admin/teacher', [TeacherController::class, 'get']);
    Route::put('/admin/teacher/{id}', [TeacherController::class, 'updateById']);
    Route::get('/admin/teacher/{id}', [TeacherController::class, 'getById']);
    Route::get('/admin/teacher/{id}/accept', [TeacherController::class, 'acceptById']);
    Route::get('/admin/teacher/{id}/reject', [TeacherController::class, 'rejectById']);

    Route::post('/admin/financial/type', [FinancialTypeController::class, 'store']);
    Route::get('/admin/financial/type', [FinancialTypeController::class, 'get']);
    Route::get('/admin/financial/type/{id}', [FinancialTypeController::class, 'getById']);
    Route::put('/admin/financial/type/{id}', [FinancialTypeController::class, 'updateById']);
    Route::delete('/admin/financial/type/{id}', [FinancialTypeController::class, 'deleteById']);

    Route::post('/admin/financial', [FinancialController::class, 'store']);
    Route::get('/admin/financial', [FinancialController::class, 'get']);
    Route::get('/admin/financial/{id}', [FinancialController::class, 'getById']);
    Route::put('/admin/financial/{id}', [FinancialController::class, 'updateById']);
    Route::delete('/admin/financial/{id}', [FinancialController::class, 'deleteById']);

    Route::post('/admin/message/template', [MessageTemplateController::class, 'store']);
    Route::get('/admin/message/template', [MessageTemplateController::class, 'get']);
    Route::get('/admin/message/template/{id}', [MessageTemplateController::class, 'getById']);
    Route::put('/admin/message/template/{id}', [MessageTemplateController::class, 'updateById']);
    Route::delete('/admin/message/template/{id}', [MessageTemplateController::class, 'deleteById']);

    Route::post('/admin/student/message', [MessageController::class, 'store']);

    Route::post('/admin/advice/date', [AdviceDateController::class, 'store']);
    Route::get('/admin/advice/date', [AdviceDateController::class, 'get']);
    Route::get('/admin/advice/date/{id}', [AdviceDateController::class, 'getById']);
    Route::put('/admin/advice/date/{id}', [AdviceDateController::class, 'updateById']);
    Route::delete('/admin/advice/date/{id}', [AdviceDateController::class, 'deleteById']);

    Route::post('/admin/advice/hour', [AdviceHourController::class, 'store']);
    Route::get('/admin/advice/hour', [AdviceHourController::class, 'get']);
    Route::get('/admin/advice/hour/{id}', [AdviceHourController::class, 'getById']);
    Route::put('/admin/advice/hour/{id}', [AdviceHourController::class, 'updateById']);
    Route::delete('/admin/advice/hour/{id}', [AdviceHourController::class, 'deleteById']);

    Route::put('/admin/advice/{id}', [AdviceController::class, 'updateById']);
    Route::get('/admin/advice', [AdviceController::class, 'get']);

    Route::post('/admin', [AdminController::class, 'register']);
    Route::put('/admin/{id}', [AdminController::class, 'updateById']);
    Route::get('/admin', [AdminController::class, 'get']);
    Route::get('/admin/{id}', [AdminController::class, 'getById']);
    Route::delete('/admin/{id}', [AdminController::class, 'deleteById']);
});

Route::group([
    'middleware' => [
        'auth:student',
        'CheckIfStudentIsBlockedMiddleware'
    ]
], function(){
    Route::get('/student/info', [StudentController::class, 'getInfo']);
    Route::post('/student/change_password', [StudentController::class, 'changePassword']);

    Route::group([
        'middleware' => ['CheckIfStudentShouldChangePassword']
    ], function(){
        Route::get('/student/student_report_card', [StudentReportCardController::class, 'getByStudent']);
        Route::get('/student/student_report_card/{id}', [StudentReportCardController::class, 'getByIdByStudent']);

        Route::post('/student/survey_answer', [SurveyAnswerController::class, 'store']);

        Route::get('/student/survey_category', [SurveyCategoryController::class, 'getByStudent']);
        Route::get('/student/survey_category/{id}', [SurveyCategoryController::class, 'getByIdByStudent']);

        Route::get('/student/advice_hour', [AdviceHourController::class, 'get']);
        Route::get('/student/advice_date', [AdviceDateController::class, 'getByStudent']);
        Route::post('/student/advice', [AdviceController::class, 'storeByStudent']);
        Route::get('/student/advice', [AdviceController::class, 'getByStudent']);

        Route::get('/student/wallet_history', [WalletHistoryController::class, 'getByStudent']);

        Route::get('/student/discipline', [StudentDisciplineController::class, 'getByStudent']);
        Route::get('/student/discipline/{id}', [StudentDisciplineController::class, 'getByIdByStudent']);

        Route::get('/student/attendance_student', [AttendanceStudentController::class, 'getByStudent']);
        Route::get('/student/attendance_student/{id}', [AttendanceStudentController::class, 'getByIdByStudent']);
        Route::get('/student/attendance_group_by_date', [AttendanceController::class, 'getGroupByDateByStudent']);

        Route::get('/student/class_score_student', [ClassScoreStudentController::class, 'getByStudent']);
        Route::get('/student/class_score/group_by_date', [ClassScoreController::class, 'getGroupByDateByStudent']);
        Route::get('/student/class_score_student/{id}', [ClassScoreStudentController::class, 'getByIdByStudent']);

        Route::get('/student/class_report', [ClassReportsController::class, 'getByStudent']);
        Route::get('/student/class_report/group_by_date', [ClassReportsController::class, 'getGroupByDateByStudent']);
        Route::get('/student/class_report/custom_format', [ClassReportsController::class, 'getInCustomFormatByStudent']);
        Route::get('/student/class_report/{id}', [ClassReportsController::class, 'getByIdByStudent']);

        Route::get('/student/class_file', [ClassFileController::class, 'getByStudent']);
        Route::get('/student/class_file/{id}', [ClassFileController::class, 'getByIdByStudent']);

        Route::get('/student/financial', [StudentFinancialController::class, 'getByStudent']);

        Route::get('/student/class_message', [ClassMessageStudentController::class, 'getByStudent']);
        Route::get('/student/class_message/{id}', [ClassMessageStudentController::class, 'getByIdByStudent']);

        Route::get('/student/class', [ClassController::class, 'getByStudent']);
        Route::get('/student/class/{id}', [ClassController::class, 'getByIdByStudent']);

        Route::get('/student/class_course', [ClassCourseController::class, 'getByStudent']);
        Route::get('/student/class_course/{id}', [ClassCourseController::class, 'getByIdByStudent']);

        Route::get('/student/message', [MessageReceiverPivotController::class, 'getByStudent']);
        Route::get('/student/message/{id}', [MessageReceiverPivotController::class, 'getByIdByStudent']);

        Route::get('/student/stat', [StatController::class, 'getByStudent']);
    });
});

Route::group([
    'middleware' => ['auth:teacher']
], function (){
    Route::get('/teacher/entrance', [TeacherEntranceController::class, 'getByTeacher']);
    Route::get('/teacher/entrance/{id}', [TeacherEntranceController::class, 'getByIdByTeacher']);

    Route::post('/teacher/survey_answer', [SurveyAnswerController::class, 'store']);

    Route::get('/teacher/survey_category', [SurveyCategoryController::class, 'getByTeacher']);
    Route::get('/teacher/survey_category/{id}', [SurveyCategoryController::class, 'getByIdByTeacher']);

    Route::post('/teacher/class_score', [ClassScoreController::class, 'storeByTeacher']);
    Route::put('/teacher/class_score/{id}', [ClassScoreController::class, 'updateByIdByTeacher']);
    Route::delete('/teacher/class_score/{id}', [ClassScoreController::class, 'deleteByIdByTeacher']);
    Route::get('/teacher/class_score', [ClassScoreController::class, 'getByTeacher']);
    Route::get('/teacher/class_score/group_by_date', [ClassScoreController::class, 'getGroupByDateByTeacher']);
    Route::get('/teacher/class_score/{id}', [ClassScoreController::class, 'getByIdByTeacher']);

    Route::post('/teacher/class_file', [ClassFileController::class, 'storeByTeacher']);
    Route::get('/teacher/class_file', [ClassFileController::class, 'getByTeacher']);
    Route::get('/teacher/class_file/{id}', [ClassFileController::class, 'getByIdByTeacher']);
    Route::put('/teacher/class_file/{id}', [ClassFileController::class, 'updateByIdByTeacher']);
    Route::delete('/teacher/class_file/{id}', [ClassFileController::class, 'deleteByIdByTeacher']);

    Route::get('/teacher/attendance', [AttendanceController::class, 'getByTeacher']);
    Route::get('/teacher/attendance/{id}', [AttendanceController::class, 'getByIdByTeacher']);
    Route::get('/teacher/attendance_group_by_date', [AttendanceController::class, 'getGroupByDateByTeacher']);

    Route::get('/teacher/class_course', [ClassCourseController::class, 'getByTeacher']);

    Route::get('/teacher/info', [TeacherController::class, 'getInfo']);
    Route::post('/teacher/change_password', [TeacherController::class, 'changePassword']);

    Route::get('/teacher/teacher_financial', [TeacherFinancialController::class, 'getByTeacher']);

    Route::get('/teacher/class_report', [ClassReportsController::class, 'getByTeacher']);
    Route::get('/teacher/class_report/group_by_date', [ClassReportsController::class, 'getGroupByDateByTeacher']);
    Route::get('/teacher/class_report/custom_format', [ClassReportsController::class, 'getByTeacher']);
    Route::get('/teacher/class_report/{id}', [ClassReportsController::class, 'getByIdByTeacher']);

    Route::get('/teacher/class', [ClassController::class, 'getByTeacher']);
    Route::get('/teacher/class/{id}', [ClassController::class, 'getByIdByTeacher']);

    Route::get('/teacher/message', [MessageReceiverPivotController::class, 'getByTeacher']);
    Route::get('/teacher/message/{id}', [MessageReceiverPivotController::class, 'getByIdByTeacher']);

    Route::get('/teacher/stat', [StatController::class, 'getByTeacher']);
});

// Route::get('/admin2/create', function(){
//    return \App\Models\AdminModel::query()->create([
//        'user_name' => 'admin',
//        'password' => \Illuminate\Support\Facades\Hash::make('admin'),
//        'is_primary' => true,
//        'privileges' => []
//    ]);
// });
