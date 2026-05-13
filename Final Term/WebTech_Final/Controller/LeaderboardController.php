<?php
session_start();
include "../Model/db.php";

$database   = new db();
$connection = $database->connection();

$leaders = $database->getLeaderboard($connection);
?>
