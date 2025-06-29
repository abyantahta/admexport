<?php

// Simple script to check interlock timers
// This can be called by a cron job every 5 minutes

// Bootstrap Laravel
require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

// Run the interlock timer check command
\Illuminate\Support\Facades\Artisan::call('interlock:check-timer');

echo "Interlock timer checked at: " . date('Y-m-d H:i:s') . "\n"; 