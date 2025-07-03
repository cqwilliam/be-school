<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\RoleCheck;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    use RoleCheck;
    public function index(Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante', 'Apoderado'])) {
            return $response;
        }

        $attendances = Attendance::all();
        return response()->json([
            'success' => true,
            'data' => $attendances
        ]);
    }

    public function store(Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente'])) {
            return $response;
        }

        $validator = Validator::make($request->all(), [
            'teacher_user_id' => 'required|exists:users,id',
            'student_user_id' => 'required|exists:users,id',
            'class_session_id' => 'required|exists:class_sessions,id',
            'status' => 'required|in:present,absent,late,justified',
            'justification' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $attendances = Attendance::create([
            'teacher_user_id' => $request->teacher_user_id,
            'student_user_id' => $request->student_user_id,
            'class_session_id' => $request->class_session_id,
            'status' => $request->status,
            'justification' => $request->justification,
        ]);

        return response()->json([
            'success' => true,
            'data' => $attendances
        ], 201);
    }

    public function show($id, Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante', 'Apoderado'])) {
            return $response;
        }

        $attendances = Attendance::find($id);

        if (!$attendances) {
            return response()->json([
                'success' => false,
                'message' => 'attendances not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $attendances
        ]);
    }

    public function update(Request $request, $id)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }

        $attendance = Attendance::find($id);

        if (!$attendance) {
            return response()->json([
                'success' => false,
                'message' => 'Attendance not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'teacher_user_id' => 'exists:users,id',
            'class_session_id' => 'exists:class_sessions,id',
            'student_user_id' => 'exists:users,id',
            'status' => 'in:present,absent,late,justified',
            'justification' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $attendance->update($request->only([
            'teacher_user_id',
            'student_user_id',
            'class_session_id',
            'status',
            'justification',
        ]));

        return response()->json([
            'success' => true,
            'data' => $attendance
        ]);
    }

    public function destroy($id, Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }

        $attendance = Attendance::find($id);

        if (!$attendance) {
            return response()->json([
                'success' => false,
                'message' => 'Attendance not found'
            ], 404);
        }

        $attendance->delete();

        return response()->json([
            'success' => true,
            'message' => 'Attendance deleted successfully'
        ]);
    }
}
