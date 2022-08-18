<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class RevivalReport extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(public array $users, public string $type)
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
            $list .= "<li>{$user['lastname']} {$user['firstname']} - ({$user['email']})</li>";
        }
        $list .= '</ul>';

        $action = $this->type === 'unpaid'
            ? 'le paiement est manquant ou incomplet'
            : 'la démarche de réinscription n\'a pas encore été faite'
            ;

        return (new MailMessage)
            ->from('nepasrepondre@alleedesfees.fr', "L'allée des Fées")
            ->subject('Relance des utilisateurs terminées')
            ->greeting('Bonjour,')
            ->line(new HtmlString("Vous avez demandé la relance des utilisateurs dont <b>$action</b>."))
            ->line('Un email de relance a donc été envoyé aux utilisateurs suivants :')
            ->line(new HtmlString($list))
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
