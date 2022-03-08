<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class SubscriptionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return Auth::user()->hasRole('subscriber');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'lesson_id' => ['required', 'integer', 'exists:lessons,id'],
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'health_data' => ['nullable', 'string'],
            'medical_certificate' => ['nullable', 'mimes:png,jpg,pdf', 'max:10000'],
            'nb_children' => ['required', 'min:0'],
            'schedule_choice1' => ['required', 'string'],
            'schedule_choice2' => ['nullable', 'string', 'different:schedule_choice1'],
            'invites' => ['array'],
            'invites.*.firstname' => ['required', 'string'],
            'invites.*.lastname' => ['required', 'string'],
            'invites.*.email' => ['nullable', 'required_without:phone'/*'email:rfc,dns'*/],
            'invites.*.phone' => ['nullable', 'required_without:email', 'string'],
            'invites.*.title' => ['required', 'string', 'exists:lessons,title'],
            'accepts' => ['required', 'accepted'],
        ];
    }

    public function attributes(): array
    {
        return [
            'invites.*.firstname' => 'Prénom',
            'invites.*.lastname' => 'Nom',
            'invites.*.email' => 'email',
            'invites.*.phone' => 'téléphone',
            'invites.0.title' => 'cours souhaité',
        ];
    }

    public function messages(): array
    {
        return [
           'schedule_choice1.required' => 'Veuillez sélectionner une date.',
           'schedule_choice2.different' => 'Vous devez selectionner deux dates différentes.',
           'accepts.accepted' => 'Vous devez accepter les conditions pour vous inscrire.',
           'invites.*.title.required' => 'Vous devez sélectionner un cours.',
       ];
    }
}
