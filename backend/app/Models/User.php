<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Saunalog;


class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens, HasRoles; 

    protected $fillable = [
        'username',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function saunalogs()
    {
        return $this->hasMany(Saunalog::class, 'userId');
    }

    public static function createUser(array $data): self
    {
        $data['password'] = Hash::make($data['password']);
        return static::create($data);
    }
}
