<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;

class SubscriptionValidation extends UserCoordinatesRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
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
            'decision' => ['required', 'string', 'in:validate,missing,payment'],
            'payment_received_at' => ['required', 'boolean'],
            'pre_registration_signature' => ['required', 'boolean'],
            'feedback' => ['required', 'string'],
        ];
    }
}
