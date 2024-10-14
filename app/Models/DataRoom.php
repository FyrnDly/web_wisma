<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Models\Log;
use App\Models\Room;

class DataRoom extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'device_connected', 'human', 'room_id', 'log_id'
    ];

    public function log(): BelongsTo {
        return $this->belongsTo(Log::class, 'log_id', 'id');
    }

    public function room(): BelongsTo {
        return $this->belongsTo(Room::class, 'room_id', 'id');
    }
}
