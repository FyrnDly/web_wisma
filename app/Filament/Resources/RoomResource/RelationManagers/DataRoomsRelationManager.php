<?php

namespace App\Filament\Resources\RoomResource\RelationManagers;

use Filament\Forms;
use Filament\Tables;
use App\Models\Device;
use Filament\Infolists;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Infolists\Infolist;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Resources\RelationManagers\RelationManager;

class DataRoomsRelationManager extends RelationManager
{
    protected static ?string $title = 'Perangkat Terhubung';
    protected static string $relationship = 'data_rooms';

    public function isReadOnly(): bool
    {
        return is_subclass_of($this->getPageClass(), EditRecord::class) ? TRUE:FALSE;
    }

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('mac_address')
                    ->label("Pilih Perangkat")
                    ->live()
                    ->required()
                    ->native(false)
                    ->options(Device::pluck('name', 'mac_address')),
                Forms\Components\TextInput::make('topic')
                    ->label("Pilih Perangkat")
                    ->unique(ignoreRecord: true)
                    ->required(),
                Forms\Components\Section::make('Posisi Perangkat')
                    ->description("Pilih Posisi Berdasarkan Koordinat Kartesius")
                    ->visible(function(Forms\Get $get){
                        $device_type = DB::table('devices')->where('mac_address', $get('mac_address'))->value('type');
                        return ($device_type == "beacon") ? TRUE:FALSE;
                    })
                    ->schema([
                        Forms\Components\TextInput::make('x')
                            ->label("Koordinat-X")
                            ->numeric()
                            ->default(0)
                            ->required(function(Forms\Get $get){
                                $device_type = DB::table('devices')->where('mac_address', $get('mac_address'))->value('type');
                                return ($device_type == "beacon") ? TRUE:FALSE;
                            }),
                        Forms\Components\TextInput::make('y')
                            ->label("Koordinat-Y")
                            ->numeric()
                            ->default(0)
                            ->required(function(Forms\Get $get){
                                $device_type = DB::table('devices')->where('mac_address', $get('mac_address'))->value('type');
                                return ($device_type == "beacon") ? TRUE:FALSE;
                            }),
                    ])->columns(['md'=>2]),
                Forms\Components\Hidden::make('created_by')
                    ->default(Auth::user()->id),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('device_name')
            ->columns([
                Tables\Columns\TextColumn::make('device_name')
                    ->label("Nama Perangkat"),
                Tables\Columns\TextColumn::make('device_type')
                    ->label("Tipe Perangkat")
                    ->badge()
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('topic')
                    ->label("Topic MQTT")
                    ->default("Belum Ditambahkan")
                    ->color('gray')
                    ->alignCenter()
                    ->badge(),
                Tables\Columns\TextColumn::make('username')
                    ->label("Dibuat Oleh")
                    ->alignCenter(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make()->color('info'),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\ForceDeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ]);
    }

    public function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('mac_address')
                    ->label("Alamat Mac Address")
                    ->badge()
                    ->color("gray"),
                Infolists\Components\TextEntry::make('device_name')
                    ->label("Nama Perangkat"),
                Infolists\Components\TextEntry::make('username')
                    ->label("Dibuat Oleh"),
                Infolists\Components\TextEntry::make('device_type')
                    ->label("Tipe Perangkat")
                    ->badge(),
                Infolists\Components\TextEntry::make('topic')
                    ->label("Topic MQTT")
                    ->color('gray')
                    ->badge(),
                Infolists\Components\TextEntry::make('coordinate')
                    ->label("Posisi Perangkat")
                    ->visible(fn($record) => $record->device_type == "Beacon"),
            ])->columns([
                'md' => 2,
                'lg' => 3
            ]);
    }
}
