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
use App\Http\Controllers\Api\MessageController;

// Public authentication routes
// Route::post('/login', [AuthController::class, 'login'])->name('login');
// Route::post('/register', [AuthController::class, 'register']);

// Protected routes
// Route::middleware('auth:sanctum')->group(function () {
// Route::get('/user', function (Request $request) {
// return $request->user();
// });

// Ruta para obtener todos los usuarios
// Route::get('/users', function (Request $request) {
//     $users = User::select('id', 'first_name', 'last_name', 'email', 'created_at')->get();
//     return response()->json($users);
// });

//Route::post('/logout', [AuthController::class, 'logout']);


// Your existing protected routes
// Route::apiResource('messages', MessageController::class);
//Route::patch('messages/{id}/mark-as-read', [MessageController::class, 'markAsRead']);
// });

// Your existing routes (you may want to protect some of these too)
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
Route::apiResource('tasks', TaskController::class);
Route::apiResource('announcements', AnnouncementController::class);
Route::apiResource('messages', MessageController::class);
