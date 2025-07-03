<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\RoleCheck;
use App\Models\StudentGuardian;
use App\Models\User;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentGuardianController extends Controller
{
    use RoleCheck;

    public function index(Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante', 'Apoderado'])) {
            return $response;
        }

        // Incluir las relaciones student y guardian con sus roles
        $relations = StudentGuardian::with([
            'student' => function ($query) {
                $query->select('id', 'first_name', 'last_name', 'email', 'phone', 'dni', 'birth_date', 'address', 'role_id');
            },
            'student.role:id,name',
            'guardian' => function ($query) {
                $query->select('id', 'first_name', 'last_name', 'email', 'phone', 'dni', 'birth_date', 'address', 'role_id');
            },
            'guardian.role:id,name'
        ])->get();

        return response()->json([
            'success' => true,
            'data' => $relations
        ]);
    }


    public function store(Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }

        $validator = Validator::make($request->all(), [
            'student_user_id' => 'required|exists:users,id',
            'guardian_user_id' => 'required|exists:users,id|different:student_user_id',
            'relationship' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $student = User::with('role')->find($request->student_user_id);
        if (!$student || $student->role->name !== 'Estudiante') {
            return response()->json([
                'success' => false,
                'message' => 'El usuario seleccionado como estudiante no tiene el rol de Estudiante.'
            ], 422);
        }

        $guardian = User::with('role')->find($request->guardian_user_id);
        if (!$guardian || $guardian->role->name !== 'Apoderado') {
            return response()->json([
                'success' => false,
                'message' => 'El usuario seleccionado como apoderado no tiene el rol de Apoderado.'
            ], 422);
        }

        $existingRelation = StudentGuardian::where('student_user_id', $request->student_user_id)
            ->where('guardian_user_id', $request->guardian_user_id)
            ->first();

        if ($existingRelation) {
            return response()->json([
                'success' => false,
                'message' => 'Ya existe una relación entre este estudiante y apoderado.'
            ], 422);
        }

        $studentGuardian = StudentGuardian::create([
            'student_user_id' => $request->student_user_id,
            'guardian_user_id' => $request->guardian_user_id,
            'relationship' => $request->relationship,
        ]);

        $studentGuardian->load(['student', 'guardian']);

        return response()->json([
            'success' => true,
            'message' => 'Relación estudiante-apoderado creada exitosamente.',
            'data' => $studentGuardian
        ], 201);
    }

    public function show($id, Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador', 'Docente', 'Estudiante', 'Apoderado'])) {
            return $response;
        }

        $student_guardian = StudentGuardian::with(['student', 'guardian'])->find($id);

        if (!$student_guardian) {
            return response()->json([
                'success' => false,
                'message' => 'Relación estudiante-apoderado no encontrada.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $student_guardian
        ]);
    }

    public function update(Request $request, $id)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }

        $student_guardian = StudentGuardian::find($id);

        if (!$student_guardian) {
            return response()->json([
                'success' => false,
                'message' => 'Relación estudiante-apoderado no encontrada.'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'student_user_id' => 'required|exists:users,id',
            'guardian_user_id' => 'required|exists:users,id|different:student_user_id',
            'relationship' => 'nullable|string|max:100',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $student = User::with('role')->find($request->student_user_id);
        if (!$student || $student->role->name !== 'Estudiante') {
            return response()->json([
                'success' => false,
                'message' => 'El usuario seleccionado como estudiante no tiene el rol de Estudiante.'
            ], 422);
        }

        $guardian = User::with('role')->find($request->guardian_user_id);
        if (!$guardian || $guardian->role->name !== 'Apoderado') {
            return response()->json([
                'success' => false,
                'message' => 'El usuario seleccionado como apoderado no tiene el rol de Apoderado.'
            ], 422);
        }

        $existingRelation = StudentGuardian::where('student_user_id', $request->student_user_id)
            ->where('guardian_user_id', $request->guardian_user_id)
            ->where('id', '!=', $id)
            ->first();

        if ($existingRelation) {
            return response()->json([
                'success' => false,
                'message' => 'Ya existe una relación entre este estudiante y apoderado.'
            ], 422);
        }

        $student_guardian->update([
            'student_user_id' => $request->student_user_id,
            'guardian_user_id' => $request->guardian_user_id,
            'relationship' => $request->relationship,
        ]);

        $student_guardian->load(['student', 'guardian']);

        return response()->json([
            'success' => true,
            'message' => 'Relación estudiante-apoderado actualizada exitosamente.',
            'data' => $student_guardian
        ], 200);
    }

    public function destroy($id, Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }

        $student_guardian = StudentGuardian::find($id);

        if (!$student_guardian) {
            return response()->json([
                'success' => false,
                'message' => 'Relación estudiante-apoderado no encontrada.'
            ], 404);
        }

        $student_guardian->delete();

        return response()->json([
            'success' => true,
            'message' => 'Relación estudiante-apoderado eliminada exitosamente.'
        ]);
    }

    public function getAvailableStudents(Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }

        $studentRole = Role::where('name', 'Estudiante')->first();
        $students = User::where('role_id', $studentRole->id)->get();

        return response()->json([
            'success' => true,
            'data' => $students
        ]);
    }

    public function getAvailableGuardians(Request $request)
    {
        if ($response = $this->checkRole($request, ['Administrador'])) {
            return $response;
        }

        $guardianRole = Role::where('name', 'Apoderado')->first();
        $guardians = User::where('role_id', $guardianRole->id)->get();

        return response()->json([
            'success' => true,
            'data' => $guardians
        ]);
    }
}
