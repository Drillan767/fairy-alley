<?php

namespace App\Notifications;

use App\Models\Lesson;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class LessonChangedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(public Lesson $lesson)
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
            ->from('nepasrepondre@alleedesfees.fr')
            ->subject('Modification de votre cours')
            ->greeting('Bonjour,')
            ->line('Vous recevez cet email car le cours où vous étiez inscrit a été changé.')
            ->line('Le nouveau est désormais :')
            ->line(new HtmlString("<b>{$this->lesson->title}</b>."))
            ->line("Si vous pensez qu'il s'agit d'une erreur, veuillez contacter les organisateurs du cours.")
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
