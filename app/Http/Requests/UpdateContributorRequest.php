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
        return [
            'userName' => 'required',
            'amount' => 'required|numeric'
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
