<?php

namespace App\Http\Requests;

use Illuminate\Support\Str;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class CreateDepartmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Prepare the data for validation.
     */
    protected function prepareForValidation()
    {
        if ($this->has('name')) {
            $this->merge([
                'name' => Str::lower($this->name),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|unique:departments,name|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Department name is required',
            'name.string' => 'Department name must be a string',
            'name.unique' => 'Department already exists',
            'name.max' => 'Department name must not exceed 255 characters',
        ];
    }
}
