<?php

namespace Backstage\Filament\Users\Pages;

use BackedEnum;
use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;
use Filament\Schemas\Concerns\InteractsWithSchemas;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class ManageApiTokens extends Page implements HasTable
{
    use InteractsWithFormActions;
    use InteractsWithSchemas;
    use InteractsWithTable;

    public static function getNavigationIcon(): string | BackedEnum | Htmlable | null
    {
        return Heroicon::OutlinedDocumentText;
    }

    public string $view = 'backstage/users::pages.manage-api-tokens';

    public array $data = [];

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function canAccess(): bool
    {
        return config('backstage.users.record.manage-api-tokens', false);
    }

    protected function getTableQuery(): Builder | Relation | null
    {
        return Filament::auth()->user()->tokens()->getQuery();
    }

    public function getFormActions(): array
    {
        return [
            $this->getSubmitFormActions(),
        ];
    }

    public function getSubmitFormActions(): Action
    {
        return Action::make('submit')
            ->label(__('Create'))
            ->action('create')
            ->color('primary')
            ->icon('heroicon-o-plus')
            ->requiresConfirmation();
    }

    public function content(Schema $schema): Schema
    {
        return $schema
            ->schema([
                TextInput::make('name')
                    ->label(__('Name'))
                    ->required()
                    ->maxLength(255)
                    ->placeholder(__('Enter token name')),

                $this->getSubmitFormActions(),
            ])
            ->statePath('data');
    }

    public function create()
    {
        $state = $this->content->getState();

        /**
         * @var \Backstage\Filament\Users\Models\User $user
         */
        $user = Filament::auth()->user();

        $token = $user->createToken($state['name']);

        Notification::make()
            ->title(__('Token created'))
            ->body(__('Please save this token: :token', ['token' => $token->plainTextToken]))
            ->success()
            ->persistent()
            ->send();

        $this->content->fill();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query($this->getTableQuery())
            ->columns([
                TextColumn::make('name')
                    ->label(__('Name'))
                    ->sortable()
                    ->searchable()
                    ->placeholder(__('Enter token name')),

                TextColumn::make('created_at')
                    ->label(__('Created'))
                    ->sortable()
                    ->dateTime()
                    ->since()
                    ->placeholder(__('Enter token name')),
            ]);
    }
}
