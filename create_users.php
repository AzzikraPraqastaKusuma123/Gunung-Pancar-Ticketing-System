<?php
$sales = App\Models\User::firstOrCreate(['email' => 'sales@example.com'], ['name' => 'Sales Marketing', 'password' => bcrypt('password')]);
$sales->assignRole('sales');

$ticketing = App\Models\User::firstOrCreate(['email' => 'ticketing@example.com'], ['name' => 'Ticketing Staff', 'password' => bcrypt('password')]);
$ticketing->assignRole('ticketing');

echo "Test users created successfully.\n";
