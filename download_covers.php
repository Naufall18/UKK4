<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Buku;
use Illuminate\Support\Str;

$books = Buku::all();

$baseAssetDir = __DIR__ . '/mobile_paket44/assets/images/books';
if (!is_dir($baseAssetDir)) {
    mkdir($baseAssetDir, 0755, true);
    echo "Created directory: $baseAssetDir\n";
}

foreach ($books as $book) {
    $slug = Str::slug($book->judul);
    $filename = $slug . '.jpg';
    $fullPath = $baseAssetDir . '/' . $filename;
    $assetPath = 'assets/images/books/' . $filename;

    $isMissingOrExternal = empty($book->cover) || strpos($book->cover, 'assets/') === false;
    $isDamaged = file_exists($fullPath) && filesize($fullPath) < 100;

    if ($isMissingOrExternal || $isDamaged) {

        // Skip if already downloaded physically and valid (not empty 302 redirect string) but DB not updated
        if (file_exists($fullPath) && filesize($fullPath) > 100) {
            $book->cover = $assetPath;
            $book->save();
            echo "Already downloaded, updated DB: {$book->judul}\n";
            continue;
        }

        echo "Searching cover for: {$book->judul} ... ";

        $query = urlencode($book->judul);
        $apiUrl = "https://www.googleapis.com/books/v1/volumes?q=intitle:{$query}&maxResults=1";

        // fetch using cURL
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; Win64; x64)');
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);

        $imgUrl = null;

        if ($response) {
            $data = json_decode($response, true);
            if (!empty($data['items'][0]['volumeInfo']['imageLinks']['thumbnail'])) {
                $imgUrl = $data['items'][0]['volumeInfo']['imageLinks']['thumbnail'];
                $imgUrl = str_replace('http://', 'https://', $imgUrl);
                $imgUrl = str_replace('&edge=curl', '', $imgUrl);
            }
        }

        // OpenLibrary Fallback
        if (!$imgUrl) {
            $olUrl = "https://openlibrary.org/search.json?title={$query}&limit=1";
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $olUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $resOl = curl_exec($ch);
            curl_close($ch);
            if ($resOl) {
                $olData = json_decode($resOl, true);
                if (!empty($olData['docs'][0]['cover_i'])) {
                    $coverI = $olData['docs'][0]['cover_i'];
                    $imgUrl = "https://covers.openlibrary.org/b/id/{$coverI}-L.jpg";
                }
            }
        }

        // Placeholder Fallback
        if (!$imgUrl) {
            $text = urlencode(substr($book->judul, 0, 15));
            $imgUrl = "https://ui-avatars.com/api/?name={$text}&size=512&background=random&font-size=0.33&length=3";
        }

        if ($imgUrl) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $imgUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0');
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $imageContent = curl_exec($ch);
            curl_close($ch);

            if ($imageContent) {
                file_put_contents($fullPath, $imageContent);
                $book->cover = $assetPath;
                $book->save();
                echo "[SUCCESS] saved to $assetPath\n";
            } else {
                echo "[FAILED] could not download image content\n";
            }
        } else {
            echo "[FAILED] completely failed to find any image URL\n";
        }
    } else {
        echo "Skip (already has asset cover): {$book->judul}\n";
    }
}

echo "\nDone!\n";
