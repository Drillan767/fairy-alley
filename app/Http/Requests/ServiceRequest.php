<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class ServiceRequest extends FormRequest
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
        $rules = [
            'title' => ['required', 'string'],
            'description' => ['required', 'string', 'max:255'],
            'page_id' => ['required', 'integer', 'exists:pages,id']
        ];

        if ($this->getMethod() === 'POST') {
            $rules['illustration'] = ['required', 'file', 'mimes:jpg,jpeg,png,webp'];
        }

        return $rules;
    }
}