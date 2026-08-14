<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

$roles = Role::all()->pluck('name')->toArray();
echo "Roles available: " . implode(', ', $roles) . "\n";

$salesRole = Role::where('name', 'sales')->first();
if (!$salesRole) {
    echo "Sales role not found, creating...\n";
    $salesRole = Role::create(['name' => 'sales']);
}

$salesUsers = User::role('sales')->get();
if ($salesUsers->isEmpty()) {
    echo "No sales user found, creating one...\n";
    $user = User::create([
        'name' => 'Sales Team',
        'email' => 'sales@example.com',
        'password' => Hash::make('password')
    ]);
    $user->assignRole('sales');
    echo "Created sales user: sales@example.com / password\n";
} else {
    echo "Found sales users:\n";
    foreach ($salesUsers as $user) {
        echo " - " . $user->email . " (We will reset password to 'password' for testing)\n";
        $user->password = Hash::make('password');
        $user->save();
    }
}
