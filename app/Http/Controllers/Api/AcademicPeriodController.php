<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AcademicPeriod;
use Illuminate\Http\Request;

class AcademicPeriodController extends Controller
{
    public function index()
    {
        $periods = AcademicPeriod::all();
        return response()->json([
            'success' => true,
            'data' => $periods
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'active' => 'boolean'
        ]);

        if (!empty($validated['active']) && $validated['active'] === true) {
            AcademicPeriod::where('active', true)->update(['active' => false]);
        }

        $period = AcademicPeriod::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Academic Period created successfully',
            'data' => $period
        ], 201);
    }
    
    public function show($id)
    {
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
        $period = AcademicPeriod::find($id);
        if (!$period) {
            return response()->json([
                'success' => false,
                'message' => 'Academic Period not found'
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:100',
            'start_date' => 'sometimes|required|date',
            'end_date' => ['sometimes', 'required', 'date', 'after:start_date'],
            'active' => 'sometimes|boolean'
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

    public function destroy($id)
    {
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
}
