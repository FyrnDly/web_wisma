<?php

namespace App\Filament\Widgets;

use Carbon\Carbon;
use App\Models\Room;
use App\Models\User;
use App\Models\Device;
use Illuminate\Support\Facades\DB;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;

class StatsView extends BaseWidget
{
    protected static ?string $pollingInterval = null;

    protected function getStats(): array
    {
        $date = Carbon::now()->format('Y-m-d');

        $room_total = Room::count();
        $room_count = DB::table('rooms as rm')
            ->join('data_rooms as dr', 'dr.room_id', '=', 'rm.id')
            ->select('rm.id')
            ->distinct()
            ->count('rm.id');

        $device_total = Device::count();
        $device_iot = DB::table('devices as d')
            ->join('data_rooms as dr', 'dr.mac_address', '=', 'd.mac_address')
            ->select('d.id')
            ->distinct()
            ->count('d.id');
        $device_on = DB::table('devices as d')
            ->join('data_rooms as dr', 'dr.mac_address', '=', 'd.mac_address')
            ->join('logs as l', function ($join) use ($date) {
                $join->on('l.data_room_id', '=', 'dr.id')
                    ->whereDate('l.created_at', $date);
            })
            ->select('d.id')
            ->distinct()
            ->count('d.id');

        $user = User::count();
        $operator = User::whereJsonContains('roles', ["admin"])->count();
        $viewer = User::whereJsonContains('roles', ["viewer"])->count();

        return [
            Stat::make("Safe Room's", $room_count)
                ->description('Kamar Terkoneksi Sistem Navigasi dari '.$room_total.' Kamar')
                ->descriptionIcon('heroicon-m-circle-stack')
                ->color('success'),
            Stat::make("IoT Online", $device_on)
                ->description('Perangkat Terhubung Sistem Navigasi '. $device_iot .'/'. $device_total .' Perangkat')
                ->descriptionIcon('heroicon-m-cloud')
                ->color('info'),
            Stat::make("Pengguna Terdaftar", $user)
                ->description('Sebagai Staff '.$viewer.' | Sebagai Operator '.$operator)
                ->descriptionIcon('heroicon-m-user-group', 'before')
        ];
    }
}
