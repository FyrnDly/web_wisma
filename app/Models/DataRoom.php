<?php

namespace App\Models;

use App\Models\Log;
use App\Models\Room;
use App\Models\User;
use App\Models\Device;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DataRoom extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'x', 'y', 'room_id', 'mac_address', 'created_by'
    ];

    public function getUsernameAttribute() {
        return Str::headline(DB::table('users')->where('id', $this->created_by)->value('name'));
    }

    public function getDeviceNameAttribute() {
        return DB::table('devices')->where('mac_address', $this->mac_address)->value('name');
    }

    public function getDeviceTypeAttribute() {
        return Str::headline(DB::table('devices')->where('mac_address', $this->mac_address)->value('type'));
    }

    public function getCoordinateAttribute() {
        return "(". $this->x .",". $this->y .")";
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function device(): BelongsTo {
        return $this->belongsTo(Device::class, 'mac_address', 'mac_address');
    }

    public function room(): BelongsTo {
        return $this->belongsTo(Room::class, 'room_id', 'id');
    }

    public function logs(): HasMany {
        return $this->hasMany(Log::class, 'data_room_id', 'id');
    }
}
