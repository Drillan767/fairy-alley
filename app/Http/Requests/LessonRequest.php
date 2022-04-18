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
//        dd($this);
        return [
            'title' => ['required', 'string'],
            'description' => ['required', 'string'],
            'gender' => ['required', 'array', 'min:1', 'max:2'],
            'type' => ['required', 'string'],
            'ref' => ['required', 'string', 'unique:lessons,ref,' . $this->id],
            'schedule' => ['required', 'array', 'min:1'],
        ];
    }

    #[ArrayShape([
        'schedule.*.date' => 'string',
        'ref' => 'référence',
    ])]
    public function attributes(): array
    {
        return [
            'ref' => 'référence',
            'schedule.*.date' => 'occurrence',
        ];
    }
}
