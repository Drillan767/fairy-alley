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
        return Auth::check();
    }

    public function rules()
    {
        return [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'lesson_id' => ['required', 'integer', 'exists:lessons,id'],
            'action' => ['required', 'string', 'in:join,leave,lock'],
            'lesson_time' => ['required', 'string']
        ];
    }

    // After hook :
    // check for "by_admin", return error if user is not admin
    // check for "action", return error if 'locked', and user is not admin
}
