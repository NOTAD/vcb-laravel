<?php

use App\Models\Admin;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = new Admin([
            'name' => 'System Admin',
            'phone' => '0999999999',
            'email' => 'system@example.com',
            'image' => '/images/admin/avatar.png',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
        ]);
        $admin->save();
        $adminRole = Role::whereGuard('admin')->whereLevel(1)->first();
        $admin->roles()->sync($adminRole);

        $admin = new Admin([
            'name' => 'Admin',
            'phone' => '0999999998',
            'email' => 'admin@example.com',
            'image' => '/images/admin/avatar.png',
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
        ]);
        $admin->save();
        $adminRole = Role::whereGuard('admin')->whereLevel(5)->first();
        $admin->roles()->sync($adminRole);
    }
}
