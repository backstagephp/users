<?php

namespace Backstage\Filament\Users\Resources\UserResource;

use BackedEnum;
use Backstage\Filament\Users\Concerns\Resources\HasSubNavigationPosition;
use Backstage\Filament\Users\Exports\UserExporter;
use Backstage\Filament\Users\Imports\UserImporter;
use Backstage\Filament\Users\Models\User;
use Backstage\Filament\Users\Resources\UserResource\Schemas\UserForm;
use Backstage\Filament\Users\Resources\UserResource\Schemas\UserInfolist;
use Backstage\Filament\Users\UsersPlugin;
use Backstage\Filament\Users\Widgets\StatsOverviewWidget;
use Backstage\Laravel\Users\Eloquent\Scopes\VerifiedUser;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ExportAction;
use Filament\Actions\ImportAction;
use Filament\Actions\ViewAction;
use Filament\Facades\Filament;
use Filament\Navigation\NavigationGroup;
use Filament\Pages\Enums\SubNavigationPosition;
use Filament\Panel;
use Filament\Resources\Pages\Page;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Database\Eloquent\Builder;

class UserResource extends Resource
{
    use HasSubNavigationPosition;

    public static function getSlug(?Panel $panel = null): string
    {
        return 'users';
    }

    public static function canAccess(): bool
    {
        return UsersPlugin::get()->canManageUsersCondition();
    }

    public static function getModel(): string
    {
        return \App\Models\User::class;
    }

    public static function getRecordTitleAttribute(): ?string
    {
        return 'name';
    }

    public static function getNavigationGroup(): ?string
    {
        return __('User management');
    }

    public static function getNavigationIcon(): string | BackedEnum | Htmlable | null
    {
        return Heroicon::OutlinedUsers;
    }

    public static function getActiveNavigationIcon(): string | BackedEnum | Htmlable | null
    {
        return Heroicon::Users;
    }

    public static function getEloquentQuery(): Builder
    {
        return static::getModel()::query()
            ->withoutGlobalScopes([
                VerifiedUser::class,
            ]);
    }

    public static function form(Schema $form): Schema
    {
        return UserForm::configure($form);
    }

    public static function infolist(Schema $schema): Schema
    {
        return UserInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->headerActions([
                ImportAction::make('import')
                    ->importer(UserImporter::class)
                    ->color('primary'),

                ExportAction::make()
                    ->exporter(UserExporter::class),
            ])
            ->columns([
                Tables\Columns\ImageColumn::make('avatar')
                    ->label(__('Avatar'))
                    ->circular()
                    ->alignCenter()
                    ->getStateUsing(fn (User $record): ?string => Filament::getUserAvatarUrl($record)),

                Tables\Columns\TextColumn::make('name')
                    ->label(__('Name'))
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label(__('Email'))
                    ->sortable()
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),

                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRecordSubNavigation(Page $page): array
    {
        return [
            ...$page->generateNavigationItems([
                Pages\ViewUser::class,
                Pages\EditUser::class,
            ]),

            NavigationGroup::make(__('Relations'))
                ->icon(fn (): ?BackedEnum => static::getSubNavigationPosition() === SubNavigationPosition::Top ? Heroicon::Link : null)
                ->items([
                    ...$page->generateNavigationItems([
                        Pages\ManageRoles::class,
                    ]),
                ]),
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
            'manage-roles' => Pages\ManageRoles::route('/{record}/roles'),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            StatsOverviewWidget::class,
        ];
    }
}
