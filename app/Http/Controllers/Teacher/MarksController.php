<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreMarkRequest;
use App\Http\Requests\UpdateMarkRequest;
use App\Models\Mark;
use Illuminate\Http\JsonResponse;

class MarksController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:teacher');
    }

    public function store(StoreMarkRequest $request): JsonResponse
    {
        $validated = $request->validated();

        $mark = Mark::create([
            'student_id' => $validated['student_id'],
            'homework_id' => $validated['homework_id'],
            'marks' => $validated['marks'],
        ]);

        return response()->json($mark, 201);
    }

    public function update(UpdateMarkRequest $request, Mark $mark): JsonResponse
    {
        $validated = $request->validated();

        $mark->update($validated);

        return response()->json($mark, 200);
    }
}
