<?php

namespace App\Http\Requests;

use App\Rules\ValidStudent;
use Illuminate\Foundation\Http\FormRequest;

class StoreMarkRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'student_id' => ['required', 'exists:users,id', new ValidStudent],
            'homework_id' => 'required|exists:homeworks,id',
            'marks' => 'required|integer|min:0|max:100',
        ];
    }
}
