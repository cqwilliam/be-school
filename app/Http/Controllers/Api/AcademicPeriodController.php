<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\RoleCheck;
use App\Models\AcademicPeriod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AcademicPeriodController extends Controller
{
    use RoleCheck;

    public function index(Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente'])) {
            return $response;
        }

        $periods = AcademicPeriod::all();
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

        $period = AcademicPeriod::create([
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

        $period = AcademicPeriod::find($id);
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

        $period = AcademicPeriod::find($id);
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
            AcademicPeriod::where('active', true)->update(['active' => false]);
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

        $period = AcademicPeriod::find($id);
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

        $period = AcademicPeriod::where('active', true)->select('name')->first();

        return response()->json([
            'success' => true,
            'name' => $period ? $period->name : 'Sin periodo activo'
        ]);
    }
}
