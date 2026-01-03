<?php

namespace App\YouTube;

require_once ROOT_PATH . '/config/env.php';

use App\Env;

class ApiYouTube {

    private string $apiKey;
    private int $ttl = 3600;    
    private string $cacheDir;

    public function __construct() {

        $this->apiKey = Env::get('YT_API_KEY');

        if (!$this->apiKey) {
            throw new \Exception("API KEY YouTube tidak ditemukan di file .env");
        }

        // folder cache
        $this->cacheDir = __DIR__ . "/cache/";

        if (!is_dir($this->cacheDir)) {
            mkdir($this->cacheDir, 0777, true);
        }
    }



    private function fetchWithCache(string $cacheName, string $url): array {

        $file = $this->cacheDir . $cacheName;

        if (file_exists($file)) {
            $cache = json_decode(file_get_contents($file), true);

            if (time() - $cache["timestamp"] < $this->ttl) {
                return $cache["data"];
            }
        }

        $data = $this->fetchFromApi($url);

        file_put_contents(
            $file,
            json_encode([
                "timestamp" => time(),
                "data" => $data
            ])
        );

        return $data;
    }

    private function fetchFromApi(string $url): array {

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $res = curl_exec($ch);

        if ($res === false) {
            throw new \Exception("Gagal fetch API YouTube: " . curl_error($ch));
        }

        curl_close($ch);

        $json = json_decode($res, true);

        if (!is_array($json)) {
            throw new \Exception("Respon YouTube API tidak valid");
        }

        return $json;
    }

    //yt search API
    public function searchVideos(string $query, int $maxResults = 20): array {

        $url = "https://www.googleapis.com/youtube/v3/search"
            . "?part=snippet"
            . "&type=video"
            . "&q=" . urlencode($query)
            . "&maxResults={$maxResults}"
            . "&key={$this->apiKey}";

        $cacheName = "search_" . md5($query) . "_{$maxResults}.json";

        return $this->fetchWithCache($cacheName, $url);
    }

}
