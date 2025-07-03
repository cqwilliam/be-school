<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ClassSessionController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\CourseMaterialController;
use App\Http\Controllers\Api\AnnouncementController;
use App\Http\Controllers\Api\AssignmentController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\SectionCourseController;
use App\Http\Controllers\Api\EvaluationController;
use App\Http\Controllers\Api\EvaluationTypeController;
use App\Http\Controllers\Api\PeriodSectionController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\StudentGuardianController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\AssignmentSubmissionController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\EvaluationGradeController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Controllers\Api\PeriodController;
use App\Http\Controllers\Api\PeriodSectionUserController;
use App\Http\Controllers\Api\SectionController;
use App\Http\Middleware\JWTMiddleware;
use Illuminate\Http\Request;

Route::post('/sign-in', [AuthController::class, 'sign_in'])->name('sign_in');

Route::middleware(JWTMiddleware::class)->group(function () {

    Route::get('/current-user', function (Request $request) {
        return response()->json($request->auth_user);
    });

    Route::get('/students/{student_user_id}/courses', [UserController::class, 'getStudentCourses']);
    Route::get('/teachers/{teacher_user_id}/courses', [UserController::class, 'getTeacherCourses']);
    Route::get('/students/{student_user_id}/sections', [UserController::class, 'getStudentSections']);
    Route::get('/students/{student_user_id}/course-materials', [UserController::class, 'getStudentCourseMaterials']);
    Route::get('/students/{student_user_id}/evaluations', [UserController::class, 'getStudentTypesEvaluations']);
    Route::get('/students/{student_user_id}/evaluation-grades', [UserController::class, 'getStudentEvaluationGrades']);
    Route::get('/students/{teacher_user_id}/course-teachers', [UserController::class, 'getCourseTeachers']);

    Route::apiResource('roles', RoleController::class);
    Route::apiResource('users', UserController::class);
    Route::apiResource('students-guardians', StudentGuardianController::class);
    Route::apiResource('periods', PeriodController::class);
    Route::apiResource('courses', CourseController::class);
    Route::apiResource('sections', SectionController::class);
    Route::apiResource('sections-courses', SectionCourseController::class);
    Route::apiResource('periods-sections', PeriodSectionController::class);
    Route::apiResource('period-sections-users', PeriodSectionUserController::class);
    Route::apiResource('schedules', ScheduleController::class);
    Route::apiResource('evaluation-types', EvaluationTypeController::class);
    Route::apiResource('evaluations', EvaluationController::class);
    Route::apiResource('evaluation-grades', EvaluationGradeController::class);
    Route::apiResource('class-sessions', ClassSessionController::class);
    Route::apiResource('attendances', AttendanceController::class);
    Route::apiResource('assignments', AssignmentController::class);
    Route::apiResource('assignment-submissions', AssignmentSubmissionController::class);
    Route::apiResource('course-materials', CourseMaterialController::class);
    Route::apiResource('announcements', AnnouncementController::class);
    Route::apiResource('messages', MessageController::class);
});
