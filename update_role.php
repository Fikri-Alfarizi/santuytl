<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

// Update user role to owner
DB::table('users')->where('id', 4)->update(['role' => 'owner']);

echo "User role updated to 'owner' successfully!\n";
