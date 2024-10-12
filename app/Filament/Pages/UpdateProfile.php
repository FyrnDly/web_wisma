<?php

namespace App\Filament\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile;
use Illuminate\Support\Str;

class UpdateProfile extends EditProfile
{
    protected static ?string $slug = 'profile';

    public static function getLabel(): string
    {
        return Str::headline(__("Update Profile"));
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('username')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->rules('regex:/^[a-zA-Z0-9_]+$/'),
                Forms\Components\TextInput::make('name')
                    ->label(__("Name"))
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label(__("Email"))
                    ->email()
                    ->unique(ignoreRecord: true)
                    ->required()
                    // ->disable()
                    ->maxLength(255),
            ]);
    }

    protected function getRedirectUrl(): ?string
    {
        return "/";
    }
}
