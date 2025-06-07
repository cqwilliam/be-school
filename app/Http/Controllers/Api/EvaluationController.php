<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Evaluation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EvaluationController extends Controller
{
    public function index()
    {
        $evaluations = Evaluation::all();
        return response()->json([
            'success' => true,
            'data' => $evaluations
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'section_id' => 'required|exists:course_sections,id',
            'evaluation_type_id' => 'required|exists:evaluation_types,id',
            'academic_period_id' => 'required|exists:academic_periods,id',
            'title' => 'required|string|max:100',
            'description' => 'nullable|string',
            'weight' => 'required|numeric|min:0',
            'date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:date',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $evaluations = Evaluation::create([
            'section_id' => $request->section_id,
            'evaluation_type_id' => $request->evaluation_type_id,
            'academic_period_id' => $request->academic_period_id,
            'title' => $request->title,
            'description' => $request->description,
            'weight' => $request->weight,
            'date' => $request->date,
            'due_date' => $request->due_date,
        ]);

        return response()->json([
            'success' => true,
            'data' => $evaluations
        ], 201);
    }

    public function show($id)
    {
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
        $evaluation = Evaluation::find($id);

        if (!$evaluation) {
            return response()->json([
                'success' => false,
                'message' => 'evaluation not found'
            ], 404);
        }
        $validator = Validator::make($request->all(), [
            'section_id' => 'exists:course_sections,id',
            'evaluation_type_id' => 'exists:evaluation_types,id',
            'academic_period_id' => 'exists:academic_periods,id',
            'title' => 'string|max:100',
            'description' => 'nullable|string',
            'weight' => 'numeric|min:0',
            'date' => 'nullable|date',
            'due_date' => 'nullable|date|after_or_equal:date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $evaluation->update($request->only([
            'section_id',
            'evaluation_type_id',
            'academic_period_id',
            'title',
            'description',
            'weight',
            'date',
            'due_date'
        ]));

        return response()->json([
            'success' => true,
            'data' => $evaluation
        ]);
    }

    // Eliminar una evaluación
    public function destroy($id)
    {
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
