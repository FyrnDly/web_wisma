<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('verify')
                ->color('success')
                ->label('Verifkasi')
                ->icon('heroicon-m-check-badge')
                ->iconPosition('after')
                ->action(function ($record) {
                    $record->verified = true;
                    $record->save();
                    Notification::make()
                        ->title('User Berhasil Diverifikasi')
                        ->success()
                        ->send();
                })
                ->requiresConfirmation()
                ->modalIcon('heroicon-m-check-badge')
                ->modalHeading('Verifikasi Identitas Pengguna')
                ->modalDescription('Pengguna yang sudah diverifkasi tidak bisa dibatalkan. Apakah Anda yakin?')
                ->hidden(fn ($record) => $record->verified),
            Actions\EditAction::make(),
        ];
    }
}
