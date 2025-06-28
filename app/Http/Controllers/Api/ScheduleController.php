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
            'period_section_id' => 'required|exists:periods_sections,id',
            'course_id' => 'required|exists:courses,id',
            'teacher_user_id' => 'required|exists:users,id',
            'day_of_week' => 'required|in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $schedule = Schedule::create([
            'period_section_id' => $request->period_section_id,
            'course_id' => $request->course_id,
            'teacher_user_id' => $request->teacher_user_id,
            'day_of_week' => $request->day_of_week,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
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
            'period_section_id' => 'exists:periods_sections,id',
            'course_id' => 'exists:courses,id',
            'teacher_user_id' => 'exists:users,id',
            'day_of_week' => 'in:Monday,Tuesday,Wednesday,Thursday,Friday,Saturday,Sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $schedule->update([
            'period_section_id' => $request->period_section_id,
            'course_id' => $request->course_id,
            'teacher_user_id' => $request->teacher_user_id,
            'day_of_week' => $request->day_of_week,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
        ]);

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
    
    public function getStudentSchedule($student_id, Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante', 'Apoderado'])) {
            return $response;
        }

        $schedule = Schedule::with([
            'courseSection.course',
            'periodSection.teachers.teacher.user'
        ])
            ->whereHas('periodSection.enrollments', function ($query) use ($student_id) {
                $query->where('student_id', $student_id);
            })
            ->whereHas('courseSection.course.academicPeriod', function ($query) {
                $query->where('active', true);
            })
            ->where('is_recurring', true)
            ->orderBy('day_of_week')
            ->orderBy('start_date')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $schedule
        ]);
    }

    public function getTeacherSchedule($teacher_id, Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente'])) {
            return $response;
        }

        $schedule = Schedule::with([
            'periodSection.course'
        ])
            ->whereHas('periodSection.teachers', function ($query) use ($teacher_id) {
                $query->where('teacher_id', $teacher_id);
            })
            ->whereHas('courseSection.course.academicPeriod', function ($query) {
                $query->where('active', true);
            })
            ->where('is_recurring', true)
            ->orderBy('day_of_week')
            ->orderBy('start_date')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $schedule
        ]);
    }
}
