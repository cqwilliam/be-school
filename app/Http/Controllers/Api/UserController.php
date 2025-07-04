<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\RoleCheck;
use App\Models\ClassSession;
use App\Models\Course;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    use RoleCheck;

    public function index(Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante'])) {
            return $response;
        }

        $users = User::all();

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    public function store(Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }

        $validated = $request->validate([
            'first_name' => 'required|string|max:100',
            'last_name' => 'required|string|max:100',
            'user_name' => 'nullable|string|max:50',
            'email' => 'required|string|email|max:100|unique:users',
            'password' => 'required|string|min:8',
            'dni' => 'required|string|max:20|unique:users',
            'birth_date' => 'nullable|date',
            'photo_url' => 'nullable|url|max:2048',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'role_id' => 'required|exists:roles,id',
        ]);

        $validated['password'] = Hash::make($request->password);

        $user = User::create([
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'user_name' => $request->user_name,
            'email' => $request->email,
            'password' => $validated['password'],
            'dni' => $request->dni,
            'birth_date' => $request->birth_date,
            'photo_url' => $request->photo_url,
            'phone' => $request->phone,
            'address' => $request->address,
            'role_id' => $request->role_id,
        ]);

        return response()->json([
            'success' => true,
            'data' => $user
        ], 201);
    }

    public function show(Request $request, $id)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante', 'Apoderado'])) {
            return $response;
        }

        $users = User::find($id);

        if (!$users) {
            return response()->json([
                'success' => false,
                'message' => 'users not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $users
        ]);
    }

    public function update(Request $request, $id)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante', 'Apoderado'])) {
            return $response;
        }

        $user = User::findOrFail($id);

        $validated = $request->validate([
            'first_name' => 'nullable|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'user_name' => 'nullable|string|max:50',
            'email' => 'nullable|string|email|max:100|unique:users,email,' . $user->id,
            'password' => 'nullable|min:8|confirmed',
            'dni' => 'nullable|string|max:20|unique:users,dni,' . $user->id,
            'birth_date' => 'nullable|date',
            'photo_url' => 'nullable|url|max:2048',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'role_id' => 'nullable|exists:roles,id',
        ]);

        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'La contraseña actual no es correcta.'
                ], 422);
            }
            $user->password = Hash::make($request->password);
        }

        $user->first_name = $request->first_name ?? $user->first_name;
        $user->last_name = $request->last_name ?? $user->last_name;
        $user->user_name = $request->user_name ?? $user->user_name;
        $user->email = $request->email ?? $user->email;
        $user->dni = $request->dni ?? $user->dni;
        $user->birth_date = $request->birth_date ?? $user->birth_date;
        $user->photo_url = $request->photo_url ?? $user->photo_url;
        $user->phone = $request->phone ?? $user->phone;
        $user->address = $request->address ?? $user->address;
        $user->role_id = $request->role_id ?? $user->role_id;

        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Usuario actualizado correctamente.',
            'user' => $user
        ]);
    }


    public function destroy(Request $request, $id)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }

        $user = User::find($id);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ]);
    }

    public function getStudentCourses(Request $request, $student_user_id)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Estudiante'])) {
            return $response;
        }

        $user = User::find($student_user_id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'user not found'
            ], 404);
        }

        $courses = $user->periodSectionUsers()
            ->with(['periodSection.section.sectionCourses.course'])
            ->get()
            ->flatMap(function ($periodSectionUser) {
                return $periodSectionUser->periodSection->section->sectionCourses
                    ->pluck('course');
            })
            ->unique('id')
            ->values();

        return response()->json([
            'success' => true,
            'data' => $courses
        ]);
    }

    public function getTeacherCourses(Request $request, $teacher_user_id)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente'])) {
            return $response;
        }

        $teacher = User::find($teacher_user_id);

        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'Profesor no encontrado'
            ], 404);
        }

        $courses = Schedule::where('teacher_user_id', $teacher->id)
            ->with(['course', 'periodSection.section'])
            ->get()
            ->pluck('course')
            ->unique('id')
            ->values();

        return response()->json([
            'success' => true,
            'data' => $courses
        ]);
    }
    public function getStudentSections(Request $request, $student_user_id)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Estudiante'])) {
            return $response;
        }

        $user = User::find($student_user_id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        $sections = $user->periodSectionUsers()
            ->with(['periodSection.section'])
            ->get()
            ->pluck('periodSection.section')
            ->unique('id')
            ->values();

        return response()->json([
            'success' => true,
            'data' => $sections
        ]);
    }

    public function getStudentCourseMaterials(Request $request, $student_user_id)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Estudiante'])) {
            return $response;
        }

        $user = User::find($student_user_id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        $materials = $user->periodSectionUsers()
            ->with(['periodSection.section.sectionCourses.course.courseMaterials'])
            ->get()
            ->flatMap(function ($periodSectionUser) {
                return $periodSectionUser->periodSection->section->sectionCourses
                    ->flatMap(function ($sectionCourse) {
                        return $sectionCourse->course->courseMaterials;
                    });
            })
            ->unique('id')
            ->values();

        return response()->json([
            'success' => true,
            'data' => $materials
        ]);
    }

    public function getCourseTeachers(Request $request, $user_id)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante'])) {
            return $response;
        }

        $user = User::find($user_id);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ], 404);
        }

        if ($user->role_name === 'Estudiante') {
            $courses = $user->periodSectionUsers()
                ->with(['periodSection.section.sectionCourses.course.schedules.teacher'])
                ->get()
                ->flatMap(function ($periodSectionUser) {
                    return $periodSectionUser->periodSection->section->sectionCourses
                        ->pluck('course');
                })
                ->unique('id');
        } else { 
            $courses = Course::whereHas('schedules', function($query) use ($user) {
                $query->where('teacher_user_id', $user->id);
            })->get();
        }

        $coursesWithTeachers = $courses->map(function ($course) {
            return [
                'course_id' => $course->id,
                'course_name' => $course->name,
                'teachers' => $course->schedules
                    ->pluck('teacher')
                    ->unique('id')
                    ->values()
                    ->map(function ($teacher) {
                        return [
                            'teacher_id' => $teacher->id,
                            'teacher_name' => $teacher->full_name,
                            'teacher_email' => $teacher->email
                        ];
                    })
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $coursesWithTeachers
        ]);
    }

    public function getStudentAttendances(Request $request, $student_user_id)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Estudiante'])) {
            return $response;
        }
        $user = User::find($student_user_id);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Usuario no encontrado'
            ], 404);
        }
        $attendances = ClassSession::whereHas('attendances', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })
            ->with(['attendances', 'periodSection.section'])
            ->get();
            return response()->json([
            'success' => true,
            'data' => $attendances
        ]);
        
    }

    public function getTeacherAttendances(Request $request, $teacher_user_id)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente'])) {
            return $response;
        }

        $teacher = User::find($teacher_user_id);
        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'Docente no encontrado'
            ], 404);
        }

        $attendances = ClassSession::where('teacher_user_id', $teacher_user_id)
            ->with(['attendances', 'periodSection.section'])
            ->get()
            ->flatMap(function ($classSession) {
                return $classSession->attendances->map(function ($attendance) use ($classSession) {
                    return [
                        'attendance_id' => $attendance->id,
                        'student_id' => $attendance->student_user_id,
                        'student_name' => $attendance->student->full_name,
                        'status' => $attendance->status,
                        'justification' => $attendance->justification,
                        'date' => $classSession->date,
                        'start_time' => $classSession->start_time,
                        'end_time' => $classSession->end_time,
                        'topic' => $classSession->topic,
                        'section_name' => $classSession->periodSection->section->name,
                        'period_section_id' => $classSession->period_section_id
                    ];
                });
            });

        return response()->json([
            'success' => true,
            'data' => $attendances
        ]);
    }
}
