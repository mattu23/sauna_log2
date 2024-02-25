<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolesAndPermissionsSeeder extends Seeder
{
    public function run()
    {
        Permission::create(['name' => 'update_saunalog']);
        Permission::create(['name' => 'delete_saunalog']);

        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo('update_saunalog');
        $adminRole->givePermissionTo('delete_saunalog');

        Role::create(['name' => 'member']);

    }
}
