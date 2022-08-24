<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = Permission::all()->pluck('id');

        $role = Role::firstOrCreate([
            'name' => 'System Admin',
            'guard' => 'admin', // Guard name in config/auth.php
            'level' => 1,
        ]);

        $role->save();
        $role->permissions()->sync($permissions);

		Role::firstOrCreate([
			'name' => 'Admin',
			'guard' => 'admin', // Guard name in config/auth.php
			'level' => 5,
		],
		[
			'name' => 'User VIP',
			'guard' => 'user', // Guard name in config/auth.php
			'level' => 1,
		],
		[
			'name' => 'User',
			'guard' => 'user', // Guard name in config/auth.php
			'level' => 5,
		]);
    }
}
