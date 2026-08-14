<?php
$admin = App\Models\User::firstOrCreate(['email' => 'admin@example.com'], ['name' => 'Super Admin', 'password' => bcrypt('password')]);
$admin->assignRole('super_admin');
echo "Admin created successfully.\n";
