<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Http;

class StudentController extends Controller
{
    // GET /api/students
    public function index()
    {
        return Student::all();
    }

    // POST /api/students
    public function store(Request $request)
{
    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:100',
        'email' => 'required|email|unique:students,email',
        'age' => 'required|integer|min:10|max:100'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'errors' => $validator->errors()
        ], 422);
    }

    $student = Student::create($request->all());
    return response()->json($student, 201);
}


    // GET /api/students/{id}
    public function show($id)
    {
        return Student::findOrFail($id);
    }

    // PUT /api/students/{id}
    public function update(Request $request, $id)
{
    $student = Student::findOrFail($id);

    $validator = Validator::make($request->all(), [
        'name' => 'required|string|max:100',
        'email' => 'required|email|unique:students,email,' . $id, // biar tidak bentrok email-nya sendiri
        'age' => 'required|integer|min:10|max:100'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'errors' => $validator->errors()
        ], 422);
    }

    $student->update($request->all());
    return response()->json($student, 200);
}


    // DELETE /api/students/{id}
    public function destroy($id)
    {
        Student::destroy($id);
        return response()->json(null, 204);
    }
}

