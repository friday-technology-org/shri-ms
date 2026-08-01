<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

echo "Media 1: " . get_media_url(1) . "\n";
echo "Media 2: " . get_media_url(2) . "\n";
echo "Media 3: " . get_media_url(3) . "\n";
