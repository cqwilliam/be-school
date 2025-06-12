<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\RoleCheck;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ScheduleController extends Controller
{
    use RoleCheck;

    public function index(Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante', 'Apoderado'])) {
            return $response;
        }

        $schedules = Schedule::all();
        return response()->json([
            'success' => true,
            'data' => $schedules
        ]);
    }

    public function store(Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }

        $validator = Validator::make($request->all(), [
            'section_id' => 'required|exists:course_sections,id',
            'day_of_week' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'is_recurring' => 'required|boolean',
            'specific_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $schedule = Schedule::create([
            'section_id' => $request->section_id,
            'day_of_week' => $request->day_of_week,
            'start_time' => $request->start_date,
            'end_time' => $request->end_date,
            'is_recurring' => $request->is_recurring,
            'specific_date' => $request->specific_date,
        ]);

        return response()->json([
            'success' => true,
            'data' => $schedule
        ], 201);
    }

    public function show($id, Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante', 'Apoderado'])) {
            return $response;
        }

        $schedule = Schedule::with('section')->find($id);

        if (!$schedule) {
            return response()->json([
                'success' => false,
                'message' => 'schedule not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $schedule
        ]);
    }


    // Actualizar horario
    public function update(Request $request, $id)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }

        $schedule = Schedule::find($id);

        if (!$schedule) {
            return response()->json([
                'success' => false,
                'message' => 'schedule not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'section_id' => 'exists:course_sections,id',
            'day_of_week' => 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'date',
            'end_time' => 'date|after:start_time',
            'is_recurring' => 'boolean',
            'specific_date' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $schedule->update($request->only([
            'section_id',
            'day_of_week',
            'start_time',
            'end_time',
            'is_recurring',
            'specific_date'
        ]));

        return response()->json([
            'success' => true,
            'data' => $schedule
        ]);
    }

    // Eliminar horario
    public function destroy($id, Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }

        $schedule = Schedule::find($id);

        if (!$schedule) {
            return response()->json([
                'success' => false,
                'message' => 'schedule not found'
            ], 404);
        }

        $schedule->delete();

        return response()->json([
            'success' => true,
            'message' => 'schedule deleted successfully'
        ]);
    }
}
