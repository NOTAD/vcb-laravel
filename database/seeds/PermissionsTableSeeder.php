<?php

use App\Enums\Permissions;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $permissions = [];

        foreach (Permissions::values() as $permission) {
           $permissions[] = [
               'key' => $permission->getValue(),
               'name' => __('enum.permissions.'.strtolower($permission->getKey())),
           ];
        }

        DB::table('permissions')->insert($permissions);
    }
}
