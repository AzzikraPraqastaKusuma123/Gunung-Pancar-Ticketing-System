<?php
$role = \Spatie\Permission\Models\Role::where('name', 'security')->first();
if ($role) {
    echo "Permissions for security:\n";
    foreach ($role->permissions as $p) {
        echo "- " . $p->name . "\n";
    }
}
