<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\RoleCheck;
use App\Models\ClassSession;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ClassSessionController extends Controller
{
    use RoleCheck;

    public function index(Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante'])) {
            return $response;
        }

        $classSessions = ClassSession::all();

        return response()->json([
            'success' => true,
            'data' => $classSessions
        ]);
    }

    public function store(Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }

        $validator = Validator::make($request->all(), [
            'teacher_id' => 'required|exists:users,id',
            'section_period_id' => 'required|exists:course_sections,id',
            'topic' => 'nullable|string',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $classSession = ClassSession::create([
            'teacher_id' => $request->teacher_id,
            'section_period_id' => $request->section_period_id,
            'topic' => $request->topic,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return response()->json([
            'success' => true,
            'data' => $classSession
        ], 201);
    }

    public function show($id, Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante', 'Apoderado'])) {
            return $response;
        }

        $classSession = ClassSession::find($id);

        if (!$classSession) {
            return response()->json([
                'success' => false,
                'message' => 'Class session not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $classSession
        ]);
    }

    public function update(Request $request, $id)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }

        $classSession = ClassSession::find($id);

        if (!$classSession) {
            return response()->json([
                'success' => false,
                'message' => 'Class session not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'teacher_id' => 'required|exists:users,id',
            'section_period_id' => 'required|exists:course_sections,id',
            'topic' => 'nullable|string',
            'date' => 'required|date',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format',
            
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $classSession->update([
            'teacher_id' => $request->teacher_id,
            'section_period_id' => $request->section_period_id,
            'topic' => $request->topic,
            'date' => $request->date,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

        return response()->json([
            'success' => true,
            'data' => $classSession
        ]);
    }

    public function destroy($id, Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }

        $classSession = ClassSession::find($id);

        if (!$classSession) {
            return response()->json([
                'success' => false,
                'message' => 'classSession not found'
            ], 404);
        }

        $classSession->delete();

        return response()->json([
            'success' => true,
            'message' => 'classSession deleted successfully'
        ]);
    }
}
