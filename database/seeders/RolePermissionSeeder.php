<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    private $superAdminPermissions = [
        'view permissions',
        'create permissions',
        'edit permissions',
        'delete permissions',
        'view roles',
        'create roles',
        'edit roles',
        'delete roles',
        'view users',
        'create users',
        'edit users',
        'delete users',
        'view academic',
        'edit academic',
        'create academic',
        'delete academic',

    ];



    public function run(): void
    {
        foreach ($this->superAdminPermissions as $permission) {
            Permission::create([
                'name' => $permission,
            ]);
        }
        $role = Role::create(['name' => 'Super Admin']);
        $managementRoles = Role::create(['name' => 'Center'], ['name' => 'Sub-Center'], ['name' => 'Operation-Head']);
        $permissions = Permission::pluck('id', 'id')->all();
        $role->syncPermissions($permissions);
    }
}
