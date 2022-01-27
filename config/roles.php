<?php

return [
    'administrator' => [
        'display' => 'Administrateur',
        'email' => '',
        'progressions' => [],
        'warning' => "Vous êtes sur le point d'octroyer tous les droits à cette personne, et serez incapable de la supprimer sans aide externe.",
    ],
    'first_contact' => [
        'display' => 'Premier contact',
        'email' => '',
        'progressions' => [],
        'warning' => "Vous êtes sur le point de retirer tous les droits à cette personne, et elle sera désormais incapable de se connecter.",
    ],
    'guest' => [
        'display' => 'Invité',
        'email' => '',
        'progressions' => [],
        'warning' => "La personne pourra se connecter et s'inscrire à un service, sans être en mesure de s'inscrire d'elle-même à un cours.",
    ],
    'presubscribed' => [
        'display' => 'Inscription en cours',
        'email' => '',
        'progressions' => [],
        'warning' => "La personne est sur le point d'être placée en processus d'inscription. Elle devra s'acquitter des frais d'inscription et répondre au questionnaire.",
    ],
    'subscriber' => [
        'display' => 'Inscrit',
        'email' => '',
        'progressions' => [],
        'warning' => "Vous êtes sur le point de donner les droits standards à cette personne, ce qui implique que les frais d'inscriptions ont été payés, et que le questionnaire a été rempli.",
    ],
    'substitute' => [
        'display' => 'Remplaçant',
        'email' => '',
        'warning' => "La personne ne pourra s'inscrire qu'au cours où il reste de la place, et ne pourra pas profiter des services.",
    ],
    'archived' => [
        'display' => 'Archivé',
        'email' => '',
        'progressions' => [],
        'warning' => "Vous êtes sur le point de retirer tous les droits à cette personne, et elle ne sera plus en mesure de se connecter. Ce statut vous permet de conserver toutes ses données à but d'usages ultérieurs.",
    ],
];
