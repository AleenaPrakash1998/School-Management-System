<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTeacherStudentRequest extends FormRequest
{
    public function rules(): array
    {
        $studentId = $this->route('teacher_student');

        return [
            'name' => 'nullable|string|max:255',
            'email' => [
                'nullable',
                'string',
                'email',
                'max:255',
                // Ignore the current user's email in the uniqueness check
                Rule::unique('users')->ignore($studentId),
            ],
            'password' => 'nullable|string|min:8',
            'grade' => 'required|integer|min:1|max:12',
        ];
    }
}
