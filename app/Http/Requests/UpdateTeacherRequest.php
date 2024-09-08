<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateTeacherRequest extends FormRequest
{
    public function rules(): array
    {
        $teacherId = $this->route('teacher')->id;

        return [
            'name' => 'nullable|string|max:255',
            'email' => [
                'nullable',
                'string',
                'email',
                'max:255',
                // Ignore the current user's email in the uniqueness check
                Rule::unique('users')->ignore($teacherId),
            ],
            'password' => 'nullable|string|min:8',
            'qualification' => 'nullable|string',
        ];
    }
}
