<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-m-user-group';
    protected static ?string $label = 'Manajemen Akun';

    public static function canViewAny(): bool {
        return Auth::user()->hasRoles(['root']);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Nama Pengguna')
                    ->required()->maxLength(255),
                Forms\Components\TextInput::make('username')
                    ->required()->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->label('Alamat Email')
                    ->email()->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('roles')->label('Kelompok Pengguna'),
                Forms\Components\Textarea::make('address')
                    ->label('Alamat Pengguna')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama Pengguna')->searchable(),
                Tables\Columns\TextColumn::make('username')
                    ->label('Alamat Email')->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Alamat Email')
                    ->icon(function ($record) {
                        return $record->email_verified_at ? 'heroicon-m-check-badge' : 'heroicon-m-x-circle';
                    })
                    ->iconColor(function ($record) {
                        return $record->email_verified_at ? 'success' : 'danger';
                    })
                    ->copyable()
                    ->copyMessage('Email address copied')
                    ->copyMessageDuration(1500)
                    ->searchable(),
                Tables\Columns\IconColumn::make('verified')
                    ->label('Status Pengguna')
                    ->alignCenter()->boolean(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
                Tables\Filters\TernaryFilter::make('email_verified_at')
                    ->label('Status Verifikasi Email Pengguna')
                    ->placeholder('Semua Pengguna')
                    ->trueLabel('Email Terverifikasi')
                    ->falseLabel('Email Belum Terverifikasi')
                    ->queries(
                        true: fn (Builder $query) => $query->whereNotNull('email_verified_at'),
                        false: fn (Builder $query) => $query->whereNull('email_verified_at'),
                        blank: fn (Builder $query) => $query,
                    ),
                Tables\Filters\TernaryFilter::make('verified')
                    ->label('Status Pengguna')
                    ->placeholder('Semua Pengguna')
                    ->trueLabel('Pengguna Terverifikasi')
                    ->falseLabel('Belum Terverifikasi')
                    ->queries(
                        true: fn (Builder $query) => $query->where('verified', true),
                        false: fn (Builder $query) => $query->where('verified', false),
                        blank: fn (Builder $query) => $query,
                    ),
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make()->color('info'),
                    Tables\Actions\EditAction::make()->color('warning'),
                    Tables\Actions\DeleteAction::make()->hidden(fn($record) => $record->id == Auth::user()->id),
                ])->color('gray')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\ForceDeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
                ]),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make([
                    Infolists\Components\TextEntry::make('name')->label('Nama Pengguna'),
                    Infolists\Components\TextEntry::make('username')->label('username'),
                    Infolists\Components\TextEntry::make('email')
                        ->label('Alamat Email')
                        ->copyable()
                        ->copyMessageDuration(1500)
                        ->copyMessage('Email address copied')
                        ->icon(function ($record) {
                            return $record->email_verified_at ? 'heroicon-m-check-badge' : 'heroicon-m-x-circle';
                        })
                        ->iconColor(function ($record) {
                            return $record->email_verified_at ? 'success' : 'danger';
                        }),
                    Infolists\Components\TextEntry::make('roles')
                        ->label('Kelompok Pengguna')
                        ->default('Belum Ditambahkan')
                        ->badge()->color('gray'),
                    Infolists\Components\TextEntry::make('verified')
                        ->label('Status Pengguna')
                        ->badge()
                        ->getStateUsing(fn ($record) => $record->verified ? 'Terverifikasi' : 'Belum Terverifikasi')
                        ->color(fn (string $state): string => match ($state){
                            'Terverifikasi' => 'success',
                            'Belum Terverifikasi' => 'danger',
                        }),
                    Infolists\Components\TextEntry::make('address')
                        ->label('Alamat Pengguna')
                        ->default("Belum Ditambahkan")
                        ->columnSpanFull(),
                ])->columns([
                    'md' => 2,
                    'xl' => 3,
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
