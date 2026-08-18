<?php

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

$role = Role::firstOrCreate(['name' => 'security', 'guard_name' => 'web']);

$permissions = [
    'view_any_blacklist',
    'view_blacklist',
    'create_blacklist',
    'update_blacklist',
    'delete_blacklist',
    'delete_any_blacklist',
    'view_any_patrol',
    'view_patrol',
    'create_patrol',
    'update_patrol',
    'delete_patrol',
    'delete_any_patrol',
];

foreach ($permissions as $permName) {
    $permission = Permission::firstOrCreate(['name' => $permName, 'guard_name' => 'web']);
    $role->givePermissionTo($permission);
}

echo "Permissions added to security role for Blacklist and Patrol.\n";
