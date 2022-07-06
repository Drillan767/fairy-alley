<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class UserRenewalFeedback extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(public array $users)
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
        $list = '<ul>';
        foreach ($this->users as $user) {
            $list .= "<li>{$user['lastname']} {$user['firstname']}</li>";
        }
        $list .= '</ul>';

        return (new MailMessage)
            ->from('nepasrepondre@alleedesfees.fr', "L'allée des Fées")
            ->subject('Réinscription des utilisateurs terminées')
            ->greeting('Bonjour,')
            ->line('Vous avez validé la réinscription des utilisateurs suivants :')
            ->line(new HtmlString($list))
            ->line('Cet email a été envoyé pour vous indiquer que la réinscription de ces utilisateurs est terminée.')
            ->line('Bonne journée,')
           ;
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
