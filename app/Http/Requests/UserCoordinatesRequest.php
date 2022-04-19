<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class UserCoordinatesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'address1' => ['required', 'string'],
            'address2' => ['nullable', 'string'],
            'zipcode' => ['required', 'max:5'],
            'city' => ['required', 'string'],
            'phone' => ['required', 'string'],
            'pro' => ['required', 'string'],
        ];
    }
}
