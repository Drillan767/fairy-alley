<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class RenewalRequest extends FormRequest
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
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'health_data' => ['nullable', 'string'],
            'medical_certificate' => ['nullable', 'mimes:png,jpg,pdf', 'max:10000'],
            'schedule1' => ['required', 'integer', 'exists:lessons,id'],
            'schedule2' => ['nullable', 'integer', 'exists:lessons,id', 'different:schedule1'],
            'payment_method' => ['required', 'string', 'in:full,quarterly'],
            'invites' => ['array'],
            'invites.*.firstname' => ['required', 'string'],
            'invites.*.lastname' => ['required', 'string'],
            'invites.*.email' => ['nullable', 'required_without:phone'],
            'invites.*.phone' => ['nullable', 'required_without:email', 'string'],
            'invites.*.lid' => ['required', 'string', 'exists:lessons,id'],
            'accepts' => ['required', 'accepted'],
        ];
    }

    public function attributes(): array
    {
        return [
            'medical_certificate' => 'Certificat médical',
            'schedule1' => 'Choix 1',
            'schedule2' => 'Choix 2',
            'invites.*.firstname' => 'Prénom',
            'invites.*.lastname' => 'Nom',
            'invites.*.email' => 'email',
            'invites.*.phone' => 'téléphone',
            'invites.*.lid' => 'cours souhaité',
        ];
    }

    public function messages(): array
    {
        return [
            'schedule_choice1.required' => 'Veuillez sélectionner une date.',
            'schedule_choice2.different' => 'Vous devez selectionner deux dates différentes.',
            'accepts.accepted' => 'Vous devez accepter les conditions pour vous inscrire.',
            'invites.*.lid.required' => 'Vous devez sélectionner un cours.',
        ];
    }
}
