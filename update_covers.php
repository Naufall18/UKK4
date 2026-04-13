<?php

use App\Models\Buku;
use Illuminate\Support\Facades\Http;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Fetching book covers from Google Books API...\n";

$books = Buku::all();
foreach ($books as $buku) {
    if (str_starts_with($buku->cover, 'http')) {
        continue;
    }

    echo "Searching for: {$buku->judul}\n";
    $query = urlencode($buku->judul . ' ' . $buku->pengarang);
    $response = Http::get("https://www.googleapis.com/books/v1/volumes?q={$query}&maxResults=1");

    if ($response->successful() && isset($response->json()['items'][0]['volumeInfo']['imageLinks']['thumbnail'])) {
        $thumbnail = $response->json()['items'][0]['volumeInfo']['imageLinks']['thumbnail'];
        // Replace http with https, and remove zoom param for better quality if possible
        $thumbnail = str_replace('http:', 'https:', $thumbnail);
        $thumbnail = str_replace('&edge=curl', '', $thumbnail);

        $buku->cover = $thumbnail;
        $buku->save();
        echo "Found cover for {$buku->judul}: {$thumbnail}\n";
    } else {
        echo "No cover found for {$buku->judul}, generating a placeholder...\n";
        // fallback to placehold.co with book title
        $titleEnc = urlencode($buku->judul);
        $buku->cover = "https://placehold.co/400x560/4F46E5/ffffff?text={$titleEnc}";
        $buku->save();
    }
    sleep(1); // be nice to the API
}

echo "Done updating book covers!\n";
