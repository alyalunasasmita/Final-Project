<?php

namespace App\Service;

require_once __DIR__ . '/../api/apiYoutube.php';

use App\YouTube\ApiYouTube;

class YouTubeSearchService {

    private ApiYouTube $yt;

    public function __construct() {
        $this->yt = new ApiYouTube();
    }

    public function search(string $keyword, int $limit = 3): array {

        $keyword = trim($keyword);
        if ($keyword === "") {
            return [];
        }

        $raw = $this->yt->searchVideos($keyword . " penjelasan", 10);
        $items = $raw['items'] ?? [];

        $results = [];

        foreach ($items as $item) {
            if (!isset($item['id']['videoId'])) continue;

            $results[] = [
                'video_id'  => $item['id']['videoId'],
                'title'     => $item['snippet']['title'],
                'thumbnail' => $item['snippet']['thumbnails']['medium']['url'],
                'channel'   => $item['snippet']['channelTitle']
            ];

            if (count($results) >= $limit) break;
        }

        return $results;
    }
}
