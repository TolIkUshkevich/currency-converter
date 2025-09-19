<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SelectCurrencyRequest extends FormRequest
{
    protected function prepareForValidation(): void
    {
        $this->merge([
            'numerator' => $this->numerator ?? 'USD',
            'denumerator' => $this->denumerator ?? 'EUR'
        ]);
    }
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'numerator' => 'required|string|size:3',
            'denumerator' => 'required|string|size:3'
        ];
    }
}
