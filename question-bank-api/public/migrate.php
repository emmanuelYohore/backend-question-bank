<?php
if ($_GET['token'] !== 'aquali2026secret') {
    die('Non autorisé');
}

define('LARAVEL_START', microtime(true));
require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);

$status = $kernel->call('migrate', ['--force' => true]);

echo "<pre>";
echo $kernel->output();
echo "</pre>";
echo "Status: " . $status;