<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubscriptionRequest;
use App\Models\Lesson;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;

class SubscriptionController extends Controller
{
    /*
     * Afficher la liste des classes dans la page d'accueil du client si aucun cours lié
     * Afficher un bouton pour s'inscrire, mettre la classe en paramètre
     * Lui demander de remplir le formulaire comme pour la préinscription
     * Remplir YearData et Subscription avec les infos fournies
     * Envoyer une notif à l'admin
     */

    public function index()
    {
        $user = request()->user();
        if ($user->lesson === null) {
            $subscribed = false;
            $data = Lesson::all();
        } else {
            $subscribed = true;
            $data = $user->lesson;
        }

        return Inertia::render('User/Landing', compact('subscribed', 'data'));
    }

    public function create(Lesson $lesson)
    {
        $lessons = Lesson::all(['id', 'title'])->map(function($l) {
            return [
                'id' => $l->id,
                'title' => $l->title,
            ];
        });
        return Inertia::render('User/Subscription/Create', compact('lesson', 'lessons'));
    }

    public function store(SubscriptionRequest $request)
    {

    }
}
