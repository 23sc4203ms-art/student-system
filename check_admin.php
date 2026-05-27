<?php
require __DIR__ . '/bootstrap/app.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(\Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\UserAccount;

$admin = UserAccount::where('username', 'admin')->first();

if ($admin) {
    echo "Admin found:\n";
    echo "Username: " . $admin->username . "\n";
    echo "Email: " . $admin->email . "\n";
    echo "Role: " . $admin->Role . "\n";
    echo "Is Active: " . $admin->is_active . "\n";
} else {
    echo "No admin account found. Creating one now...\n";
    $hash = \Illuminate\Support\Facades\Hash::make('secret123');
    UserAccount::create([
        'username' => 'admin',
        'email' => 'admin@example.com',
        'Password' => $hash,
        'Role' => 'admin',
        'is_active' => 1,
    ]);
    echo "Admin account created!\n";
}
?>
