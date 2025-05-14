<?php

namespace Backstage\Filament\Users\Pages\Auth;

use Backstage\Laravel\Users\Enums\NotificationType;
use Filament\Forms\Components\Select;
use Filament\Pages\Auth\EditProfile as BaseEditProfile;

class EditProfile extends BaseEditProfile
{
    protected function getForms(): array
    {
        return [
            'form' => $this->form(
                $this->makeForm()
                    ->schema([
                        $this->getNameFormComponent(),
                        $this->getEmailFormComponent(),
                        static::getNotificationFormComponent(),
                        $this->getPasswordFormComponent(),
                        $this->getPasswordConfirmationFormComponent(),
                    ])
                    ->operation('edit')
                    ->model($this->getUser())
                    ->statePath('data')
                    ->inlineLabel(! static::isSimple()),
            ),
        ];
    }

    public static function getNotificationFormComponent(): Select
    {
        $types = NotificationType::cases();

        $options = [];

        foreach ($types as $type) {
            $options[$type->value] = $type->label();
        }

        return Select::make('notification_preferences')
            ->label(__('Notification preferences'))
            ->options(fn () => $options)
            ->live()
            ->placeholder(fn () => ('Select notification preferences'))
            ->searchingMessage(__('Searching notification types...'))
            ->searchPrompt(__('Search notification types...'))
            ->preload()
            ->multiple();
    }

    public function saveNotificationPreferences()
    {
        $formState = $this->form->getState();

        $state = $formState['notification_preferences'];

        $state = collect($state)->map(fn ($value) => NotificationType::from($value));

        $record = $this->getUser();

        $state->each(function (NotificationType $type) use ($record) {
            if (! $record->notificationPreferences->contains('navigation_type', $type->value)) {

                $record->notificationPreferences()->create([
                    'navigation_type' => $type->value,
                ]);
            }
        });
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $data = parent::mutateFormDataBeforeFill($data);

        $user = $this->getUser();

        if ($user->notificationPreferences->isNotEmpty()) {
            $data['notification_preferences'] = $user->notificationPreferences->pluck('navigation_type')->map(fn (NotificationType $record) => $record->value)->toArray();
        }

        return $data;
    }
}
