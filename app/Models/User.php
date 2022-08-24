<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, SoftDeletes;

    /** @var string */
    protected $guard = 'user';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'api_key'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'updated_at' => 'datetime: H:i:s d/m/Y',
        'created_at' => 'datetime: H:i:s d/m/Y',
        'email_verified_at' => 'datetime',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'image',
        'is_player',
        'email_verified_at',
        'password',
    ];

    /**
     * @return HasMany
     */
    public function verifications()
    {
        return $this->hasMany(Token::class);
    }

    /**
     * @return HasMany
     */
    public function snsAcounts()
    {
        return $this->hasMany(SnsAccount::class);
    }
}
