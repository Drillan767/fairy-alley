<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FirstContactRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'firstname' => ['required', 'string'],
            'lastname' => ['required', 'string'],
            'email' => ['required', 'unique:users,email', 'unique:users,email', 'email:rfc,dns'],
            'birthday' => ['nullable', 'date_format:Y-m-d'],
            'phone' => ['nullable', 'required_without:pro', 'string'],
            'pro' => ['nullable', 'required_without:phone', 'string'],
            'work' => ['nullable', 'string'],
            'gender' => ['required', 'max:1', 'in:F,H'],
            'nb_children' => ['nullable', 'integer'],
            'family_situation' => ['required', 'string'],
            'lesson' => ['required', 'integer'],
            'address' => ['nullable', 'string'],
            'address2' => ['nullable', 'string'],
            'zipcode' => ['nullable', 'string', 'max:5'],
            'city' => ['nullable', 'string'],
            'health_issue' => ['nullable', 'string'],
            'current_health_issue' => ['nullable', 'string'],
            'medical_treatment' => ['nullable', 'string'],
            'sports' => ['nullable', 'string'],
            'objectives' => ['nullable', 'string'],
            'other_data' => ['nullable', 'string'],
        ];
    }
}
