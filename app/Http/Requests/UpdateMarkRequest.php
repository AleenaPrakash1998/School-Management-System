<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMarkRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'marks' => 'required|integer|min:0|max:100',
        ];
    }
}
