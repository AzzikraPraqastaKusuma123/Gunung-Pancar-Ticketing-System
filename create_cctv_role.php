<?php
\Spatie\Permission\Models\Role::firstOrCreate(['name' => 'security']);
$user = \App\Models\User::firstOrCreate(
    ['email' => 'cctv@example.com'],
    ['name' => 'Petugas CCTV', 'password' => bcrypt('password')]
);
$user->assignRole('security');
echo "Role and user created.\n";
