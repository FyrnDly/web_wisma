<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Log;

class Device extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'mac_address', 'created_by'
    ];

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function logs(): HasMany {
        return $this->hasMany(Log::class, 'mac_address_device', 'mac_address');
    }
}
