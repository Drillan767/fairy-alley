<?php

namespace App\Notifications;

use App\Models\Service;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ServiceAsked extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(public int $userId, public int $serviceId)
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
        $user = User::select(['firstname', 'lastname'])->find($this->userId);
        $service = Service::select(['id'])->find($this->serviceId);

        return (new MailMessage)
            ->from('nepasrepondre@alleedesfees.fr', "L'allée des Fées")
            ->subject('Nouvelle demande pour un service')
            ->greeting('Bonjour,')
            ->line("L'utilisateur/trice $user->full_name a demandé à s'inscrire au service intitulé $service->title.")
            ->line('Pour valider ou non son inscription, veuillez vous rendre dans la partis souscriptions des services, à l\'aide du lien ci-dessous :')
            ->action('Souscriptions', route('services.subscriptions.index'))
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
