<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubscriptionMissingElements extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(public array $subscription)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via(mixed $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(): MailMessage
    {
        return (new MailMessage)
            ->subject("Votre dossier d'inscription est incomplet")
            ->greeting('Bonjour,')
            ->line("Vous recevez ce message car votre dossier d'inscription est incomplet.")
            ->line("L'organisatrice a indiqué ceci :")
            ->line($this->subscription['feedback'])
            ->line('Pour le modifier, vous pouvez cliquer sur le lien ci-dessous')
            ->action("Mettre à jour ma demande d'inscription", url(route('subscription.edit', ['lesson' => $this->subscription['lesson_id']])))
            ->line('Bien cordialement,');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray(mixed $notifiable): array
    {
        return [
            //
        ];
    }
}
