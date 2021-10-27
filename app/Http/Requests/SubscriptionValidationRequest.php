<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;

class SubscriptionValidationRequest extends UserCoordinatesRequest
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
    public function rules()
    {
        return parent::rules() + [
            'decision' => ['required', 'string', 'in:accepted,missing'],
            'payments' => ['nullable', 'array'],
            'pre_registration_signature' => ['nullable', 'string', 'date_format:Y-m-d'],
            'feedback' => ['nullable', 'required_if:decision,missing', 'string'],
        ];
    }
}
