<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHomeWorkRequest extends FormRequest
{

    public function rules(): array
    {

        return [
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'nullable|date',
        ];

    }
}
