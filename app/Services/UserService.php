<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserService
{

    /**
     * @param $params
     * @return mixed
     */
    public function searchUser($params)
    {
        $query = User::select(
            'id',
            'name',
            'email',
            'api_key',
            'phone',
            'image');
        if (!empty($params['id'])) {
            $query->where('id', $params['id']);
        }
        if (!empty($params['email'])) {
            $query->where('email', $params['email']);
        }
        if (!empty($params['phone'])) {
            $query->where('phone', $params['phone']);
        }
        if (!empty($params['search'])) {
            $query->where(function ($query) use ($params) {
                $query->where('id', $params['search'])
                    ->orWhere('email', 'like', '%' . $params['search'] . '%')
                    ->orWhere('phone', 'like', '%' . $params['search'] . '%')
                    ->orWhere('name', 'like', '%' . $params['search'] . '%');
            });
        }
        if (!empty($params['except_id'])) {
            $query->where('id', '<>', $params['except_id']);
        }
        return $query->paginate(config('app.pagination'));
    }

    /**
     * @param $attributes
     * @return mixed
     */
    public function createUser(array $attributes)
    {
        if (empty($attributes['password'])) {
            $attributes['password'] = Hash::make(Str::random(16));
        }

        return DB::transaction(function () use ($attributes) {
            $user = new User($attributes);
            $user->save();

            return $user;
        });
    }


    /**
     * @param User $user
     * @param array $attributes
     * @return mixed
     */
    public function updateUser(User $user, array $attributes)
    {
        return DB::transaction(function () use ($attributes, $user) {
            $user->update($attributes);
            return $user;
        });
    }

    /**
     * @param User $user
     * @return mixed
     */
    public function deleteUser(User $user)
    {
        return DB::transaction(function () use ($user) {
            return $user->delete();
        });
    }

}
