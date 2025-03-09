<?php

namespace App\User\Entities;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable {
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'id',
        'name',
        'email',
        'amount',
        'password',
    ];

    protected $guarded = ['id'];

    public $incrementing = false;

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public function setAttribute($key, $value)
    {
        if ($key === 'id' && $this->exists) {
            throw new \Exception("L'ID ne peut pas être modifié.");
        }

        return parent::setAttribute($key, $value);
    }
}
