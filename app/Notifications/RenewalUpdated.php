<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RenewalUpdated extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(public string $user, public int $userId)
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
            ->subject('Un utilisateur a mis à jour sa réinscription.')
            ->greeting('Bonjour,')
            ->line("Vous recevez cet email car $this->user a mis à jour les informations de sa réinscription.")
            ->line('Veuillez cliquer sur le lien ci-dessous pour y accéder :')
            ->line('The introduction to the notification.')
            ->action('Accéder aux informations', route('utilisateur.renewal.show', ['user' => $this->userId]))
            ->line('En vous souhaitant une excellente journée.');
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
