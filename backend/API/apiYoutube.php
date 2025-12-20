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

    public function extractYouTubeData(string $url): ?array {

        $url = trim($url);

        // Playlist
        if (strpos($url, "list=") !== false) {
            parse_str(parse_url($url, PHP_URL_QUERY), $q);
            return [
                "type"      => "playlist",
                "id"        => $q["list"],
                "embed_url" => "https://www.youtube.com/embed/videoseries?list=".$q["list"]
            ];
        }

        // Shorts
        if (preg_match("/youtube\.com\/shorts\/([a-zA-Z0-9_-]+)/", $url, $m)) {
            return $this->formatVideoResult($m[1]);
        }

        // youtu.be
        if (preg_match("/youtu\.be\/([a-zA-Z0-9_-]+)/", $url, $m)) {
            return $this->formatVideoResult($m[1]);
        }

        // embed
        if (preg_match("/embed\/([a-zA-Z0-9_-]+)/", $url, $m)) {
            return $this->formatVideoResult($m[1]);
        }

        // watch?v=
        parse_str(parse_url($url, PHP_URL_QUERY), $q);
        if (isset($q["v"])) {
            return $this->formatVideoResult($q["v"]);
        }

        return null;
    }

    private function formatVideoResult(string $videoId): array {

        return [
            "type"      => "video",
            "id"        => $videoId,
            "embed_url" => "https://www.youtube.com/embed/" . $videoId,
            "thumbnail" => [
                "hq" => "https://img.youtube.com/vi/{$videoId}/hqdefault.jpg",
                "sd" => "https://img.youtube.com/vi/{$videoId}/sddefault.jpg",
                "mq" => "https://img.youtube.com/vi/{$videoId}/mqdefault.jpg",
            ]
        ];
    }

    public function getPlaylistVideos(string $playlistId, int $maxResults = 20): array {

        $url = "https://www.googleapis.com/youtube/v3/playlistItems"
             . "?part=snippet&maxResults={$maxResults}"
             . "&playlistId={$playlistId}"
             . "&key={$this->apiKey}";

        $cacheName = "playlist_{$playlistId}_{$maxResults}.json";

        return $this->fetchWithCache($cacheName, $url);
    }

    public function getVideoDetail(string $videoId): array {

        $url = "https://www.googleapis.com/youtube/v3/videos"
             . "?part=snippet,contentDetails"
             . "&id={$videoId}"
             . "&key={$this->apiKey}";

        $cacheName = "video_{$videoId}.json";

        return $this->fetchWithCache($cacheName, $url);
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

    /* ============================================================
 *  YouTube SEARCH API
 * ============================================================ */
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

    // Convert ISO 8601 duration to seconds
    public function iso8601ToSeconds(string $iso): int {
    try {
        $interval = new DateInterval($iso);

        return ($interval->h * 3600)
             + ($interval->i * 60)
             + $interval->s;
    } catch (Exception $e) {
        return 0;
    }
}



}
