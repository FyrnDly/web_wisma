<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DeviceResource\Pages;
use App\Filament\Resources\DeviceResource\RelationManagers;
use App\Filament\Rules\PoliciesResource as Resource;
use App\Models\Device;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DeviceResource extends Resource
{
    protected static ?string $model = Device::class;

    protected static ?string $label = 'Perangkat IoT';
    protected static ?string $navigationIcon = 'heroicon-o-cloud';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->label('Nama Perangkat')
                    ->maxLength(255),
                Forms\Components\TextInput::make('mac_address')
                    ->label('Mac Address')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Select::make('type')
                    ->options([
                        'beacon' => 'Beacon',
                        'smart mirror' => 'Smart Mirror',
                    ])->required()
                    ->label('Tipe')
                    ->native(false),
                Forms\Components\Hidden::make('created_by')
                    ->required()->default(fn()=> Auth::user()->id),
            ])->columns([
                'md' => 1,
                'xl' => 3,
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\Section::make([
                    Infolists\Components\TextEntry::make('name')->label('Nama Perangkat'),
                    Infolists\Components\TextEntry::make('mac_address')->label('Mac Address Perangkat'),
                    Infolists\Components\TextEntry::make('type')->getStateUsing(fn($record)=>match ($record->type) {
                        'beacon' => 'Beacon',
                        'smart mirror' => 'Smart Mirror',
                    })->label('Tipe Perangkat')->badge(),
                ])->columns([
                    'md' => 2,
                    'xl' => 3,
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->label('Nama Perangkat'),
                Tables\Columns\TextColumn::make('mac_address')
                    ->searchable()
                    ->copyable()->copyMessageDuration(1500)
                    ->icon('heroicon-o-clipboard-document')
                    ->iconPosition('after')
                    ->label('Mac Address')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('type')
                    ->label('Tipe')
                    ->getStateUsing(fn($record)=>match ($record->type) {
                        'beacon' => 'Beacon',
                        'smart mirror' => 'Smart Mirror',
                    })->badge()
                    ->alignCenter()
                    ->sortable(),
                Tables\Columns\TextColumn::make('username')
                    ->label('Dibuat Oleh')
                    ->alignCenter()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->color('info'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageDevices::route('/'),
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
