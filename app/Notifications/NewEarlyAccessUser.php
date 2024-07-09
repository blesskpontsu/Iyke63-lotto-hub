<?php

namespace App\Notifications;

use App\Models\EarlyUser;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class NewEarlyAccessUser extends Notification implements ShouldQueue
{
    use Queueable, InteractsWithQueue;

    /**
     * Create a new notification instance.
     */
    public EarlyUser $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('New Early Access Registration')
            ->greeting("Thank You for Registering for Early Access to Iyke63")
            ->line("Hello {$this->user->firstname}, Thank you for registering for early access to Iyke63 Lotto Hub, our innovative lottery prediction service! We are thrilled to have you on board and excited to share our product with you.")
            ->line('You will be receiving an email with registration links to access our application in the next days')
            ->line('Thank you for using Iyke63 Lotto Hub!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
