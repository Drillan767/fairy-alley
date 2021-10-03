<?php

namespace App\Casts;

use App\Models\Subscription;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class Status implements CastsAttributes
{
    private array $status = [
        Subscription::PENDING           => 'En attente',
        Subscription::VALIDATED         => 'Validé',
        Subscription::NEEDS_INFOS       => "En attente d'informations",
        Subscription::AWAITING_PAYMENT  => 'En attente du paiement',
        Subscription::SUBSCRIPTION_OVER => 'Fin des cours',
    ];

    public function get($model, string $key, $value, array $attributes)
    {
        return $this->status[$value];
    }

    public function set($model, string $key, $value, array $attributes)
    {
        // TODO: Implement set() method.
    }
}
