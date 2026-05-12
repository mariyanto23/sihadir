<?php

namespace App\Http\Requests\Student;

use Illuminate\Foundation\Http\FormRequest;

class FaceCheckInRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->student()->exists() ?? false;
    }

    public function rules(): array
    {
        return [
            'matched' => ['required', 'accepted'],
            'distance' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
