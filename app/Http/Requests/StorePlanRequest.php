<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePlanRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'school_id' => 'required|exists:schools,id',
            'user_id' => 'required|exists:users,id',
            'department_id' => 'required|exists:departments,id',
            'start' => 'required|date',
            'end' => 'date',
        ];

    }
}
