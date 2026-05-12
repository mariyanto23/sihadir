<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class LeaveRequestStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->student()->exists() ?? false;
    }

    public function rules(): array
    {
        return [
            'type' => ['required', Rule::in(['Izin', 'Sakit'])],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date', 'after_or_equal:start_date'],
            'reason' => ['required', 'string', 'max:1000'],
        ];
    }
}
