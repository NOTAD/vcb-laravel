<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;


class Admin extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, SoftDeletes;

    /** @var string */
    protected $guard = 'admin';

    /** @var string */
    protected $table = 'admins';

    /** @var array */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /** @var array */
    protected $casts = [
        'updated_at' => 'datetime: H:i:s d/m/Y',
        'created_at' => 'datetime: H:i:s d/m/Y',
        'email_verified_at' => 'datetime',
    ];

    /** @var array $fillable */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'image',
        'email_verified_at',
        'password',
    ];

    /**
     * @return BelongsToMany
     */
    public function roles()
    {
        return $this->belongsToMany(Role::class, 'admin_role');
    }

    /**
     * @return HasMany
     */
    public function verifications()
    {
        return $this->hasMany(Token::class);
    }


    /**
     * @param $permissionKey
     * @return bool
     */
    public function canDo($permissionKey)
    {
        $admin = $this->load('roles.permissions');

        foreach ($admin->roles as $role) {
            foreach ($role->permissions as $permission) {
                if ($permission->key === $permissionKey) {
                    return true;
                }
            }
        }

        return false;
    }
}
