<?php
require_once __DIR__ . "/../../backend/service/adminStatscard.php";
use App\Dashboard\DashboardCards;

$cards = new DashboardCards();

$cardMateri   = $cards->cardTotalMateriAktif();
$cardUsers    = $cards->cardTotalUsers('create_time'); 
$cardSessions = $cards->cardActiveSessions();
$cardRate     = $cards->cardCompletionRate();

// misal return ke view
$data = [
  'materi' => $cardMateri,
  'users' => $cardUsers,
  'sessions' => $cardSessions,
  'completion' => $cardRate,
];
