<?php
// ─────────────────────────────────────────────────────────────────────────────
// Model/db.php
// Database class — follows the same pattern as the repo (class db, connection()
// method returns a mysqli handle, every other method accepts $connection).
//
// ALL queries use prepared statements (bind_param / bind_value) — no raw
// string concatenation of user-supplied values anywhere.
// ─────────────────────────────────────────────────────────────────────────────

class db
{
    // ── 1. CONNECTION ────────────────────────────────────────────────────────

    /**
     * Open and return a MySQLi connection.
     * Change $db_name to match the shared database name agreed with the team.
     */
    function connection()
    {
        $db_host     = "localhost";
        $db_user     = "root";
        $db_password = "";
        $db_name     = "quiz_platform";   // shared database

        $connection = new mysqli($db_host, $db_user, $db_password, $db_name);

        if ($connection->connect_error) {
            die("Database connection failed: " . $connection->connect_error);
        }

        // Ensure UTF-8 throughout
        $connection->set_charset("utf8mb4");

        return $connection;
    }

    // ── 2. RESULT PAGE ───────────────────────────────────────────────────────

    /**
     * Fetch one attempt row plus its quiz info.
     * Also verifies the attempt belongs to $student_id (security check).
     *
     * @return array|null  Associative row or null if not found / wrong owner.
     */
    function getAttemptWithQuiz($connection, int $attempt_id, int $student_id)
    {
        $sql = "SELECT  a.id         AS attempt_id,
                        a.score,
                        a.started_at,
                        a.completed_at,
                        q.id         AS quiz_id,
                        q.title,
                        q.total_marks,
                        q.time_limit_minutes,
                        TIMESTAMPDIFF(SECOND, a.started_at, a.completed_at) AS duration_seconds
                FROM    attempts a
                JOIN    quizzes  q ON q.id = a.quiz_id
                WHERE   a.id         = ?
                  AND   a.student_id = ?
                  AND   a.completed_at IS NOT NULL";

        $stmt = $connection->prepare($sql);
        $stmt->bind_param("ii", $attempt_id, $student_id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();   // null if attempt not found / wrong student
    }

    /**
     * Return per-question breakdown for the result page.
     * Each row includes: question text, marks worth, what the student picked,
     * whether it was correct, and what the correct option was.
     *
     * @return array  Array of associative rows, ordered by question order_index.
     */
    function getQuestionBreakdown($connection, int $attempt_id)
    {
        $sql = "SELECT  q.question_text,
                        q.marks,
                        sel.option_text  AS selected_text,
                        sel.is_correct   AS is_selected_correct,
                        cor.option_text  AS correct_text
                FROM    answers  ans
                JOIN    questions q   ON q.id  = ans.question_id
                -- The option the student chose
                LEFT JOIN options sel ON sel.id = ans.selected_option_id
                -- The correct option for the same question
                LEFT JOIN options cor ON cor.question_id = q.id
                                      AND cor.is_correct = 1
                WHERE   ans.attempt_id = ?
                ORDER BY q.order_index ASC";

        $stmt = $connection->prepare($sql);
        $stmt->bind_param("i", $attempt_id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // ── 3. STUDENT "MY RESULTS" ──────────────────────────────────────────────

    /**
     * All completed attempts for a student, newest first.
     * Returns quiz title, score, total marks, dates, and duration in seconds.
     *
     * @return array
     */
    function getStudentAttempts($connection, int $student_id)
    {
        $sql = "SELECT  a.id           AS attempt_id,
                        q.title,
                        q.total_marks,
                        a.score,
                        a.started_at,
                        a.completed_at,
                        TIMESTAMPDIFF(SECOND, a.started_at, a.completed_at) AS duration_seconds
                FROM    attempts a
                JOIN    quizzes  q ON q.id = a.quiz_id
                WHERE   a.student_id    = ?
                  AND   a.completed_at IS NOT NULL
                ORDER BY a.completed_at DESC";

        $stmt = $connection->prepare($sql);
        $stmt->bind_param("i", $student_id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // ── 4. INSTRUCTOR ANALYTICS ──────────────────────────────────────────────

    /**
     * Quizzes created by a specific instructor — for the dropdown selector.
     *
     * @return array
     */
    function getInstructorQuizzes($connection, int $instructor_id)
    {
        $sql = "SELECT id, title
                FROM   quizzes
                WHERE  instructor_id = ?
                ORDER BY created_at DESC";

        $stmt = $connection->prepare($sql);
        $stmt->bind_param("i", $instructor_id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * All completed student attempts for one quiz, with student name and duration.
     * The JOIN on quizzes.instructor_id ensures only the owner can see results.
     *
     * @return array  Rows ordered by score DESC.
     */
    function getQuizAttempts($connection, int $quiz_id, int $instructor_id)
    {
        $sql = "SELECT  u.name         AS student_name,
                        a.score,
                        q.total_marks,
                        a.started_at,
                        a.completed_at,
                        TIMESTAMPDIFF(SECOND, a.started_at, a.completed_at) AS duration_seconds
                FROM    attempts a
                JOIN    users    u ON u.id   = a.student_id
                JOIN    quizzes  q ON q.id   = a.quiz_id
                WHERE   a.quiz_id        = ?
                  AND   q.instructor_id  = ?
                  AND   a.completed_at  IS NOT NULL
                ORDER BY a.score DESC";

        $stmt = $connection->prepare($sql);
        $stmt->bind_param("ii", $quiz_id, $instructor_id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Aggregate stats for the summary row: avg, highest, lowest, pass rate.
     * Pass threshold = 60% of total_marks.
     *
     * @return array|null  Single associative row.
     */
    function getQuizSummary($connection, int $quiz_id, int $instructor_id)
    {
        $sql = "SELECT  COUNT(*)                                          AS total_attempts,
                        ROUND(AVG(a.score), 2)                            AS avg_score,
                        MAX(a.score)                                      AS highest_score,
                        MIN(a.score)                                      AS lowest_score,
                        q.total_marks,
                        SUM(CASE WHEN (a.score / q.total_marks) >= 0.60
                                 THEN 1 ELSE 0 END)                       AS passed_count
                FROM    attempts a
                JOIN    quizzes  q ON q.id  = a.quiz_id
                WHERE   a.quiz_id        = ?
                  AND   q.instructor_id  = ?
                  AND   a.completed_at  IS NOT NULL";

        $stmt = $connection->prepare($sql);
        $stmt->bind_param("ii", $quiz_id, $instructor_id);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_assoc();
    }

    // ── 5. LEADERBOARD ───────────────────────────────────────────────────────

    /**
     * Top 10 students by cumulative score across ALL completed attempts.
     * Ties broken by number of attempts (fewer = better rank).
     *
     * @return array  Each row: student_id, name, total_score, attempts_count.
     */
    function getLeaderboard($connection)
    {
        $sql = "SELECT  u.id          AS student_id,
                        u.name,
                        SUM(a.score)  AS total_score,
                        COUNT(a.id)   AS attempts_count
                FROM    attempts a
                JOIN    users    u ON u.id = a.student_id
                WHERE   a.completed_at IS NOT NULL
                  AND   u.role = 'student'
                GROUP BY a.student_id, u.name
                ORDER BY total_score DESC, attempts_count ASC
                LIMIT 10";

        $stmt = $connection->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        return $result->fetch_all(MYSQLI_ASSOC);
    }

    /**
     * Security helper — confirm a quiz belongs to $instructor_id.
     * Used before showing analytics so an instructor can't spy on another's quiz.
     *
     * @return bool
     */
    function verifyQuizOwner($connection, int $quiz_id, int $instructor_id): bool
    {
        $sql = "SELECT id FROM quizzes WHERE id = ? AND instructor_id = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("ii", $quiz_id, $instructor_id);
        $stmt->execute();
        return $stmt->get_result()->num_rows > 0;
    }
}
