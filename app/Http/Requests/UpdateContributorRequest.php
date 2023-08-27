<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateContributorRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        $method = $this->method();

        // Check if PUT, else PATCH
        if ($method === 'PUT') {
            return [
                'userName' => 'required',
                'amount' => 'required|numeric'
            ];
        }
        return [
            'userName' => 'sometimes|required',
            'amount' => 'sometimes|required|numeric'
        ];
    }

    /**
     * Preparing data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        if ($this->userName) {
            $this->merge(['user_name' => $this->userName]);
        }
    }
}
