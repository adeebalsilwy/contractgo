<?php
require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Support\Facades\DB;

// Initialize Laravel application
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo 'Professions count: ' . DB::table('professions')->count() . PHP_EOL;
$professions = DB::table('professions')->get();
foreach ($professions as $p) {
    echo 'ID: ' . $p->id . ', Name: ' . $p->name . PHP_EOL;
}