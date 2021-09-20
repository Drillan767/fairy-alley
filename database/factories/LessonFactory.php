<?php

namespace Database\Factories;

use App\Models\Lesson;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class LessonFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Lesson::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'title' => 'Gymnastique rééducative posturale globale',
            'year' => '2021-2022',
            'description' => "Tousled 90's truffaut copper mug. Chartreuse tote bag activated charcoal tbh man bun coloring book godard.",
            'detail' => '<p>30 cours sur l’année 2021-2022 d’une durée d’1h15<br />L’inscription est annuelle<br />Le nombre de participants est limité à 12 personnes par cours</p>',
            'process' => "<ol>
                            <li>Préinscription
                            <ol>
                            <li>Dépôt de chèque d'accompte de 120€</li>
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
            'schedule' => '[{"day":"Lundi","begin":"09:30","end":"10:45"},{"day":"Lundi","begin":"17:30","end":"18:45"},{"day":"Mardi","begin":"08:30","end":"09:45"},{"day":"Mardi","begin":"10:15","end":"11:30"},{"day":"Mardi","begin":"17:00","end":"18:15"},{"day":"Jeudi","begin":"08:00","end":"09:15"},{"day":"Jeudi","begin":"09:45","end":"11:00"},{"day":"Jeudi","begin":"18:00","end":"19:15"},{"day":"Vendredi","begin":"09:15","end":"10:30"}]',
        ];
    }
}
