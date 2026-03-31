<?php

namespace Backstage\Filament\Users\Notifications;

use Backstage\Filament\Users\Actions\GenerateSignedRegistrationUri;
use Backstage\Filament\Users\Pages\RegisterFromInvitationPage;
use Backstage\Laravel\Users\Eloquent\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserInvitationNotification extends Notification
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
    public function via(User $notifiable): array
    {
        return config('users.events.auth.user_created.notification_delivery_channels', ['mail']);
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(User $notifiable): MailMessage
    {
        /**
         * Mask the $dedicatedPanel variable as string to get the route name for the RegisterFromInvitationPage.
         *
         * @var string $dedicatedPanel
         */
        $url = GenerateSignedRegistrationUri::run(user: $notifiable);

        return (new MailMessage)
            ->subject(__('Welcome to :appName', ['appName' => config('app.name', 'Backstagephp')]))
            ->greeting(__('Hello :name!', ['name' => $notifiable->getAttribute('name')]))
            ->line(__('We are excited to have you on board.'))
            ->action(__('Register'), $url)
            ->line(__('If you did not sign up, request this invitation, or expect to receive it, please ignore this email.'));
    }

    public function toArray(User $notifiable): array
    {
        return [];
    }
}
