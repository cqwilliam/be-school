<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\RoleCheck;
use App\Models\SectionCourse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SectionCourseController extends Controller
{
    use  RoleCheck;

    public function index(Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante'])) {
            return $response;
        }

        $sectionCourse = SectionCourse::all();
        return response()->json([
            'success' => true,
            'data' => $sectionCourse
        ]);
    }

    public function store(Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }

        $validator = Validator::make($request->all(), [
            'course_id' => 'required|exists:courses,id',
            'section_id' => 'required|exists:sections,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $courseSection = SectionCourse::create([
            'course_id' => $request->course_id,
            'section_id' => $request->section_id,
        ]);

        return response()->json([
            'success' => true,
            'data' => $courseSection
        ], 201);
    }

    public function show($section_id, Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante'])) {
            return $response;
        }

        $section = SectionCourse::find($section_id);

        if (!$section) {
            return response()->json([
                'success' => false,
                'message' => 'Sección no encontrada'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $section
        ]);
    }

    public function update(Request $request, $id)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }

        $courseSection = SectionCourse::find($id);

        if (!$courseSection) {
            return response()->json(['message' => 'Course Section not found'], 404);
        }

        $validator = Validator::make($request->all(), [
            'course_id' => 'exists:courses,id',
            'section_id' => 'exists:sections,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $courseSection->update($request->only([
            'course_id',
            'section_id',
        ]));

        return response()->json([
            'success' => true,
            'data' => $courseSection
        ]);
    }

    public function destroy($id, Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }

        $courseSection = SectionCourse::find($id);

        if (!$courseSection) {
            return response()->json([
                'success' => false,
                'message' => 'Couse Section not found'
            ], 404);
        }

        $courseSection->delete();

        return response()->json([
            'success' => true,
            'message' => 'Section Course deleted successfully'
        ]);
    }
}
