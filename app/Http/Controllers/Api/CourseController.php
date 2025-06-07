<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;


class CourseController extends Controller
{
    /**
     * List all courses with their academic period.
     */
    public function index()
    {
        $courses = Course::all();
        return response()->json([
            'success' => true,
            'data' => $courses
        ]);
    }

    /**
     * Create a new course.
     * 
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:20|unique:courses,code',
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'credits' => 'required|integer|min:0',
            'academic_period_id' => 'required|exists:academic_periods,id',
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
            'credits' => $request->credits,
            'academic_period_id' => $request->academic_period_id,
        ]);

        return response()->json([
            'success' => true,
            'data' => $course
        ], 201);
    }

    /**
     * Show a specific course with its relations.
     */
    public function show($id)
    {
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

    /**
     * Update a specific course.
     */
    public function update(Request $request, $id)
    {
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
            'credits' => 'integer|min:0',
            'academic_period_id' => 'exists:academic_periods,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $course->update($request->only([
            'code',
            'name',
            'description',
            'credits',
            'academic_period_id',
        ]));

        return response()->json([
            'success' => true,
            'data' => $course
        ]);
    }

    /**
     * Delete a course.
     */
    public function destroy($id)
    {
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
}
