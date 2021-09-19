<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class LessonRequest extends FormRequest
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
        return [
            'title' => ['required', 'string'],
            'detail' => ['required', 'string'],
            'process' => ['required', 'string'],
            'organization' => ['required', 'string'],
            'conditions' => ['required', 'string'],
            'schedule' => ['required', 'array'],
            'schedule.*.day' => ['required', 'string', 'in:Lundi,Mardi,Mercredi,Jeudi,Vendredi'],
            'schedule.*.begin' => ['required', 'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/'],
            'schedule.*.end' => ['required', 'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/'],
        ];
    }
}
