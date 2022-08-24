<?php

namespace App\Services;

use App\Models\Admin;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminService
{

    /**
     * @param $params
     * @return mixed
     */
    public function searchAdmin($params)
    {
        $query = Admin::with('roles');
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
    public function createAdmin(array $attributes)
    {
        if (empty($attributes['password'])) {
            $attributes['password'] = Hash::make(Str::random(16));
        }
        return DB::transaction(function () use ($attributes) {
            $admin = new Admin($attributes);
            $admin->save();
            $admin->roles()->sync($attributes['role_ids']);

            return $admin->load('roles');
        });
    }

    /**
     * @param Admin $admin
     * @param array $attributes
     * @return mixed
     */
    public function updateAdmin(Admin $admin, array $attributes)
    {
        return DB::transaction(function () use ($attributes, $admin) {
            $admin->update($attributes);
            $admin->roles()->sync($attributes['role_ids']);

            return $admin->load('roles');
        });
    }

    /**
     * @param Admin $admin
     * @return mixed
     */
    public function deleteAdmin(Admin $admin)
    {
        return DB::transaction(function () use ($admin) {
            return $admin->delete();
        });
    }

}
