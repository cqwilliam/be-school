<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\RoleCheck;
use App\Models\StudentEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentEnrollmentController extends Controller
{
    use RoleCheck;
    
    public function index(Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante', 'Apoderado'])) {
            return $response;
        }

        $studentenrollments = StudentEnrollment::all();
        return response()->json([
            'success' => true,
            'data' => $studentenrollments
        ]);
    }

    public function store(Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }

        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'section_period_id' => 'required|exists:sections_periods,id',
            'status' => 'required|string|max:100',
        ]);

        $validator->after(function ($validator) use ($request) {
            if (StudentEnrollment::where('student_id', $request->student_id)
                ->where('section_period_id', $request->section_period_id)
                ->exists()) {
                $validator->errors()->add('enrollment', 'El estudiante ya está matriculado en esta sección');
            }
        });

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $studentEnrollment = StudentEnrollment::create([
            'student_id' => $request->student_id,
            'section_period_id' => $request->section_period_id,
            'status' => $request->status,
        ]);

        return response()->json([
            'success' => true,
            'data' => $studentEnrollment
        ], 201);
    }

    public function show($id, Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante', 'Apoderado'])) {
            return $response;
        }

        $studentEnrollment = StudentEnrollment::find($id);

        if (!$studentEnrollment) {
            return response()->json([
                'success' => false,
                'message' => 'studentEnrollment not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $studentEnrollment
        ]);
    }

    public function update(Request $request, $id)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }

        $studentEnrollment = StudentEnrollment::find($id);

        if (!$studentEnrollment) {
            return response()->json([
                'success' => false,
                'message' => 'studentEnrollment not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'student_id' => 'exists:students,id',
            'section_period_id' => 'exists:sections_periods,id',
            'status' => 'string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $studentEnrollment->update($request->only([
            'student_id',
            'section_period_id',
            'status'
        ]));

        return response()->json([
            'success' => true,
            'data' => $studentEnrollment
        ]);
    }

    public function destroy($id, Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }

        $studentEnrollment = StudentEnrollment::find($id);

        if (!$studentEnrollment) {
            return response()->json([
                'success' => false,
                'message' => 'studentEnrollment not found'
            ], 404);
        }

        $studentEnrollment->delete();

        return response()->json([
            'success' => true,
            'message' => 'studentEnrollment deleted successfully'
        ]);
    }
}
