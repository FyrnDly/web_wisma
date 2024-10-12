<?php

namespace App\Filament\Pages;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Pages\Auth\EditProfile;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Closure;


class UpdatePassword extends EditProfile
{
    public static function getLabel(): string
    {
        return Str::headline(__("Reset Password"));
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('current_password')
                    ->password()
                    ->label(Str::headline(__("validation.attributes.current_password")))
                    ->required()
                    ->rules([
                        fn (): Closure => function (string $attribute, $value, Closure $fail) {
                            if (!Hash::check($value, auth()->user()->password)) {
                                $fail(__("validation.current_password"));
                            }
                        },
                    ])
                    ->validationAttribute('current password'),
                Forms\Components\TextInput::make('password')
                    ->password()
                    ->label(Str::headline(__("New Password")))
                    ->revealable(filament()->arePasswordsRevealable())
                    ->rule(Password::default())
                    ->autocomplete('new-password')
                    ->dehydrated(fn ($state): bool => filled($state))
                    ->dehydrateStateUsing(fn ($state): string => Hash::make($state))
                    ->live(debounce: 500)
                    ->same('passwordConfirmation'),
                Forms\Components\TextInput::make('passwordConfirmation')
                    ->label(Str::headline(__("validation.attributes.password_confirmation")))
                    ->password()
                    ->revealable(filament()->arePasswordsRevealable())
                    ->required()
                    ->dehydrated(false),
            ]);
    }

    protected function getRedirectUrl(): ?string
    {
        return "/";
    }
}