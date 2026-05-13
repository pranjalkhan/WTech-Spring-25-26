<?php
// ─────────────────────────────────────────────────────────────────────────────
// Controller/ResultController.php
// Handles the Post-Attempt Result page.
//
// URL: View/result.php?attempt_id=<int>
// Access: Logged-in students only (the student who owns the attempt).
// ─────────────────────────────────────────────────────────────────────────────

require_once "../config/session.php";
require_once "../Model/db.php";

// ── Auth guard ──────────────────────────────────────────────────────────────
requireAuth();   // Redirect to login if not logged in

// Students are the only ones who view their own results.
// Instructors can see results via the analytics page instead.
if (!hasRole('student')) {
    header("Location: analytics.php");
    exit();
}

// ── Input validation ─────────────────────────────────────────────────────────
// attempt_id must be a positive integer supplied in the query string.
$attempt_id = isset($_GET['attempt_id']) ? (int) $_GET['attempt_id'] : 0;

if ($attempt_id <= 0) {
    // Invalid or missing — send back to their results list
    header("Location: my_results.php");
    exit();
}

// ── Query ────────────────────────────────────────────────────────────────────
$database   = new db();
$connection = $database->connection();

// verifyAttemptWithQuiz checks BOTH attempt_id AND student_id, so
// a student can never view another student's result by guessing the ID.
$attempt = $database->getAttemptWithQuiz($connection, $attempt_id, (int) $_SESSION['user_id']);

if (!$attempt) {
    // Not found or belongs to someone else
    header("Location: my_results.php");
    exit();
}

// ── Data for the view ─────────────────────────────────────────────────────────
$breakdown   = $database->getQuestionBreakdown($connection, $attempt_id);
$score       = (int) $attempt['score'];
$total_marks = (int) $attempt['total_marks'];
$percentage  = $total_marks > 0 ? round(($score / $total_marks) * 100) : 0;
$passed      = $percentage >= 60;

// Human-readable duration: "2 min 35 sec"
$dur_sec  = (int) $attempt['duration_seconds'];
$dur_min  = intdiv($dur_sec, 60);
$dur_rem  = $dur_sec % 60;
$duration = "{$dur_min} min {$dur_rem} sec";
