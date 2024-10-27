<?php

namespace App\Models;

use App\Models\Log;
use App\Models\User;
use App\Models\DataRoom;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Device extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'mac_address', 'created_by', 'type',
    ];

    public function getUsernameAttribute() {
        return Str::headline(DB::table('users')->where('id', $this->created_by)->value('name'));
    }

    public function user(): BelongsTo {
        return $this->belongsTo(User::class, 'created_by', 'id');
    }

    public function data_rooms(): HasMany {
        return $this->hasMany(DataRoom::class, 'mac_address', 'mac_address');
    }
}
