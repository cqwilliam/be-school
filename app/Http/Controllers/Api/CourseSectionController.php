<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CourseSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CourseSectionController extends Controller
{

    public function index()
    {
        $couseSection = CourseSection::all();
        return response()->json([
            'success' => true,
            'data' => $couseSection
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required|string|max:20',
            'course_id' => 'required|exists:courses,id',
            'classroom' => 'nullable|string|max:50',
            'max_capacity' => 'required|integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $courseSection = CourseSection::create([
            'code' => $request->code,
            'course_id' => $request->course_id,
            'classroom' => $request->classroom,
            'max_capacity' => $request->max_capacity,
        ]);

        return response()->json([
            'success' => true,
            'data' => $courseSection
        ], 201);
    }

    public function show($id)
    {
        $courseSection = CourseSection::find($id);

        if (!$courseSection) {
            return response()->json([
                'success' => false,
                'message' => 'Course Section not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $courseSection
        ]);
    }

    public function update(Request $request, $id)
    {
        $courseSection = CourseSection::find($id);

        if (!$courseSection) {
            return response()->json(['message' => 'Course Section not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'code' => 'string|max:20',
            'course_id' => 'exists:courses,id',
            'classroom' => 'nullable|string|max:50',
            'max_capacity' => 'integer|min:1',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $courseSection->update($request->only([
            'code',
            'course_id',
            'classroom',
            'max_capacity',
        ]));

        return response()->json([
            'success' => true,
            'data' => $courseSection
        ]);
    }

    // Eliminar un course section con manejo de excepción
    public function destroy($id)
    {
        $couseSection = CourseSection::find($id);
        
        if (!$couseSection) {
            return response()->json([
                'success' => false,
                'message' => 'Couse Section not found'
            ], 404);
        }

        $couseSection->delete();

        return response()->json([
            'success' => true,
            'message' => 'Couse Section deleted successfully'
        ]);
    }
}
