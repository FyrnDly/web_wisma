<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Notifications\Notifiable;
use App\Models\Device;
use App\Models\Room;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username', 'name', 'email', 'password',
        'address', 'verified', 'roles',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array{
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'roles' => 'array',
        ];
    }

    public function canAccessPanel($panel): bool{
        return true;
    }

    public function hasRoles($roles){
        $userRoles = $this->roles;
        return count(array_intersect($roles, $userRoles)) > 0;
    }

    protected static function booted(): void{
        static::creating(function (User $user) {
            if (empty($user->roles)) {
                $user->roles = ["viewer"];
            }
        });
    }

    public function rooms(): HasMany {
        return $this->hasMany(Room::class, 'created_by', 'id');
    }

    public function devices(): HasMany {
        return $this->hasMany(Device::class, 'created_by', 'id');
    }
}
