<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\RoleCheck;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\TeacherSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;


class CourseController extends Controller
{
    use RoleCheck;

    public function index(Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante', 'Apoderado'])) {
            return $response;
        }

        $courses = Course::all();
        return response()->json([
            'success' => true,
            'data' => $courses
        ]);
    }

    public function store(Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }

        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:20|unique:courses,code',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $course = Course::create([
            'code' => $request->code,
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'data' => $course
        ], 201);
    }

    public function show($id, Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante', 'Apoderado'])) {
            return $response;
        }

        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $course
        ]);
    }

    public function update(Request $request, $id)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }

        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'code' => 'string|max:20|unique:courses,code',
            'name' => 'string|max:100',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $course->update([
            'code' => $request->code,
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Course updated successfully',
            'data' => $course
        ]);
    }

    /**
     * Delete a course.
     */
    public function destroy($id, Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }

        $course = Course::find($id);

        if (!$course) {
            return response()->json([
                'success' => false,
                'message' => 'Course not found'
            ], 404);
        }

        $course->delete();

        return response()->json([
            'success' => true,
            'message' => 'Course deleted successfully'
        ]);
    }
    // public function getStudentCourses($student_id, Request $request)
    // {
    //     if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante', 'Apoderado'])) {
    //         return $response;
    //     }

    //     $courses = Enrollment::with([
    //         'courseSection.course',
    //         'courseSection.teachers.teacher.user',
    //         'academicPeriod'
    //     ])
    //         ->where('student_id', $student_id)
    //         ->whereHas('academicPeriod', function ($query) {
    //             $query->where('active', true);
    //         })
    //         ->get();

    //     return response()->json([
    //         'success' => true,
    //         'data' => $courses
    //     ]);
    // }

    // public function getTeacherCourses($teacher_id, Request $request)
    // {
    //     if ($response = $this->checkRole($request, ['Administrador', 'Docente'])) {
    //         return $response;
    //     }

    //     $courses = TeacherSection::with([
    //         'courseSection.course',
    //         'courseSection.course.academicPeriod'
    //     ])
    //         ->where('teacher_id', $teacher_id)
    //         ->whereHas('courseSection.course.academicPeriod', function ($query) {
    //             $query->where('active', true);
    //         })
    //         ->get();

    //     return response()->json([
    //         'success' => true,
    //         'data' => $courses
    //     ]);
    // }
}
