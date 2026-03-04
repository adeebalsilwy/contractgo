<?php
require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\Schema;

// Initialize Laravel application
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo 'Has profession_id column in users: ' . (Schema::hasColumn('users', 'profession_id') ? 'YES' : 'NO') . PHP_EOL;
echo 'Has profession_id column in clients: ' . (Schema::hasColumn('clients', 'profession_id') ? 'YES' : 'NO') . PHP_EOL;
echo 'Has profession_id column in contracts: ' . (Schema::hasColumn('contracts', 'profession_id') ? 'YES' : 'NO') . PHP_EOL;