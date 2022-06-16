<?php

return [
    'tos' => [
        'details' => '<p>30 cours sur l’année %YEAR% d’une durée d’1h15<br />L’inscription est annuelle<br />Le nombre de participants est limité à 12 personnes par cours</p>',
        'process' => "<ol>Préinscription
                    <ol>
                    <li>Dépôt de chèque d'accompte de %price_full% ou mise en place d'un paiement en plusieurs fois à raison de %price_quarterly% par trimestre.</li>
                    <li>Remplissage des 2 possibilités du tableau</li>
                    <li>Acceptation des conditions ci-dessous</li>
                    </ol>
                    </li>
                    <li>Confirmation du cours dans lequel vous serez (courant Août)</li>
                    <li>Inscription définitive lors du premier cours avec réception de votre paiement annuel (chèque ou<br />virement).</li>
                    </ol>",
        'organization' => '<ul>
                        <li>L’activité ne peut fonctionner qu’avec un minimum de participants. Les tarifs sont calculés au plus juste</li>
                        <li>Si le nombre d’inscrits pour un cours donné est insuffisant il sera annulé et une autre proposition sera<br />faite aux participants pour intégrer un autre cours à un horaire différent</li>
                        <li>Pour chaque cours : Une serviette pour le corps + une serviette pour les mains</li>
                        </ul>',
        'conditions' => '<p><strong>Toute annulation</strong></p>
                    <ul>
                    <li><strong>du fait de l’organisatrice</strong><br />donne lieu au remplacement de la séance sauf en cas de force majeure (épidémie, catastrophe naturelle<br />etc.). Dans ce cas l’organisatrice prendra contact avec les participants et mettra tout en œuvre pour<br />remplacer la séance en cas d’annulation de sa part</li>
                    <li><strong>de la part des autorités</strong><br />n’est pas remboursable mais l’organisatrice les remplacera par des séances Zoom ou par des vidéos</li>
                    <li><strong>de la part du participant</strong><br />n’est pas remboursable mais remplaçable via le tableau Absences/Remplacements</li>
                    </ul>
                    <p><br /><strong>Règlement</strong><br />Pour suivre les cours il faut <strong>avoir réglé les cours en totalité lors du premier cours (360€ pour l’année)</strong><br />Le règlement final peut se faire par chèque ou virement bancaire</p>',
    ],
    'max_users' => 20,
    'headlines' => [
        [
            'status_id' => 'En attente',
            'title' => 'Votre inscription est en cours de validation.',
            'subtitle' => "Vous recevrez un email lors de la confirmation de celle-ci ou pour toute demande d'informations complémentaires.",
        ],
        [
            'status_id' => 'Validé',
            'title' => 'Votre espace personnel',
            'subtitle' => 'Retrouvez ci-dessous les informations liées à votre cours',
        ],
        [
            'status_id' => "En attente d'informations",
            'title' => 'Il manque des informations pour compléter votre inscription',
            'subtitle' => "Veuillez modifier votre demande d'inscription avec les informations que vous a envoyé l'organisatrice.",
        ],
        [
            'status_id' => 'En attente du paiement',
            'title' => 'En attente de votre paiement',
            'subtitle' => "L'organisatrice indique ne pas avoir encore reçu votre paiement. Si vous l'avez déjà envoyé,
            ne tenez pas compte de ce message, sinon, veuillez l'envoyer dans les plus brefs délais.",
        ],
        [
            'status_id' => 'Fin des cours',
            'title' => 'Veuillez sélectionner un nouveau cours pour la rentrée prochaine',
            'subtitle' => "La fin d'année est là, il est temps de renouveler votre présence pour l'année prochaine si vous le souhaitez !",
        ],
    ],

    'renewal' => [
        [
            'status' => 1,
            'title' => 'Votre demande de réinscription a bien été prise en compte, elle sera traitée dans les plus brefs délais.',
            'color' => 'yellow',
            'showEdit' => false,
        ],
        [
            'status' => 2,
            'title' => 'Votre demande d\'inscription est terminée : vous êtes réinscrit(e) pour l\'année prochaine.',
            'color' => 'green',
            'showEdit' => false,
        ],
        [
            'status' => 3,
            'title' => 'Demande d\'inscription incomplète : des documents sont manquants, veuillez modifier votre demande de réinscription.',
            'color' => 'orange',
            'showEdit' => true,
        ],
        [
            'status' => 4,
            'title' => 'Demande d\'inscription incomplète : le paiement est manquant, veuillez vous en occuper dans les plus brefs délais.',
            'color' => 'orange',
            'showEdit' => false,
        ]
    ]
];
