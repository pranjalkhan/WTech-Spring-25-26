<?php
// ─────────────────────────────────────────────────────────────────────────────
// Controller/AnalyticsController.php
// Instructor analytics: quiz dropdown → attempt table → summary row.
//
// URL: View/analytics.php  (GET for dropdown, same page for table)
//      ?quiz_id=<int>  to load a specific quiz's data
// Access: Instructors only.
// ─────────────────────────────────────────────────────────────────────────────

require_once "../config/session.php";
require_once "../Model/db.php";

requireRole('instructor');

$instructor_id = (int) $_SESSION['user_id'];

$database   = new db();
$connection = $database->connection();

// ── Quiz dropdown ─────────────────────────────────────────────────────────────
$quizzes  = $database->getInstructorQuizzes($connection, $instructor_id);
$error    = "";
$attempts = [];
$summary  = null;
$selected_quiz_id    = 0;
$selected_quiz_title = "";

// ── When a quiz is selected via the form ─────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['quiz_id'])) {
    $selected_quiz_id = (int) $_GET['quiz_id'];

    // Security: confirm this quiz belongs to the logged-in instructor
    if ($selected_quiz_id > 0 && $database->verifyQuizOwner($connection, $selected_quiz_id, $instructor_id)) {

        $attempts = $database->getQuizAttempts($connection, $selected_quiz_id, $instructor_id);
        $summary  = $database->getQuizSummary($connection, $selected_quiz_id, $instructor_id);

        // Enrich each attempt row
        foreach ($attempts as &$row) {
            $pct          = $row['total_marks'] > 0
                            ? round(($row['score'] / $row['total_marks']) * 100)
                            : 0;
            $row['pct']    = $pct;
            $row['passed'] = $pct >= 60;

            $sec             = (int) $row['duration_seconds'];
            $row['duration'] = intdiv($sec, 60) . ' min ' . ($sec % 60) . ' sec';
            $row['date']     = date('d M Y', strtotime($row['completed_at']));
        }
        unset($row);

        // Find the selected quiz title for the table header
        foreach ($quizzes as $q) {
            if ((int) $q['id'] === $selected_quiz_id) {
                $selected_quiz_title = $q['title'];
                break;
            }
        }

        // Compute pass rate for summary
        if ($summary && $summary['total_attempts'] > 0) {
            $summary['pass_rate'] = round(($summary['passed_count'] / $summary['total_attempts']) * 100);
        } else {
            $summary['pass_rate'] = 0;
        }

    } else {
        $error = "Quiz not found or you do not have permission to view it.";
    }
}
