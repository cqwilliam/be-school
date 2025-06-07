<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\EvaluationType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EvaluationTypeController extends Controller
{
    public function index()
    {
        $evaluationTypes = EvaluationType::all();
        return response()->json([
            'success' => true,
            'data' => $evaluationTypes
        ]);
    }

    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50|unique:evaluation_types,name,',
            'description' => 'nullable|string',
            'weight' => 'required|numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $evaluationTypes = EvaluationType::create([
            'name' => $request->name,
            'description' => $request->description,
            'weight' => $request->weight
        ]);

        return response()->json([
            'success' => true,
            'data' => $evaluationTypes
        ], 201);
    }

    public function show($id)
    {
        $evaluationType = EvaluationType::find($id);

        if (!$evaluationType) {
            return response()->json([
                'success' => false,
                'message' => 'evaluationType not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $evaluationType
        ]);
    }

    // Actualizar un tipo de evaluación existente
    public function update(Request $request, $id)
    {

        $evaluationType = EvaluationType::find($id);

        if (!$evaluationType) {
            return response()->json([
                'success' => false,
                'message' => 'evaluationType not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'string|max:50|unique:evaluation_types,name,',
            'description' => 'nullable|string',
            'weight' => 'numeric|min:0'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $evaluationType->update($request->only([
            'name',
            'description',
            'weight'
        ]));

        return response()->json([
            'success' => true,
            'data' => $evaluationType
        ]);
    }


    // Eliminar un tipo de evaluación
    public function destroy($id)
    {
        $evaluationType = EvaluationType::find($id);

        if (!$evaluationType) {
            return response()->json([
                'success' => false,
                'message' => 'evaluationType not found'
            ], 404);
        }

        $evaluationType->delete();

        return response()->json([
            'success' => true,
            'message' => 'evaluationType deleted successfully'
        ]);
    }
}
