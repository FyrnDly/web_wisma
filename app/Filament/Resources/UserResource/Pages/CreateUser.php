<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Resources\Pages\CreateRecord;
use Filament\Actions;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Notifications\NewUserNotification;
use App\Models\User;


class CreateUser extends CreateRecord
{
    protected static string $resource = UserResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $username = $data['username'];
        $full_name = $data['name'];
        $email = $data['email'];
        $password = Str::random(12);

        $data['password'] = Hash::make($password);
        $data['verified'] = TRUE;

        $temporaryUser = (new User)->forceFill([
            'username' => $username,
            'name' => $full_name,
            'email' => $email,
        ]);

        $temporaryUser->notify(new NewUserNotification(
            $username,
            $full_name,
            $email,
            $password
        ));

        return $data;
    }
}
