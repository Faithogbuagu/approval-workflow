<?php

namespace App\Http\Requests;

use Illuminate\Validation\Rule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDepartmentRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $departmentId = $this->route('department')?->id ?? $this->route('department');

        return [
            'name' => [
                'required',
                'string',
                Rule::unique('departments', 'name')->ignore($departmentId),
                'max:255',
            ],
            'status' => 'required|in:Active,Inactive',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Department name is required',
            'name.string' => 'Department name must be a string',
            'name.unique' => 'Department already exists',
            'name.max' => 'Department name must not exceed 255 characters',
            'status.required' => 'Department status is required',
            'status.in' => 'Department status must be either Active or Inactive',
        ];
    }
}
