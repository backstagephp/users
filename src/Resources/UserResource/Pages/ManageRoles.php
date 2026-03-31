<?php

namespace Backstage\Filament\Users\Resources\UserResource\Pages;

use BackedEnum;
use Backstage\Filament\Users\Resources\RoleResource\RoleResource;
use Backstage\Filament\Users\Resources\UserResource\UserResource;
use Filament\Actions\AttachAction;
use Filament\Actions\CreateAction;
use Filament\Actions\DetachAction;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\HtmlString;

class ManageRoles extends ManageRelatedRecords
{
    protected static string $resource = UserResource::class;

    protected static string $relationship = 'roles';

    protected static string | BackedEnum | null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $relatedResource = RoleResource::class;

    public static function getNavigationIcon(): string | BackedEnum | Htmlable | null
    {
        return RoleResource::getNavigationIcon();
    }

    public static function getActiveNavigationIcon(): string | BackedEnum | Htmlable | null
    {
        return RoleResource::getActiveNavigationIcon();
    }

    public function table(Table $table): Table
    {
        return $table
            ->heading(fn (): Htmlable => new HtmlString(__('Roles of :name', ['name' => Blade::render('<x-filament::badge>' . $this->getRecordTitle() . '</x-filament::badge>')])))
            ->headerActions([
                AttachAction::make()
                    ->preloadRecordSelect()
                    ->recordTitleAttribute('name')
                    ->hidden(fn () => ! static::getResource()::canEdit($this->getOwnerRecord()))
                    ->multiple(),

                CreateAction::make(),
            ])
            ->pushRecordActions([
                DetachAction::make(),
            ]);
    }
}
