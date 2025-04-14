<?php

namespace Backstage\UserManagement\Notifications;

use Filament\Facades\Filament;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeEmail extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct() {}

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
        /**
         * Broker
         *
         * @var \Illuminate\Auth\Passwords\PasswordBroker $broker
         */
        $broker = app('auth.password.broker');

        $token = $broker->createToken($notifiable);
        $passwordResetUrl = Filament::getResetPasswordUrl($token, $notifiable);

        return (new MailMessage)
            ->subject(__('Welcome to Our Platform'))
            ->greeting(__('Hello :name!', ['name' => $notifiable->name]))
            ->line(__('We are excited to have you on board.'))
            ->action(__('Please configure youre password'), $passwordResetUrl)
            ->line(__('If you did not sign up, please ignore this email.'));
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
