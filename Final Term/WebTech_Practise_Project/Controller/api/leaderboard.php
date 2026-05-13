<?php
// ─────────────────────────────────────────────────────────────────────────────
// Controller/api/leaderboard.php
// AJAX endpoint — GET /api/leaderboard
// Returns top-10 leaderboard as JSON.
// Called every 30 seconds by the leaderboard page's setInterval.
//
// Response shape:
//   { "success": true, "data": [ {student_id, name, total_score, attempts_count}, ... ] }
//   { "success": false, "message": "..." }
// ─────────────────────────────────────────────────────────────────────────────

// Always respond with JSON
header('Content-Type: application/json');

// Session check — must be logged in to call this endpoint
require_once "../../config/session.php";

if (!isset($_SESSION['user_id'])) {
    http_response_code(401);
    echo json_encode(["success" => false, "message" => "Unauthorized"]);
    exit();
}

// Only GET is allowed
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(["success" => false, "message" => "Method Not Allowed"]);
    exit();
}

require_once "../../Model/db.php";

try {
    $database    = new db();
    $connection  = $database->connection();
    $leaderboard = $database->getLeaderboard($connection);

    echo json_encode([
        "success" => true,
        "data"    => $leaderboard
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "success" => false,
        "message" => "Server error. Please try again."
    ]);
}
