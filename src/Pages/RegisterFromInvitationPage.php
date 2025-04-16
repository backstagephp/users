<?php

namespace Backstage\Users\Pages;

use Backstage\Users\Models\User;
use Filament\Actions\Action;
use Filament\Events\Auth\Registered;
use Filament\Facades\Filament;
use Filament\Forms\Components\Component;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Pages\Auth\Register;
use Filament\Pages\Concerns\CanUseDatabaseTransactions;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class RegisterFromInvitationPage extends Page implements HasForms
{
    use CanUseDatabaseTransactions;
    use InteractsWithFormActions;
    use InteractsWithForms;

    protected static ?string $slug = '/{userId}';

    protected static string $view = 'backstage/users::pages.register';

    protected static string $layout = 'filament-panels::components.layout.simple';

    public $userModel = null;

    public User $user;

    public array $data = [];

    public function getTitle(): string | Htmlable
    {
        return __('filament-panels::pages/auth/register.title');
    }

    public function mount($userId = null): void
    {
        $userId = decrypt($userId);

        $this->user = app($this->getUserModel())::withoutGlobalScopes()->findOrFail($userId);

        $this->data = [
            'name' => $this->user->name,
            'email' => $this->user->email,
            'password' => '',
            'passwordConfirmation' => '',
        ];
    }

    public static function hasLogo()
    {
        return true;
    }

    public function getForms(): array
    {
        return [
            'registerForm' => $this->makeForm()
                ->model($this->getUserModel())
                ->schema([
                    $this->getNameFormComponent(),
                    $this->getEmailFormComponent(),
                    $this->getPasswordFormComponent(),
                    $this->getPasswordConfirmationFormComponent(),
                ])
                ->statePath('data'),

        ];
    }

    protected function getNameFormComponent(): Component
    {
        return TextInput::make('name')
            ->label(__('filament-panels::pages/auth/register.form.name.label'))
            ->required()
            ->disabled()
            ->maxLength(255)
            ->autofocus();
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->label(__('filament-panels::pages/auth/register.form.email.label'))
            ->email()
            ->disabled()
            ->required()
            ->maxLength(255);
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label(__('filament-panels::pages/auth/register.form.password.label'))
            ->password()
            ->revealable(filament()->arePasswordsRevealable())
            ->required()
            ->rule(Password::default())
            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
            ->same('passwordConfirmation')
            ->validationAttribute(__('filament-panels::pages/auth/register.form.password.validation_attribute'));
    }

    protected function getPasswordConfirmationFormComponent(): Component
    {
        return TextInput::make('passwordConfirmation')
            ->label(__('filament-panels::pages/auth/register.form.password_confirmation.label'))
            ->password()
            ->revealable(filament()->arePasswordsRevealable())
            ->required()
            ->dehydrated(false);
    }

    protected function getUserModel(): string
    {
        if (isset($this->userModel)) {
            return $this->userModel;
        }

        /** @var SessionGuard $authGuard */
        $authGuard = Filament::auth();

        /** @var EloquentUserProvider $provider */
        $provider = $authGuard->getProvider();

        return $this->userModel = $provider->getModel();
    }

    protected function getFormActions(): array
    {
        return [
            $this->getRegisterFormAction(),
        ];
    }

    public function getRegisterFormAction(): Action
    {
        return Action::make('register')
            ->label(__('filament-panels::pages/auth/register.form.actions.register.label'))
            ->submit('register');
    }

    protected function hasFullWidthFormActions(): bool
    {
        return true;
    }

    // Register
    public function register()
    {
        $user = $this->wrapInDatabaseTransaction(function () {
            $this->callHook('beforeValidate');

            $data = $this->registerForm->getState();

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeRegister($data);

            $this->callHook('beforeRegister');

            $user = $this->handleRegistration($data);

            $this->registerForm->model($user)->saveRelationships();

            $this->callHook('afterRegister');

            return $user;
        });

        event(new Registered($user));

        $defaultPanel = Filament::getDefaultPanel();

        return $this->redirect($defaultPanel->getUrl());
    }

    protected function handleRegistration(array $data): Model
    {
        $this->user->password = $data['password'];
        $this->user->email_verified_at = now();

        $this->user->save();

        return $this->user;
    }

    /**
     * @param  array<string, mixed>  $data
     * @return array<string, mixed>
     */
    protected function mutateFormDataBeforeRegister(array $data): array
    {
        return $data;
    }
}
