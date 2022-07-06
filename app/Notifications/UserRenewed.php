<?php

namespace App\Notifications;

use App\Models\Lesson;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class UserRenewed extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(public string $lesson)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->from('nepasrepondre@alleedesfees.fr', "L'allée des Fées")
            ->subject('Votre réinscription a été validée')
            ->greeting('Bonjour,')
            ->line('Vous recevez cet email pour vous informer que votre réinscription a été validée par les administrateurs.')
            ->line('Vous êtes donc inscrit(e) au cours suivant :')
            ->line(new HtmlString('<p style="text-align: center; font-weight: bold; text-decoration: underline">' . $this->lesson . '.</p>'))
            ->line('Vous pouvez retrouver des informations complémentaires en vous rendant connectant à votre espace')
            ->action('Connexion', url(route('login')))
            ->line('Bonne journée !');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
