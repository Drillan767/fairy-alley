<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserUpdateRequest extends UserCoordinatesRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::user()->hasRole('administrator');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'firstname' => ['required', 'string'],
            'lastname' => ['required', 'string'],
            'email' => ['required', 'email:rfc,dns'],
            'gender' => ['required', 'in:H,F'],
            'birthday' => ['nullable', 'date_format:Y-m-d'],
            'other_data' => ['nullable', 'string'],
            'suggestions' => ['nullable', 'array'],
        ];
    }
}
