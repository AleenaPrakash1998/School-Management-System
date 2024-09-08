<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class TeacherController extends Controller
{
    public function __construct()
    {
        // Ensure only admin can access these routes

        $this->middleware('role:admin');
    }

    public function index(): JsonResponse
    {
        // Fetch all users who are teachers based on your logic

        $teachers = User::query()
            ->whereHas('roles', function ($query) {
                $query->where('name', 'teacher');
            })->get();

        return response()->json($teachers);
    }

    public function store(StoreTeacherRequest $request): JsonResponse
    {
        // Validate the incoming request data
        $validated = $request->validated();

        // Create a new user
        $teacher = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => bcrypt($validated['password']),
        ]);
        // Assign the 'teacher' role to the newly created user
        $teacher->assignRole('teacher');


        // Create a new Teacher record with additional information
        Teacher::create([
            'user_id' => $teacher->id, // Foreign key linking to the user
            'qualification' => $validated['qualification'] // Additional information
        ]);

        // Return a JSON response with the created teacher data
        return response()->json($teacher->load('roles'), 201); // Load roles if needed for the response
    }

    public function show($id): JsonResponse
    {
        $user = User::query()->find($id);

        // Check if the user exists
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Check if the user has the 'teacher' role
        if (!$user->hasRole('teacher')) {
            return response()->json(['error' => 'User is not a teacher'], 404);
        }

        // Return the user data
        return response()->json($user);
    }

    public function update(UpdateTeacherRequest $request, User $teacher): JsonResponse
    {
        // Check if the user has the 'teacher' role
        if (!$teacher->hasRole('teacher')) {
            return response()->json(['error' => 'Not found'], 404);
        }

        // Validate and update the teacher data
        $validated = $request->validated();

        // Update user data
        $teacher->update([
            'name' => $validated['name'] ?? $teacher->name,
            'email' => $validated['email'] ?? $teacher->email,
        ]);

        // Update password if provided
        if (isset($validated['password'])) {
            $teacher->password = bcrypt($validated['password']);
        }

        // Save the changes to the teacher record
        $teacher->save();

        // Update additional information if provided
        if (isset($validated['qualification'])) {
            // Assuming 'Teacher' is a model related to 'User' with a one-to-one relationship
            $teacherRecord = Teacher::where('user_id', $teacher->id)->first();
            if ($teacherRecord) {
                $teacherRecord->qualification = $validated['qualification'];
                $teacherRecord->save();
            } else {
                // If the teacher record does not exist, create it
                Teacher::create([
                    'user_id' => $teacher->id,
                    'qualification' => $validated['qualification']
                ]);
            }
        }

        // Return a JSON response with the updated teacher data
        return response()->json($teacher->load('teacher'), 200); // Load roles if needed for the response
    }

    public function destroy($id): JsonResponse
    {
        // Fetch the user by ID
        $user = User::query()->find($id);

        // Check if the user exists
        if (!$user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        // Check if the user has the 'teacher' role
        if (!$user->hasRole('teacher')) {
            return response()->json(['error' => 'User is not a teacher'], 403);
        }

        // Delete the user
        $user->delete();

        // Return a success response
        return response()->json(['message' => 'Successfully deleted'], 200);
    }
}
