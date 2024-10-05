<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddSlovoVKRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'slovo' => 'required|unique:stoplinks_v_k_s,slovo',
        ];
    }
}
