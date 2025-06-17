<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\RoleCheck;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TeacherController extends Controller
{
    use RoleCheck;
    // List all teachers
    public function index(Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante', 'Apoderado'])) {
            return $response;
        }

        $teachers = Teacher::all();

        return response()->json([
            'success' => true,
            'data' => $teachers
        ], 200);
    }

    public function store(Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id|unique:teachers,user_id',
            'specialty' => 'nullable|string|max:100',
            'academic_degree' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::find($request->user_id);
        if ($user->role_id !== 3) { // 3 es el rol de Docente
            return response()->json([
                'success' => false,
                'message' => 'El usuario debe ser un docente para ser creado como tal.'
            ], 422);
        }

        $teacher = Teacher::create($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Teacher created successfully',
            'data' => $teacher
        ], 201);
    }


    // Show a specific teacher
    public function show(Request $request, $id)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante', 'Apoderado'])) {
            return $response;
        }

        $teacher = Teacher::find($id);

        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'Teacher not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $teacher
        ], 200);
    }

    // Update a teacher
    public function update(Request $request, $id)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }

        $teacher = Teacher::find($id);

        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'Teacher not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            //'user_id' => 'exists:users,id|unique:teachers,user_id,' . $teacher->id,
            'specialty' => 'sometimes|string|max:100',
            'academic_degree' => 'sometimes|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $teacher->update($validator->validated());

        return response()->json([
            'success' => true,
            'message' => 'Teacher updated successfully',
            'data' => $teacher
        ], 200);
    }

    // Delete a teacher
    public function destroy(Request $request, $id)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }
        $teacher = Teacher::find($id);

        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'Teacher not found'
            ], 404);
        }

        $teacher->delete();

        return response()->json([
            'success' => true,
            'message' => 'Teacher deleted successfully'
        ], 200);
    }

    public function getByUserId(Request $request, $userId)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante', 'Apoderado'])) {
            return $response;
        }

        $teacher = Teacher::where('user_id', $userId)->first();

        if (!$teacher) {
            return response()->json([
                'success' => false,
                'message' => 'Teacher not found for the given user ID'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $teacher
        ], 200);
    }
}
