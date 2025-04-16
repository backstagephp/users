<?php

namespace Backstage\Users\Pages;

use Filament\Actions\Action;
use Filament\Facades\Filament;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Concerns\HasRoutes;
use Filament\Pages\Concerns\InteractsWithFormActions;
use Filament\Pages\Page;
use Filament\Tables\Actions\CreateAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class ManageApiTokens extends Page implements HasTable
{
    use InteractsWithTable;
    use InteractsWithForms;
    use InteractsWithFormActions;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'backstage/users::pages.manage-api-tokens';

    public array $data = [];

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function canAccess(): bool
    {
        return config('backstage.users.record.manage-api-tokens', false);
    }

    protected function getTableQuery(): Builder|Relation|null
    {
        return Filament::auth()->user()->tokens()->getQuery();
    }

    public function getFormActions(): array
    {
        return [
            $this->getSubmitFormActions()
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

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label(__('Name'))
                    ->required()
                    ->maxLength(255)
                    ->placeholder(__('Enter token name')),
            ])
            ->statePath('data');
    }


    public function create()
    {
        $state = $this->form->getState();

        $token = Filament::auth()->user()->createToken($state['name']);

        Notification::make()
            ->title(__('Token created'))
            ->body(__('Please save this token:: :token', ['token' => $token->plainTextToken]))
            ->success()
            ->persistent()
            ->send();

        $this->form->fill();
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
