<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class PageRequest extends FormRequest
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
            'title' => ['required', 'string', 'unique:pages,title', 'max:255'],
            'slug' => ['required', 'string', 'unique:pages,slug', 'max:255'],
            'summary' => ['required', 'string', 'max:255'],
            'content' => ['required', 'text'],
            'published' => ['required', 'boolean']
        ];
    }
}
