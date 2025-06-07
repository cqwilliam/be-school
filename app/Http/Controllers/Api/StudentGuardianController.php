<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\StudentGuardian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentGuardianController extends Controller
{
    /**
     * Mostrar todas las relaciones estudiante-apoderado.
     */
    public function index()
    {
        $relations = StudentGuardian::all();
        return response()->json($relations);
    }

    /**
     * Crear una nueva relación estudiante-apoderado.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
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
    public function show($id)
    {
        $student_guardian = StudentGuardian::find($id);
        
        if (!$student_guardian) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found'
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
        $relation = StudentGuardian::findOrFail($id);

        $validated = $request->validate([
            'relationship' => 'nullable|string|max:100',
            'is_primary' => 'boolean',
        ]);

        $relation->update($validated);

        return response()->json($relation);
    }

    /**
     * Eliminar una relación.
     */
    public function destroy($id)
    {
        $student_guardian = StudentGuardian::find($id);
        
        if (!$student_guardian) {
            return response()->json([
                'success' => false,
                'message' => 'Task not found'
            ], 404);
        }

        $student_guardian->delete();

        return response()->json([
            'success' => true,
            'message' => 'Task deleted successfully'
        ]);
    }
}
