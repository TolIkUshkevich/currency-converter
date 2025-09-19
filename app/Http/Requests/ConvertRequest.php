<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ConvertRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'fromCharCode' => 'required|string|size:3',
            'toCharCode' => 'required|string|size:3',
            'amount' => 'required|numeric|min:0'
        ];
    }
}
