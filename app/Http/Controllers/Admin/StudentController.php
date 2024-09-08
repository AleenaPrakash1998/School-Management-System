<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class StudentController extends Controller
{
    public function __construct()
    {
        // Ensure only admin can access these routes
        $this->middleware('role:admin');
    }

    public function index(): JsonResponse
    {
        // Fetch all users who are students based on your logic
        $students = User::query()
            ->whereHas('roles', function ($query) {
                $query->where('name', 'student');
            })->get();

        return response()->json($students);
    }

    public function store(StoreStudentRequest $request): JsonResponse
    {
        // Validate the incoming request data
        $validated = $request->validated();

        // Create a new user
        $student = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);
        // Assign the 'student' role to the newly created user
        $student->assignRole('student');

        // Create a new Student record with additional information
        Student::create([
            'user_id' => $student->id, // Foreign key linking to the user
            'grade' => $validated['grade'] // Additional information
        ]);

        // Return a JSON response with the created student data
        return response()->json($student->load('roles'), 201); // Load roles if needed for the response
    }

    public function show($id): JsonResponse
    {
        $user = User::query()->find($id);

        // Check if the user exists
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Check if the user has the 'student' role
        if (!$user->hasRole('student')) {
            return response()->json(['error' => 'User is not a student'], 404);
        }

        // Return the user data
        return response()->json($user);
    }

    public function update(UpdateStudentRequest $request, User $student): JsonResponse
    {
        // Check if the user has the 'student' role
        if (!$student->hasRole('student')) {
            return response()->json(['error' => 'Not found'], 404);
        }

        // Validate and update the student data
        $validated = $request->validated();

        // Update user data
        $student->update([
            'name' => $validated['name'] ?? $student->name,
            'email' => $validated['email'] ?? $student->email,
        ]);

        // Update password if provided
        if (isset($validated['password'])) {
            $student->password = bcrypt($validated['password']);
        }

        // Save the changes to the student record
        $student->save();

        // Update additional information if provided
        if (isset($validated['grade'])) {
            // Assuming 'Student' is a model related to 'User' with a one-to-one relationship
            $studentRecord = Student::where('user_id', $student->id)->first();
            if ($studentRecord) {
                $studentRecord->grade = $validated['grade'];
                $studentRecord->save();
            } else {
                // If the student record does not exist, create it
                Student::create([
                    'user_id' => $student->id,
                    'grade' => $validated['grade']
                ]);
            }
        }

        // Return a JSON response with the updated student data
        return response()->json($student->load('student'), 200); // Load roles if needed for the response
    }

    public function destroy($id): JsonResponse
    {
        // Fetch the user by ID
        $user = User::query()->find($id);

        // Check if the user exists
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Check if the user has the 'student' role
        if (!$user->hasRole('student')) {
            return response()->json(['error' => 'User is not a student'], 403);
        }

        // Delete the user
        $user->delete();

        // Return a success response
        return response()->json(['message' => 'Successfully deleted'], 200);
    }
}
