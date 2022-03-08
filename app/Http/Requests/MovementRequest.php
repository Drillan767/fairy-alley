<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class MovementRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->hasAnyRole('subscriber', 'guest', 'substitute');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'lesson_id' => ['required', 'integer', 'exists:lessons,id'],
            'action' => ['required', 'string', 'in:joined,cancelled'],
            'lesson_hour' => ['required', 'string', 'regex:^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$'],
        ];
    }
}
