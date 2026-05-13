<?php
// ─────────────────────────────────────────────────────────────────────────────
// Controller/LeaderboardController.php
// Loads the initial top-10 leaderboard data for the page render.
// Subsequent auto-refreshes hit the AJAX API endpoint instead.
//
// URL: View/leaderboard.php
// Access: Any logged-in user (student or instructor).
// ─────────────────────────────────────────────────────────────────────────────

require_once "../config/session.php";
require_once "../Model/db.php";

requireAuth();   // Any logged-in role can view the leaderboard

$database    = new db();
$connection  = $database->connection();
$leaderboard = $database->getLeaderboard($connection);

// Highlight the current user's row (if they appear in top 10)
$current_user_id = (int) $_SESSION['user_id'];
