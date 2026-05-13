<?php
session_start();

include "../Model/db.php";

// ── Auth check (Task 1 sets $_SESSION — uncomment after merging) ──
// if (!isset($_SESSION["user_id"])) {
//     Header("Location: ../View/leaderboard.php");
//     exit;
// }

$attempt   = [];
$breakdown = [];
$result_error = "";

$attempt_id = 0;
if (isset($_GET["attempt_id"])) {
    $attempt_id = (int)$_GET["attempt_id"];
} elseif (isset($_GET["id"])) {
    $attempt_id = (int)$_GET["id"];
}

if ($attempt_id > 0) {
    $database   = new db();
    $connection = $database->connection();

    $attempt   = $database->getAttempt($connection, $attempt_id);
    if (!empty($attempt)) {
        $breakdown = $database->getAnswerBreakdown($connection, $attempt_id);
    }
}

if (empty($attempt)) {
    $result_error = "Attempt not found. Please open a saved attempt from My Results or Analytics.";
} else {
    $score   = $attempt["score"];
    $total   = $attempt["total_marks"];
    $percent = $total > 0 ? ($score / $total) * 100 : 0;
    $pass    = $percent >= 60;
}
?>
