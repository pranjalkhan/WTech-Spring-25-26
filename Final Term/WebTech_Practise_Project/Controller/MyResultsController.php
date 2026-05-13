<?php
// ─────────────────────────────────────────────────────────────────────────────
// Controller/MyResultsController.php
// Loads all completed attempts for the current student.
//
// URL: View/my_results.php
// Access: Logged-in students only.
// ─────────────────────────────────────────────────────────────────────────────

require_once "../config/session.php";
require_once "../Model/db.php";

requireRole('student');

$database   = new db();
$connection = $database->connection();

// Fetch all completed attempts for this student
$attempts = $database->getStudentAttempts($connection, (int) $_SESSION['user_id']);

// Pre-compute pass/fail and formatted values for each row
foreach ($attempts as &$row) {
    $pct          = $row['total_marks'] > 0
                    ? round(($row['score'] / $row['total_marks']) * 100)
                    : 0;
    $row['pct']    = $pct;
    $row['passed'] = $pct >= 60;

    // Duration
    $sec          = (int) $row['duration_seconds'];
    $row['duration'] = intdiv($sec, 60) . ' min ' . ($sec % 60) . ' sec';

    // Friendly date
    $row['date_taken'] = date('d M Y, h:i A', strtotime($row['completed_at']));
}
unset($row); // Break the reference

// Summary totals for the top widget
$total_attempts = count($attempts);
$total_score    = array_sum(array_column($attempts, 'score'));
$passed_count   = count(array_filter($attempts, fn($r) => $r['passed']));
