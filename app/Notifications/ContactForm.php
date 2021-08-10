<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class ContactForm extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(public array $data)
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
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->greeting('Bonjour')
            ->line('Vous venez de recevoir un message provenant du formulaire de contact du site.')
            ->line('Voici les détails :')
            ->line(new HtmlString('<b>Nom :</b>'))
            ->line($this->data['name'])
            ->line(new HtmlString('<b>Adresse email :</b>'))
            ->line($this->data['email'])
            ->line(new HtmlString('<b>Objet :</b>'))
            ->line($this->data['subject'])
            ->line(new HtmlString('<b>Message :</b>'))
            ->line(nl2br($this->data['message']))
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
