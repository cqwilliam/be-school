<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\RoleCheck;
use App\Models\PeriodSectionUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PeriodSectionUserController extends Controller
{
    use RoleCheck;

    public function index(Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante'])) {
            return $response;
        }

        $periodSectionUsers = PeriodSectionUser::all();
        return response()->json([
            'success' => true,
            'data' => $periodSectionUsers
        ]);
    }

    public function store(Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'period_section_id' => 'required|integer|exists:periods_sections,id',
            'status' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $periodSectionUser = PeriodSectionUser::create([
            'user_id' => $request->user_id,
            'period_section_id' => $request->period_section_id,
            'status' => $request->status
        ]);
        return response()->json([
            'success' => true,
            'data' => $periodSectionUser
        ]);
    }

    public function show($id, Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante'])) {
            return $response;
        }

        $periodSectionUser = PeriodSectionUser::find($id);
        if (!$periodSectionUser) {
            return response()->json([
                'success' => false,
                'message' => 'PeriodSectionUser not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $periodSectionUser
        ]);
    }

    public function update($id, Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }
        $periodSectionUser = PeriodSectionUser::find($id);
        if (!$periodSectionUser) {
            return response()->json([
                'success' => false,
                'message' => 'PeriodSectionUser not found'
            ], 404);
        }
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|integer|exists:users,id',
            'period_section_id' => 'required|integer|exists:periods_sections,id',
            'status' => 'required|string|max:255'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $periodSectionUser->update([
            'user_id' => $request->user_id,
            'period_section_id' => $request->period_section_id,
            'status' => $request->status
        ]);
        
        return response()->json([
            'success' => true,
            'data' => $periodSectionUser
        ]);
    }

    public function destroy($id, Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }
        $periodSectionUser = PeriodSectionUser::find($id);

        if (!$periodSectionUser) {
            return response()->json([
                'success' => false,
                'message' => 'PeriodSectionUser not found'
            ], 404);
        }
        $periodSectionUser->delete();
        return response()->json([
            'success' => true,
            'message' => 'PeriodSectionUser deleted'
        ]);
    }
}
