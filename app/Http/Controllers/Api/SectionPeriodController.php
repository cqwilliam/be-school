<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Controllers\Traits\RoleCheck;
use App\Models\SectionPeriod;
use Illuminate\Support\Facades\Validator;

class SectionPeriodController extends Controller
{
    use RoleCheck;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante'])) {
            return $response;
        }
        $sectionPeriod = SectionPeriod::all();
        return response()->json([
            'success' => true,
            'data' => $sectionPeriod
        ]);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }
        $validator = Validator::make($request->all(), [
            'section_id' => 'required|integer|exists:sections,id',
            'period_id' => 'required|integer|exists:periods,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        $sectionPeriod = SectionPeriod::create([
            'section_id' => $request->section_id,
            'period_id' => $request->period_id,
        ]);

        return response()->json([
            'success' => true,
            'data' => $sectionPeriod
        ], 201);
    }

    /**
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente'])) {
            return $response;
        }
        $sectionPeriod = SectionPeriod::find($id);
        if (!$sectionPeriod) {
            return response()->json([
                'success' => false,
                'message'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $sectionPeriod
        ]);
    }

    /**
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }
        $sectionPeriod = SectionPeriod::find($id);
        if (!$sectionPeriod) {
            return response()->json([
                'success' => false,
                'message' => 'SectionPeriod not found'
            ], 404);
        }
        $validator = Validator::make($request->all(), [
            'section_id' => 'required|integer|exists:sections,id',
            'period_id' => 'required|integer|exists:periods,id',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }
        $sectionPeriod->update([
            'section_id' => $request->section_id,
            'period_id' => $request->period_id,
        ]);
        return response()->json([
            'success' => true,
            'data' => $sectionPeriod
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }

        $sectionPeriod = SectionPeriod::find($id);

        if (!$sectionPeriod) {
            return response()->json([
                'success' => false,
                'message' => 'SectionPeriod not found'
            ], 404);
        }

        $sectionPeriod->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'SectionPeriod deleted successfully'
        ], 200);
    }
}
