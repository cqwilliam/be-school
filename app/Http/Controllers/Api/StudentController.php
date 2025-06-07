<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StudentController extends Controller
{
    /**
     * Display a listing of all students.
     */
    public function index()
    {
        $students = Student::all();
        //$student = Student::with('user')->get();

        return response()->json([
            'success' => true,
            'data' => $students
        ]);
    }

    /**
     * Store a newly created student in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id|unique:students,user_id',
            'grade' => 'required|string|max:20',
            'section' => 'required|string|max:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $student = Student::create([
            'user_id' => $request->user_id,
            'grade' => $request->grade,
            'section' => $request->section,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Student created successfully',
            'data' => $student->load('user')
        ], 201);
    }

    /**
     * Display the specified student.
     */
    public function show($id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $student
        ]);
    }

    /**
     * Update the specified student in storage.
     */
    public function update(Request $request, $id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            //'user_id' => 'sometimes|exists:users,id|unique:students,user_id,' . $student->id,
            'grade' => 'sometimes|string|max:20',
            'section' => 'sometimes|string|max:10',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors()
            ], 422);
        }

        $student->update($request->only(['grade', 'section']));

        return response()->json([
            'success' => true,
            'message' => 'Student updated successfully',
            'data' => $student->load('user')
        ]);
    }

    /**
     * Remove the specified student from storage.
     */
    public function destroy($id)
    {
        $student = Student::find($id);

        if (!$student) {
            return response()->json([
                'success' => false,
                'message' => 'Student not found'
            ], 404);
        }

        $student->delete();

        return response()->json([
            'success' => true,
            'message' => 'Student deleted successfully'
        ]);
    }

    /**
     * Assign a guardian to a student.
     */
}
