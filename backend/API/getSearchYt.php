<?php

require_once __DIR__ . '/../service/ytSearch.php';

use App\Service\YouTubeSearchService;

header("Content-Type: application/json");

$keyword = $_GET["q"] ?? "";

$service = new YouTubeSearchService();

echo json_encode(
    $service->search($keyword, 3),
    JSON_UNESCAPED_SLASHES | JSON_PRETTY_PRINT
);
