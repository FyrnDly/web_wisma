<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Models\DataRoom;
use App\Models\User;

class Room extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['name', 'created_by'];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function data_rooms(): HasMany {
        return $this->hasMany(DataRoom::class, 'room_id', 'id');
    }
}
