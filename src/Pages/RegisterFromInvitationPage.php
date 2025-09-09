<?php

namespace Backstage\Filament\Users\Pages;

use BackedEnum;
use Backstage\Filament\Users\Models\User;
use Backstage\Filament\Users\Pages\RegisterFromInvitationPage\RedirectUrlAfterRegistration;
use Filament\Actions\Action;
use Filament\Auth\Events\Registered;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Pages\Auth\Register;
use Filament\Pages\Concerns\CanUseDatabaseTransactions;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;
use Filament\Panel;
use Filament\Schemas\Components\Component;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Contracts\HasSchemas;
use Filament\Schemas\Schema;
use Filament\Support\Colors\Color;
use Filament\Support\Enums\Alignment;
use Filament\Support\Enums\Width;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\HtmlString;
use Illuminate\Validation\Rules\Password;

class RegisterFromInvitationPage extends Page implements HasSchemas
{
    use CanUseDatabaseTransactions;
    use InteractsWithFormActions;
    use InteractsWithSchemas;

    protected static ?string $slug = '/{userId}';

    public function getView(): string
    {
        return 'backstage/users::pages.register';
    }

    protected static string $layout = 'filament-panels::components.layout.simple';

    public $userModel = null;

    public User $user;

    public array $data = [
        'name' => '',
        'email' => '',
        'password' => '',
        'passwordConfirmation' => '',
    ];

    public function mount($userId = null): void
    {
        $userId = decrypt($userId);

        $this->user = $this->getUserModel()::withoutGlobalScopes()->findOrFail($userId);

        $this->checkUserVerification();

        $this->form->fill($this->user->toArray());
    }

    protected function checkUserVerification(): void
    {
        if ($this->user->email_verified_at) {
            abort(403, __('You have already registered.'));
        }

        if (Filament::auth()->check()) {
            Filament::auth()->logout();
        }
    }

    public static function hasLogo()
    {
        return true;
    }

    public function getTitle(): string | Htmlable
    {
        return __('Welcome :userName', ['userName' => $this->user->name]);
    }

    protected function getLayoutData(): array
    {
        return [
            'hasTopbar' => false,
        ];
    }

    public function getMaxContentWidth(): Width | string | null
    {
        return Width::TwoExtraLarge;
    }

    public static function getRouteMiddleware(Panel $panel): string | array
    {
        return ['signed'];
    }

    public function form(Schema $form)
    {
        return $form
            ->statePath('data')
            ->schema([
                $this->getNameFormComponent(),

                $this->getEmailFormComponent(),

                $this->getPasswordFormComponent(),

                $this->getPasswordConfirmationFormComponent(),

                $this->getRegisterActionFormComponent(),
            ])
            ->columns(1);
    }

    protected function getNameFormComponent(): Component
    {
        return TextInput::make('name')
            ->disabled()
            ->hintAction($this->getExplanationAction())
            ->prefixIcon(fn (): BackedEnum => Heroicon::OutlinedUserCircle, fn (): bool => true)
            ->prefixIconColor(fn (): ?array => static::getDefaultPanelColor())
            ->label(__('filament-panels::auth/pages/register.form.name.label'));
    }

    protected function getEmailFormComponent(): Component
    {
        return TextInput::make('email')
            ->disabled()
            ->hintAction($this->getExplanationAction())
            ->prefixIcon(fn (): BackedEnum => Heroicon::OutlinedEnvelope, fn (): bool => true)
            ->prefixIconColor(fn (): ?array => static::getDefaultPanelColor())
            ->label(__('filament-panels::auth/pages/register.form.email.label'));
    }

    protected function getPasswordFormComponent(): Component
    {
        return TextInput::make('password')
            ->label(__('filament-panels::auth/pages/register.form.password.label'))
            ->password()
            ->revealable(filament()->arePasswordsRevealable())
            ->required()
            ->rule(Password::default())
            ->dehydrateStateUsing(fn ($state) => Hash::make($state))
            ->same('passwordConfirmation')
            ->live()
            ->prefixIcon(fn (TextInput $component): BackedEnum => Heroicon::LockClosed, fn (): bool => true)
            ->prefixIconColor(fn (): ?array => static::getDefaultPanelColor())
            ->validationAttribute(__('filament-panels::auth/pages/register.form.password.validation_attribute'));
    }

    protected function getPasswordConfirmationFormComponent(): Component
    {
        return TextInput::make('passwordConfirmation')
            ->label(__('filament-panels::auth/pages/register.form.password_confirmation.label'))
            ->password()
            ->revealable(filament()->arePasswordsRevealable())
            ->required()
            ->rule(Password::default())
            ->same('password')
            ->live()
            ->prefixIcon(fn (TextInput $component): BackedEnum => Heroicon::LockClosed, fn (): bool => true)
            ->prefixIconColor(fn (): ?array => static::getDefaultPanelColor())
            ->dehydrated(false);
    }

    protected function getRegisterActionFormComponent(): Action
    {
        return Action::make('hi')
            ->label(__('Register'))
            ->color(fn (): ?array => static::getDefaultPanelColor())
            ->action(fn () => $this->register());
    }

    protected static function getDefaultPanelColor(): array
    {
        return Filament::getDefaultPanel()->getColors()['primary'] ?? Color::Blue;
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

    protected function hasFullWidthFormActions(): bool
    {
        return true;
    }

    // Register
    public function register()
    {
        $user = $this->wrapInDatabaseTransaction(function () {
            $this->callHook('beforeValidate');

            $data = $this->form->getState();

            $this->callHook('afterValidate');

            $data = $this->mutateFormDataBeforeRegister($data);

            $this->callHook('beforeRegister');

            $user = $this->handleRegistration($data);

            $this->form->model($user)->saveRelationships();

            $this->callHook('afterRegister');

            return $user;
        });

        event(new Registered($user));

        $redirectUrl = RedirectUrlAfterRegistration::get($user);

        return $this->redirect($redirectUrl ?: url('/'), [
            'success' => __('You have successfully registered.'),
        ]);
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

    protected function getExplanationAction(): Action
    {
        return Action::make('explanation')
            ->label(__('Explanation'))
            ->icon(fn (): BackedEnum => Heroicon::OutlinedInformationCircle)
            ->color('secondary')
            ->size('sm')
            ->modal()
            ->modalWidth(fn (): Width => Width::TwoExtraLarge)
            ->modalIcon(fn (): BackedEnum => Heroicon::InformationCircle)
            ->modalHeading(__('Explanation'))
            ->modalDescription(fn (): ?Htmlable => new HtmlString(__('Information about the registration process.')))
            ->modalContent(fn (): ?Htmlable => new HtmlString(__(
                'You are registering as :userName with the email :email. This is because you were invited to join our platform. Your account will be created with the following details: <br>' .
                    '<strong>Name:</strong> :userName <br>' .
                    '<strong>Email:</strong> :email <br><br>',
                [
                    'userName' => $this->user->name,
                    'email' => $this->user->email,
                ]
            )))
            ->modalContentFooter(fn (): ?Htmlable => new HtmlString(__('This can later be changed in your profile settings.')))
            ->modalFooterActionsAlignment(Alignment::Center)
            ->modalCancelAction(fn (Action $action): Action => $action->label(__('Close')))
            ->modalSubmitAction(fn (Action $action): Action => $action->hidden());
    }
}
