<?php
// get_urls2.php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$books = App\Models\Buku::all(['judul', 'cover']);
$out = "";
foreach ($books as $x) {
    if (str_starts_with($x->cover, 'http')) {
        $out .= "'" . addslashes($x->judul) . "' => '" . $x->cover . "',\n";
    }
}
file_put_contents('urls_valid.txt', $out);
