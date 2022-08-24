<?php

namespace App\Services;

use App\Models\Role;
use Illuminate\Support\Facades\DB;

class RoleService
{

    /**
     * @param $attributes
     * @return mixed
     */
    public function createRole(array $attributes)
    {
        return DB::transaction(function () use ($attributes) {
            $role = new Role($attributes);
            $role->save();
            $role->permissions()->sync($attributes['permission_ids']);

            return $role->load('permissions');
        });
    }

    /**
     * @param Role $role
     * @param array $attributes
     * @return mixed
     */
    public function updateRole(Role $role, array $attributes)
    {
        return DB::transaction(function () use ($attributes, $role) {
            $role->update($attributes);
            $role->permissions()->sync($attributes['permission_ids']);

            return $role->load('permissions');
        });
    }

    /**
     * @param Role $role
     * @return mixed
     */
    public function deleteRole(Role $role)
    {
        return DB::transaction(function () use ($role) {
            return $role->delete();
        });
    }

}
