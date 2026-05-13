<?php
session_start();

include "../Model/db.php";

// ── Auth check — student only (Task 1 sets $_SESSION — uncomment after merging) ──
// if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "student") {
//     Header("Location: ../View/leaderboard.php");
//     exit;
// }

$database   = new db();
$connection = $database->connection();

// Use session user_id after Task 1 merge; hardcoded to 1 for standalone demo
$student_id = isset($_SESSION["user_id"]) ? $_SESSION["user_id"] : 1;

$results = $database->getStudentResults($connection, $student_id);
?>
