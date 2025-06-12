<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\TaskController;
use App\Http\Controllers\Api\TeacherController;
use App\Http\Controllers\Api\AcademicPeriodController;
use App\Http\Controllers\Api\TeacherSectionController;
use App\Http\Controllers\Api\GradeController;
use App\Http\Controllers\Api\ClassSessionController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\CourseMaterialController;
use App\Http\Controllers\Api\AnnouncementController;
use App\Http\Controllers\Api\AssignmentController;
use App\Http\Controllers\Api\CourseController;
use App\Http\Controllers\Api\CourseSectionController;
use App\Http\Controllers\Api\EnrollmentController;
use App\Http\Controllers\Api\EvaluationController;
use App\Http\Controllers\Api\EvaluationTypeController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\GuardianController;
use App\Http\Controllers\Api\StudentGuardianController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\AssignmentSubmissionController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MessageController;
use App\Http\Middleware\JWTMiddleware;
use Illuminate\Http\Request;

Route::post('/sign-in', [AuthController::class, 'sign_in'])->name('sign_in');

Route::middleware(JWTMiddleware::class)->group(function () {
    Route::get('/current-user', function (Request $request) {
        return response()->json($request->auth_user);
    });
    
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::apiResource('roles', RoleController::class);
    Route::apiResource('users', UserController::class);
    Route::apiResource('students', StudentController::class);
    Route::apiResource('teachers', TeacherController::class);
    Route::apiResource('guardians', GuardianController::class);
    Route::apiResource('student-guardians', StudentGuardianController::class);
    Route::apiResource('academic-periods', AcademicPeriodController::class);
    Route::apiResource('courses', CourseController::class);
    Route::apiResource('course-sections', CourseSectionController::class);
    Route::apiResource('teacher-sections', TeacherSectionController::class);
    Route::apiResource('enrollments', EnrollmentController::class);
    Route::apiResource('schedules', ScheduleController::class);
    Route::apiResource('evaluation-types', EvaluationTypeController::class);
    Route::apiResource('evaluations', EvaluationController::class);
    Route::apiResource('grades', GradeController::class);
    Route::apiResource('class-sessions', ClassSessionController::class);
    Route::apiResource('attendances', AttendanceController::class);
    Route::apiResource('assignments', AssignmentController::class);
    Route::apiResource('assignment_submissions', AssignmentSubmissionController::class);
    Route::apiResource('course-materials', CourseMaterialController::class);
    Route::apiResource('announcements', AnnouncementController::class);
    Route::apiResource('messages', MessageController::class);
    Route::apiResource('tasks', TaskController::class);
});
