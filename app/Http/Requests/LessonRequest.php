<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use JetBrains\PhpStorm\ArrayShape;

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
            'description' => ['required', 'string'],
            'ref' => ['required', 'string', 'unique:lessons,ref'],
            'schedule' => ['required', 'array', 'min:1'],
            'schedule.*.day' => ['required', 'string', 'in:Lundi,Mardi,Mercredi,Jeudi,Vendredi'],
            'schedule.*.begin' => ['required', 'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/'],
            'schedule.*.end' => ['required', 'regex:/^([0-1]?[0-9]|2[0-3]):[0-5][0-9]$/'],
        ];
    }

    #[ArrayShape([
        'schedule.*.day' => "string",
        'schedule.*.begin' => "string",
        'schedule.*.end' => "string",
        'ref' => 'référence',
    ])]
    public function attributes(): array
    {
        return [
            'ref' => 'référence',
            'schedule.*.day' => 'jour',
            'schedule.*.begin' => 'début',
            'schedule.*.end' => 'fin',
        ];
    }
}
