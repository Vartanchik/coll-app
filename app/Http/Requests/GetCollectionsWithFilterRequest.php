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
            'remainingAmount'=>'array|array_keys:eq,lt,lte,gt,gte,ne',
            'remainingAmount.*'=>'float',
            'isLessThanTargetAmount' => 'boolean'
        ];
    }
}
