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
    /**
     * Mostrar todas las relaciones estudiante-apoderado.
     */
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

    /**
     * Crear una nueva relación estudiante-apoderado.
     */
    public function store(Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }

        $validator = Validator::make($request->all(), [
            'student_id' => 'required|exists:students,id',
            'guardian_id' => 'required|exists:guardians,id',
            'relationship' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $studentGuardian = StudentGuardian::create([
            'student_id' => $request->student_id,
            'guardian_id' => $request->guardian_id,
            'relationship' => $request->relationship,
        ]);

        return response()->json([
            'success' => true,
            'data' => $studentGuardian
        ], 201);
    }

    /**
     * Mostrar una relación específica.
     */
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

    /**
     * Actualizar una relación específica.
     */
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
            'student_id' => 'required|exists:students,id',
            'guardian_id' => 'required|exists:guardians,id',
            'relationship' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        
        $student_guardian->update([
            'student_id' => $request->student_id,
            'guardian_id' => $request->guardian_id,
            'relationship' => $request->relationship,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Student Guardian updated successfully',
            'data' => $student_guardian
        ], 200);
    }

    /**
     * Eliminar una relación.
     */
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
