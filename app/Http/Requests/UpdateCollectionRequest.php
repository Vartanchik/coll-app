<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCollectionRequest extends FormRequest
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
                'title' => 'required',
                'description' => 'required',
                'targetAmount' => 'required|numeric',
                'link' => 'required|url'
            ];
        }
        return [
            'title' => 'sometimes|required',
            'description' => 'sometimes|required',
            'targetAmount' => 'sometimes|required|numeric',
            'link' => 'sometimes|required|url'
        ];
    }

    /**
     * Preparing data for validation.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        if ($this->targetAmount) {
            $this->merge(['target_amount' => $this->targetAmount]);
        }
    }
}
