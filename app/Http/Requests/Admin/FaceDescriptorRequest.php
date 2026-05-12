<?php

namespace App\Http\Requests\Admin;

use App\Support\Role;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FaceDescriptorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->hasRole(Role::ADMIN) ?? false;
    }

    public function rules(): array
    {
        return [
            'descriptor' => ['required', 'array', 'min:16'],
            'descriptor.*' => ['required', 'numeric'],
            'source' => ['nullable', Rule::in(['camera', 'upload'])],
        ];
    }
}
