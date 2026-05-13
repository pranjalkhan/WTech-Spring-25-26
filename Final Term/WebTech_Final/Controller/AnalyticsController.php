<?php
session_start();

include "../Model/db.php";

// Only instructors can access this page
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "instructor") {
    Header("Location: ../View/leaderboard.php");
    exit;
}

$database   = new db();
$connection = $database->connection();

$quizzes       = $database->getInstructorQuizzes($connection, $_SESSION["user_id"]);
$attempts      = [];
$selected_quiz = "";

if (isset($_GET["quiz_id"]) && $_GET["quiz_id"] !== "") {
    $selected_quiz = (int)$_GET["quiz_id"];
    $attempts      = $database->getQuizAttempts($connection, $selected_quiz);
}
?>
