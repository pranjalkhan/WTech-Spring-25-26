<?php
session_start();

include "../Model/db.php";

// Only students can access this page
if (!isset($_SESSION["user_id"]) || $_SESSION["role"] !== "student") {
    Header("Location: ../View/leaderboard.php");
    exit;
}

$database   = new db();
$connection = $database->connection();

$results = $database->getStudentResults($connection, $_SESSION["user_id"]);
?>
