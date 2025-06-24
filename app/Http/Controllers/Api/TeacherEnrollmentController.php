<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\RoleCheck;
use App\Models\TeacherEnrollment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeacherEnrollmentController extends Controller
{
    use RoleCheck;

    public function index(Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente'])) {
            return $response;
        }

        $teacherEnrollment = TeacherEnrollment::all();
        return response()->json([
            'success' => true,
            'data' => $teacherEnrollment
        ]);
    }

    public function store(Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }

        $validator = Validator::make($request->all(), [
            'teacher_id' => 'required|exists:teachers,id',
            'section_period_id' => 'required|exists:sections_periods,id',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        $teacherEnrollment = TeacherEnrollment::create([
            'teacher_id' => $request->teacher_id,
            'section_period_id' => $request->section_period_id,
        ]);

        return response()->json([
            'success' => true,
            'data' => $teacherEnrollment
        ], 201);
    }

    public function show($id, Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante', 'Apoderado'])) {
            return $response;
        }

        $teacherEnrollment = TeacherEnrollment::find($id);

        if (!$teacherEnrollment) {
            return response()->json([
                'success' => false,
                'message' => 'teacher section not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $teacherEnrollment
        ]);
    }

    public function update(Request $request, $id)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }

        $teacherEnrollment = TeacherEnrollment::find($id);

        if (!$teacherEnrollment) {
            return response()->json([
                'success' => false,
                'message' => 'teacherEnrollment not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'teacher_id' => 'exists:teachers,id',
            'section_period_id' => 'exists:sections_periods,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $teacherEnrollment->update($request->only([
            'teacher_id',
            'section_period_id',
        ]));

        return response()->json([
            'success' => true,
            'data' => $teacherEnrollment
        ]);
    }

    public function destroy($id, Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }

        $teacherEnrollment = TeacherEnrollment::find($id);

        if (!$teacherEnrollment) {
            return response()->json([
                'success' => false,
                'message' => 'teacherEnrollment not found'
            ], 404);
        }

        $teacherEnrollment->delete();

        return response()->json([
            'success' => true,
            'message' => 'teacherEnrollment deleted successfully'
        ]);
    }
}
