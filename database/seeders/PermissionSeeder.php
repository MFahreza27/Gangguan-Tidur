<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role_admin = Role::updateOrCreate(['name' => 'admin']);
        $role_user  = Role::updateOrCreate (['name' =>'user']);
        $permission = Permission::updateOrCreate([
            'name' => 'view_admin',
        ]);

        $role_admin->givePermissionTo($permission);
        $user = User::find(1);
        $user2 = User::find(2);
        
        $user ->assignRole('admin');
        $user2 ->assignRole('user');

    }
}
