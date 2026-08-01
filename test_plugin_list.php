<?php
require __DIR__.'/vendor/autoload.php';
$app = require __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$pm = $app->make(Cms\Core\Services\PluginManager::class);
$plugins = $pm->getInstalledPlugins();
echo json_encode($plugins, JSON_PRETTY_PRINT);
?>
