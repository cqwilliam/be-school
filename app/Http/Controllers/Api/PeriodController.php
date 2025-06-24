<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\RoleCheck;
use App\Models\Period;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PeriodController extends Controller
{
    use RoleCheck;

    public function index(Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente'])) {
            return $response;
        }

        $periods = Period::all();
        return response()->json([
            'success' => true,
            'data' => $periods
        ]);
    }

    public function store(Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'active' => 'boolean'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $period = Period::create([
            'name' => $request->name,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'active' => $request->active,
        ]);

        return response()->json([
            'success' => true,
            'data' => $period
        ], 201);
    }

    public function show($id, Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente'])) {
            return $response;
        }

        $period = Period::find($id);
        if (!$period) {
            return response()->json([
                'success' => false,
                'message' => 'Academic Period not found'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => $period
        ]);
    }

    public function update(Request $request, $id)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }

        $period = Period::find($id);
        if (!$period) {
            return response()->json([
                'success' => false,
                'message' => 'Academic Period not found'
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'string|max:100',
            'start_date' => 'date',
            'end_date' => 'date|after:start_date',
            'active' => 'boolean'
        ]);
        if (!empty($validated['active']) && $validated['active'] === true) {
            Period::where('active', true)->update(['active' => false]);
        }

        $period->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Academic Period updated successfully',
            'data' => $period
        ]);
    }

    public function destroy($id, Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }

        $period = Period::find($id);
        if (!$period) {
            return response()->json([
                'success' => false,
                'message' => 'Academic Period not found'
            ], 404);
        }

        $period->delete();

        return response()->json([
            'success' => true,
            'message' => 'Academic Period deleted successfully'
        ]);
    }
    public function getNamePeriodActual(Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante', 'Apoderado'])) {
            return $response;
        }

        $period = Period::where('active', true)->select('name')->first();

        return response()->json([
            'success' => true,
            'name' => $period ? $period->name : 'Sin periodo activo'
        ]);
    }
}
