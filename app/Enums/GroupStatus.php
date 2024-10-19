<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum GroupStatus: string implements HasColor, HasLabel
{
    case User = 'viewer';
    case Admin = 'admin';
    case Master = 'root';

    public function getName(): string
    {
        return match ($this) {
            self::User => 'viewer',
            self::Admin => 'admin',
            self::Master => 'root',
        };
    }

    public function getLabel(): string
    {
        return match ($this) {
            self::User => 'Staff',
            self::Admin => 'Admin',
            self::Master => 'Super Admin',
        };
    }

    public function getColor(): string | array | null
    {
        return match ($this) {
            self::User => 'gray',
            self::Admin => 'warning',
            self::Master => 'info',
        };
    }
}
