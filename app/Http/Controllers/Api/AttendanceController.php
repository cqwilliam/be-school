<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    // Listar asistencias
    public function index()
    {
        $attendances = Attendance::all();
        return response()->json([
            'success' => true,
            'data' => $attendances
        ]);
    }

    // Crear asistencia
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'class_session_id' => 'required|exists:class_sessions,id',
            'student_id' => 'required|exists:students,id',
            'status' => 'required|in:present,absent,late,justified',
            'recorded_time' => 'nullable|date_format:H:i:s',
            'justification' => 'nullable|string',
            'recorded_by' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $attendances = Attendance::create([
            'class_session_id' => $request->class_session_id,
            'student_id' => $request->student_id,
            'status' => $request->status,
            'recorded_time' => $request->recorded_time,
            'justification' => $request->justification,
            'recorded_by' => $request->recorded_by,
        ]);

        return response()->json([
            'success' => true,
            'data' => $attendances
        ], 201);
    }

    // Mostrar una asistencia
    public function show($id)
    {
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

    // Actualizar asistencia
    public function update(Request $request, $id)
    {
        $attendance = Attendance::find($id);

        if (!$attendance) {
            return response()->json([
                'success' => false,
                'message' => 'Attendance not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'class_session_id' => 'exists:class_sessions,id',
            'student_id' => 'exists:students,id',
            'status' => 'in:present,absent,late,justified',
            'recorded_time' => 'nullable|date_format:H:i:s',
            'justification' => 'nullable|string',
            'recorded_by' => 'exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $attendance->update($request->only([
            'class_session_id',
            'student_id',
            'status',
            'recorded_time',
            'justification',
            'recorded_by'
        ]));

        return response()->json([
            'success' => true,
            'data' => $attendance
        ]);
    }

    // Eliminar asistencia
    public function destroy($id)
    {
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
