<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateHomeWorkRequest;
use App\Http\Requests\UpdateStudentHomeWorkRequest;
use App\Models\Homework;
use App\Models\StudentHomework;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentPerformanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    // View assigned homework
    public function viewHomework($id): JsonResponse
    {
        $homework = Homework::query()->find($id);

        if (!$homework) {
            return response()->json(['error' => 'Homework not found'], 404);
        }

        return response()->json($homework);
    }

    // Submit homework
    public function submitHomework(UpdateStudentHomeWorkRequest $request): JsonResponse
    {
        $validated = $request->validated();
        $userId = Auth::id();

        // Check if the homework exists
        $homework = Homework::query()->find($validated['homework_id']);
        if (!$homework) {
            return response()->json(['error' => 'Homework not found'], 404);
        }

        // Store the file if provided
        if ($request->hasFile('submission_file')) {
            $filePath = $request->file('submission_file')->store('homework_submissions');
        } else {
            $filePath = null;
        }

        // Create or update the submission record
        $submission = StudentHomework::updateOrCreate(
            ['student_id' => $userId, 'homework_id' => $validated['homework_id']],
            ['submitted' => true, 'submission_file' => $filePath]
        );

        return response()->json($submission, 201);
    }

    // Monitor performance

    public function monitorPerformance(Request $request): JsonResponse
    {
        $userId = Auth::id();
        $period = $request->query('period');
        $now = now();

        switch ($period) {
            case 'year':
                $start = $now->copy()->startOfYear();
                $end = $now->copy()->endOfYear();
                break;
            case 'month':
                $start = $now->copy()->startOfMonth();
                $end = $now->copy()->endOfMonth();
                break;
            case 'week':
                $start = $now->copy()->startOfWeek();
                $end = $now->copy()->endOfWeek();
                break;
            default:
                return response()->json(['error' => 'Invalid period'], 400);
        }

        // Fetch submissions for the given period
        $submissions = StudentHomework::query()
            ->where('student_id', $userId)
            ->whereHas('homework', function ($query) use ($start, $end) {
                $query->whereBetween('due_date', [$start, $end]);
            })
            ->get();

        // If no submissions found, return 0 or a custom message
        if ($submissions->isEmpty()) {
            return response()->json([
                'message' => 'No submissions found for this period.',
                'submissions_count' => 0
            ], 200);
        }

        // Return the submissions if found
        return response()->json([
            'submissions' => $submissions,
            'submissions_count' => $submissions->count()
        ], 200);
    }

}
