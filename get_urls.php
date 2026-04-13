<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$books = App\Models\Buku::all(['judul', 'cover']);
foreach ($books as $x) {
    if (str_starts_with($x->cover, 'http')) {
        echo "'" . addslashes($x->judul) . "' => '" . $x->cover . "',\n";
    }
}
