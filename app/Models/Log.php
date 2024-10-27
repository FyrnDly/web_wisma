<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Models\DataRoom;

class Log extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'data_room_id', 'data'
    ];

    protected function casts(): array{
        return [
            'data' => 'array',
        ];
    }

    public function data_room(): BelongsTo {
        return $this->belongsTo(DataRoom::class, 'data_room_id', 'id');
    }
}
