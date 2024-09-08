<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHomeworkRequest;
use App\Http\Requests\UpdateHomeWorkRequest;
use App\Models\Homework;
use Illuminate\Http\JsonResponse;

class HomeworkController extends Controller
{
    public function __construct()
    {
        // Ensure only admin can access these routes
        $this->middleware('role:teacher');
    }

    /**
     * Store a newly created homework in storage.
     *
     * @param StoreHomeworkRequest $request
     * @return JsonResponse
     */
    public function store(StoreHomeworkRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $homework = Homework::create([
            'student_id' => $validated['student_id'],
            'title' => $validated['title'],
            'description' => $validated['description'],
            'due_date' => $validated['due_date'],
        ]);

        return response()->json($homework, 201);
    }

    /**
     * Update the specified homework in storage.
     *
     * @param UpdateHomeworkRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateHomeWorkRequest $request, int $id): JsonResponse
    {
        $validated = $request->validated();

        // Find the homework by ID
        $homework = Homework::find($id);

        // Check if homework exists
        if (!$homework) {
            return response()->json(['error' => 'Homework not found'], 404);
        }

        // Update the homework record
        $homework->update([
            'title' => $validated['title'] ?? $homework->title,
            'description' => $validated['description'] ?? $homework->description,
            'due_date' => $validated['due_date'] ?? $homework->due_date,
        ]);

        return response()->json($homework, 200);
    }
}
