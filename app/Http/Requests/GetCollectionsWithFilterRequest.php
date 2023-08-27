<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class GetCollectionsWithFilterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'array_key_first(remainingAmount)' => 'in:eq,lt,lte,gt,gte,ne',
            'remainingAmount.*' => 'numeric',
            'isLessThanTargetAmount' => 'boolean'
        ];
    }
}
