<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EnrollmentController extends Controller
{
    public function index()
    {
        $enrollments = Enrollment::all();
        return response()->json([
            'success' => true,
            'data' => $enrollments
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'section_id' => 'required|exists:course_sections,id',
            'academic_period_id' => 'required|exists:academic_periods,id',
            'enrolled_at' => 'nullable|date',
            'status' => 'required|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $enrollment = Enrollment::create([
            'student_id' => $request->student_id,
            'section_id' => $request->section_id,
            'academic_period_id' => $request->academic_period_id,
            'enrolled_at' => $request->enrolled_at,
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'data' => $enrollment
        ], 201);
    }

    public function show($id)
    {
        $enrollment = Enrollment::find($id);

        if (!$enrollment) {
            return response()->json([
                'success' => false,
                'message' => 'enrollment not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $enrollment
        ]);
    }

    // Actualizar inscripción
    public function update(Request $request, $id)
    {
        $enrollment = Enrollment::find($id);

        if (!$enrollment) {
            return response()->json([
                'success' => false,
                'message' => 'enrollment not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'student_id' => 'exists:students,id',
            'section_id' => 'exists:course_sections,id',
            'academic_period_id' => 'exists:academic_periods,id',
            'enrolled_at' => 'date',
            'status' => 'string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $enrollment->update($request->only([
            'student_id',
            'section_id',
            'academic_period_id',
            'enrolled_at',
            'status'
        ]));

        return response()->json([
            'success' => true,
            'data' => $enrollment
        ]);
    }

    // Eliminar inscripción
    public function destroy($id)
    {
        $enrollment = Enrollment::find($id);
        
        if (!$enrollment) {
            return response()->json([
                'success' => false,
                'message' => 'enrollment not found'
            ], 404);
        }

        $enrollment->delete();

        return response()->json([
            'success' => true,
            'message' => 'enrollment deleted successfully'
        ]);
    }
}
