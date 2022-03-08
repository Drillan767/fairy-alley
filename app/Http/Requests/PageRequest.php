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
        $rules = [
            'summary' => ['required', 'string', 'max:255'],
            'content' => ['required', 'string'],
            'published' => ['required', 'boolean'],
        ];

        if ($this->routeIs('pages.store')) {
            $rules['title'] = ['required', 'string', 'unique:pages,title', 'max:255'];
            $rules['slug'] = ['required', 'string', 'unique:pages,slug', 'max:255'];
            $rules['imgFile'] = ['required', 'mimes:jpg,jpeg,png,webp'];
        } else {
            $rules['title'] = ['required', 'string', 'unique:pages,title,' . $this->id, 'max:255'];
            $rules['slug'] = ['required', 'string', 'unique:pages,slug,' . $this->id, 'max:255'];
        }

        return $rules;
    }
}
