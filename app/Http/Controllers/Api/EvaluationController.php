<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\RoleCheck;
use App\Models\Evaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EvaluationController extends Controller
{
    use RoleCheck;

    public function index(Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador','Docente', 'Estudiante'])) {
            return $response;
        }

        $evaluations = Evaluation::all();
        return response()->json([
            'success' => true,
            'data' => $evaluations
        ]);
    }

    public function store(Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente'])) {
            return $response;
        }

        $validator = Validator::make($request->all(), [
            'period_section_id' => 'required|exists:periods_sections,id',
            'evaluation_type_id' => 'required|exists:evaluation_types,id',
            'teacher_user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:100',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date|after_or_equal:date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $evaluations = Evaluation::create([
            'period_section_id' => $request->period_section_id,
            'evaluation_type_id' => $request->evaluation_type_id,
            'teacher_user_id' => $request->teacher_user_id,
            'title' => $request->title,
            'description' => $request->description,
            'due_date' => $request->due_date,
        ]);

        return response()->json([
            'success' => true,
            'data' => $evaluations
        ], 201);
    }

    public function show($id, Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante', 'Apoderado'])) {
            return $response;
        }

        $evaluation = Evaluation::find($id);

        if (!$evaluation) {
            return response()->json([
                'success' => false,
                'message' => 'evaluation not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $evaluation
        ]);
    }

    // Actualizar una evaluación
    public function update(Request $request, $id)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente'])) {
            return $response;
        }

        $evaluation = Evaluation::find($id);

        if (!$evaluation) {
            return response()->json([
                'success' => false,
                'message' => 'evaluation not found'
            ], 404);
        }
        $validator = Validator::make($request->all(), [
            'period_section_id' => 'exists:periods_sections,id',
            'evaluation_type_id' => 'exists:evaluation_types,id',
            'teacher_user_id' => 'exists:users,id',
            'title' => 'string|max:100',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date|after_or_equal:date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $evaluation->update($request->only([
            'period_section_id',
            'evaluation_type_id',
            'teacher_user_id',
            'title',
            'description',
            'due_date'
        ]));

        return response()->json([
            'success' => true,
            'data' => $evaluation
        ]);
    }

    // Eliminar una evaluación
    public function destroy($id, Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }

        $evaluation = Evaluation::find($id);

        if (!$evaluation) {
            return response()->json([
                'success' => false,
                'message' => 'evaluation not found'
            ], 404);
        }

        $evaluation->delete();

        return response()->json([
            'success' => true,
            'message' => 'evaluation deleted successfully'
        ]);
    }
}
