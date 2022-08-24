<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class AddNewPermissionSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Permission::create([
			'key' => 131,
			'name' => 'Xem URL tùy chỉnh'
		]);
		Permission::create([
			'key' => 132,
			'name' => 'Thêm, sửa URL tùy chỉnh'
		]);
		Permission::create([
			'key' => 133,
			'name' => 'Xóa URL tùy chỉnh'
		]);
	}
}
