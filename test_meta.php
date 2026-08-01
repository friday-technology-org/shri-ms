<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$post = \Cms\Core\Models\Post::first();
if ($post) {
    echo "Post ID: {$post->id}\n";
    $meta = $post->meta()->get();
    foreach($meta as $m) {
        echo "Meta Key: {$m->meta_key}\n";
        echo "Meta Value: {$m->meta_value}\n";
        $decoded = $post->getMeta($m->meta_key);
        echo "Decoded:\n";
        print_r($decoded);
        echo "-----------------\n";
    }
} else {
    echo "No posts found.\n";
}
