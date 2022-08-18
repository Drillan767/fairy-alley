<?php

namespace App\Http\Requests;

use Illuminate\Support\Facades\Auth;

class UserUpdateRequest extends UserCoordinatesRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return Auth::user()->hasRole('administrator');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'firstname' => ['required', 'string'],
            'lastname' => ['required', 'string'],
            'email' => ['required', 'email:rfc,dns'],
            'gender' => ['required', 'in:H,F'],
            'birthday' => ['nullable', 'date_format:Y-m-d'],
            'other_data' => ['nullable', 'string'],
            'payments' => ['nullable', 'array', 'max:3'],
            'payments.*.amount' => ['required', 'integer'],
            'payments.*.method' => ['required', 'string', 'in:Chèque,Virement,Espèces'],
            'payments.*.paid_at' => ['required', 'string'],
        ];
    }

    public function attributes(): array
    {
        return [
            'payments.*.amount' => 'Montant',
            'payments.*.method' => 'Moyen de paiement',
            'payments.*.paid_at' => 'Reçu le',
        ];
    }
}
