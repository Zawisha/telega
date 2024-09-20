<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchClientsRequest extends FormRequest
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
            'line_id' => 'required|exists:search_telegram_lines,id',
            'my_client_id' => 'required|exists:my_clients,id',
        ];
    }
}
