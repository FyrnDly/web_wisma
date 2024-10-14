<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Models\DataRoom;
use App\Models\Device;

class Log extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'mac_address_device', 'status_beacon', 'data'
    ];

    protected function casts(): array{
        return [
            'status_beacon' => 'array',
            'data' => 'array',
        ];
    }

    public function device(): BelongsTo {
        return $this->belongsTo(Device::class, 'mac_address_device', 'mac_address');
    }

    public function data_rooms(): HasMany {
        return $this->hasMany(DataRoom::class, 'log_id', 'id');
    }
}
