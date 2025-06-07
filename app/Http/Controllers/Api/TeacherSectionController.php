<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TeacherSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeacherSectionController extends Controller
{
    // Listar todas las asignaciones teacher-section
    public function index()
    {
        $teacherSection = TeacherSection::all();
        return response()->json([
            'success' => true,
            'data' => $teacherSection
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'section_id' => 'required|exists:course_sections,id',
            'teacher_id' => 'required|exists:teachers,id',
            'is_primary' => 'boolean',
        ]);

       if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        $teacherSection = TeacherSection::create([
            'section_id' => $request->section_id,
            'teacher_id' => $request->teacher_id,
            'is_primary' => $request->is_primary ?? false,
        ]);

        return response()->json([
            'success' => true,
            'data' => $teacherSection
        ], 201);
    }

    public function show($id)
    {
        $teacherSection = TeacherSection::find($id);
        
        if (!$teacherSection) {
            return response()->json([
                'success' => false,
                'message' => 'teacher section not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $teacherSection
        ]);
    }

    public function update(Request $request, $id)
    {
        $teacherSection = TeacherSection::find($id);
        
        if (!$teacherSection) {
            return response()->json([
                'success' => false,
                'message' => 'teacherSection not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'section_id' => 'exists:course_sections,id',
            'teacher_id' => 'exists:teachers,id',
            'is_primary' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $teacherSection->update($request->only([
            'section_id',
            'teacher_id',
            'is_primary'
        ]));

        return response()->json([
            'success' => true,
            'data' => $teacherSection
        ]);
    }

    // Eliminar asignación
    public function destroy($id)
    {
        $teacherSection = TeacherSection::find($id);
        
        if (!$teacherSection) {
            return response()->json([
                'success' => false,
                'message' => 'teacherSection not found'
            ], 404);
        }

        $teacherSection->delete();

        return response()->json([
            'success' => true,
            'message' => 'teacherSection deleted successfully'
        ]);
    }
}
