<?php

use App\Models\Buku;
use Illuminate\Support\Facades\Http;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Generating/Fetching book covers...\n";

$books = Buku::all();
$processed = 0;

foreach ($books as $buku) {
    // Skip if already has a valid remote URL
    if (!empty($buku->cover) && str_starts_with($buku->cover, 'http')) {
        echo "[SKIP] {$buku->judul} - already has remote URL\n";
        continue;
    }

    echo "Processing: {$buku->judul}...";
    
    // First try Google Books API
    $query = urlencode($buku->judul . ' ' . ($buku->pengarang ?? ''));
    try {
        $response = Http::timeout(5)->get("https://www.googleapis.com/books/v1/volumes?q={$query}&maxResults=1");
        
        if ($response->successful() && isset($response->json()['items'][0]['volumeInfo']['imageLinks']['thumbnail'])) {
            $thumbnail = $response->json()['items'][0]['volumeInfo']['imageLinks']['thumbnail'];
            $thumbnail = str_replace('http:', 'https:', $thumbnail);
            $thumbnail = str_replace('&edge=curl', '', $thumbnail);
            
            $buku->cover = $thumbnail;
            $buku->save();
            echo " [FOUND via Google Books]\n";
            $processed++;
        } else {
            throw new Exception('Google Books API returned no results');
        }
    } catch (Exception $e) {
        echo " [Using placeholder]\n";
        // Generate a nice placeholder based on category
        $categoryColors = [
            'Fiksi' => '8B5CF6',
            'Non-Fiksi' => '3B82F6',
            'Sejarah' => 'DC2626',
            'Teknologi' => '10B981',
            'Pendidikan' => 'F59E0B',
            'Seni' => 'EC4899',
            'Sains' => '6366F1',
            'default' => '4F46E5'
        ];
        
        $color = $categoryColors[$buku->kategori] ?? $categoryColors['default'];
        $titleEnc = urlencode(substr($buku->judul, 0, 20));
        $authorEnc = urlencode(substr($buku->pengarang ?? 'Author', 0, 15));
        
        // Create a placeholder with title and author
        $buku->cover = "https://placehold.co/400x560/{$color}/ffffff?text={$titleEnc}%20%7C%20{$authorEnc}";
        $buku->save();
        $processed++;
    }
    
    sleep(0.5); // be nice to the API
}

echo "\n=== Done updating book covers! ===\n";
echo "Total books processed: {$processed}\n";
