<?php

namespace App\Http\Requests;

use App\Rules\ValidStudent;
use Illuminate\Foundation\Http\FormRequest;

class StoreHomeworkRequest extends FormRequest
{

    public function rules(): array
    {

        return [
            'student_id' => ['required', 'exists:users,id', new ValidStudent],
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
        ];

    }
}
