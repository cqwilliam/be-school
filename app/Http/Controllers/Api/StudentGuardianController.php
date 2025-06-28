<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\RoleCheck;
use App\Models\StudentGuardian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentGuardianController extends Controller
{
    use RoleCheck;

    public function index(Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante', 'Apoderado'])) {
            return $response;
        }

        $relations = StudentGuardian::all();
        return response()->json([
            'success' => true,
            'data' => $relations
        ]);
    }

    public function store(Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }

        $validator = Validator::make($request->all(), [
            'student_user_id' => 'required|exists:users,id',
            'guardian_user_id' => 'required|exists:users,id',
            'relationship' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $studentGuardian = StudentGuardian::create([
            'student_user_id' => $request->student_user_id,
            'guardian_user_id' => $request->guardian_user_id,
            'relationship' => $request->relationship,
        ]);

        return response()->json([
            'success' => true,
            'data' => $studentGuardian
        ], 201);
    }

    public function show($id, Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante', 'Apoderado'])) {
            return $response;
        }

        $student_guardian = StudentGuardian::find($id);

        if (!$student_guardian) {
            return response()->json([
                'success' => false,
                'message' => 'Student Guardian not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $student_guardian
        ]);
    }

    public function update(Request $request, $id)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }

        $student_guardian = StudentGuardian::find($id);

        if (!$student_guardian) {
            return response()->json([
                'success' => false,
                'message' => 'Student Guardian not found'
            ], 404);
        }
        $validator = Validator::make($request->all(), [
            'student_user_id' => 'required|exists:users,id',
            'guardian_user_id' => 'required|exists:users,id',
            'relationship' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $student_guardian->update([
            'student_user_id' => $request->student_user_id,
            'guardian_user_id' => $request->guardian_user_id,
            'relationship' => $request->relationship,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Student Guardian updated successfully',
            'data' => $student_guardian
        ], 200);
    }

    public function destroy($id, Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }

        $student_guardian = StudentGuardian::find($id);

        if (!$student_guardian) {
            return response()->json([
                'success' => false,
                'message' => 'Student Guardian not found'
            ], 404);
        }

        $student_guardian->delete();

        return response()->json([
            'success' => true,
            'message' => 'Student Guardian deleted successfully'
        ]);
    }
}
