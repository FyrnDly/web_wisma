<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Room;
use Filament\Tables;
use Filament\Infolists;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Infolists\Components\Tabs;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\RoomResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\RoomResource\RelationManagers;

class RoomResource extends Resource
{
    protected static ?string $model = Room::class;

    protected static ?string $label = 'Kamar';
    protected static ?string $navigationIcon = 'heroicon-o-circle-stack';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('code')
                    ->label('Kode Kamar')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->label('Nama Kamar')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Textarea::make('description')
                    ->label('Deskripsi Kamar')
                    ->rows(3)
                    ->columnSpanFull(),
                Forms\Components\Hidden::make('created_by')
                    ->default(Auth::user()->id),
            ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Tabs::make('Detail')
                ->contained(false)
                ->columnSpanFull()
                ->tabs([
                    Tabs\Tab::make('Informasi Kamar')
                        ->schema(static::infoRoom()),
                    Tabs\Tab::make('Sistem Navigasi')
                        ->schema(static::DeviceTracking()),
                    Tabs\Tab::make('Smart Mirror')
                        ->schema(static::smartMirror()),
                ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('code')
                    ->label('Kode Kamar')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('Nama')
                    ->searchable(),
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

    public static function getRelations(): array
    {
        return [
            RelationManagers\DataRoomsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListRooms::route('/'),
            'create' => Pages\CreateRoom::route('/create'),
            'view' => Pages\ViewRoom::route('/{record}'),
            'edit' => Pages\EditRoom::route('/{record}/edit'),
        ];
    }

    public static function infoRoom() {
        return [
            Infolists\Components\Section::make([
                Infolists\Components\TextEntry::make('code')
                    ->label('Kode Kamar')
                    ->badge(),
                Infolists\Components\TextEntry::make('name')
                    ->label('Nama Kamar'),
                Infolists\Components\TextEntry::make('username')
                    ->label('Dibuat Oleh'),
                Infolists\Components\TextEntry::make('description')
                    ->label('Deskripsi Kamar')
                    ->default('Belum Ditambahkan')
                    ->columnSpanFull(),
            ])->columns(['md' => 3,])
        ];
    }

    public static function DeviceTracking() {
        return [
            Infolists\Components\Section::make([
                Infolists\Components\ViewEntry::make('device-tracking')
                    ->view('filament.device-tracking'),
            ])
        ];
    }

    public static function smartMirror() {
        return [
            Infolists\Components\Section::make([

            ])
        ];
    }
}
