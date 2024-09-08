<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentHomeWorkRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'homework_id' => 'required|exists:homeworks,id',
            'submission_file' => 'nullable|file|mimes:pdf,doc,docx,txt|max:2048',
        ];
    }
}
