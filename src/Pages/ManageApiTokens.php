<?php

namespace Backstage\Users\Pages;

use Filament\Pages\Page;

class ManageApiTokens extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static string $view = 'backstage/users::pages.manage-api-tokens';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function canAccess(): bool
    {
        return config('backstage.users.record.manage-api-tokens', false);
    }
}
