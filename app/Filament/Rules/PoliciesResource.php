<?php

namespace App\Filament\Rules;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Resource;


class PoliciesResource extends Resource
{
    public static function canViewAny(): bool {
        return Auth::user()->hasRoles(['admin','root']);
    }

    public static function canForceDelete(Model $record): bool
    {
        return Auth::user()->hasRoles(['root']);
    }

    public static function canForceDeleteAny(): bool
    {
        return Auth::user()->hasRoles(['root']);
    }
    
    public static function canRestore(Model $record): bool
    {
        return Auth::user()->hasRoles(['root']);
    }
    
    public static function canRestoreAny(): bool
    {
        return Auth::user()->hasRoles(['root']);
    }
}