<?php
class db {

    // ── CONNECT TO DATABASE ──────────────────────────────────────────
    function connection() {
        $db_host     = "localhost";
        $db_user     = "root";
        $db_password = "";
        $db_name     = "quiz_platform";

        $connection = new mysqli($db_host, $db_user, $db_password, $db_name);

        if ($connection->connect_error) {
            die("Connection Failed: " . $connection->connect_error);
        }

        return $connection;
    }

    // ── GET SINGLE ATTEMPT + QUIZ INFO ──────────────────────────────
    function getAttempt($connection, $attempt_id) {
        $sql  = "SELECT a.*, q.title, q.total_marks
                 FROM attempts a
                 JOIN quizzes q ON a.quiz_id = q.id
                 WHERE a.id = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("i", $attempt_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    // ── GET QUESTION-BY-QUESTION BREAKDOWN ──────────────────────────
    function getAnswerBreakdown($connection, $attempt_id) {
        $sql  = "SELECT q.question_text,
                        sel.option_text AS selected_answer,
                        sel.is_correct,
                        cor.option_text AS correct_answer
                 FROM answers ans
                 JOIN questions q   ON ans.question_id       = q.id
                 JOIN options   sel ON ans.selected_option_id = sel.id
                 JOIN options   cor ON cor.question_id = q.id AND cor.is_correct = 1
                 WHERE ans.attempt_id = ?";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("i", $attempt_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // ── GET ALL ATTEMPTS FOR A STUDENT ──────────────────────────────
    function getStudentResults($connection, $student_id) {
        $sql  = "SELECT a.id, a.score, a.started_at, a.completed_at,
                        TIMEDIFF(a.completed_at, a.started_at) AS duration,
                        q.title, q.total_marks
                 FROM attempts a
                 JOIN quizzes q ON a.quiz_id = q.id
                 JOIN users attempt_user ON a.student_id = attempt_user.id
                 LEFT JOIN users session_user ON session_user.id = ?
                 WHERE (a.student_id = ?
                        OR (session_user.email IS NOT NULL
                            AND session_user.role = 'student'
                            AND attempt_user.email = session_user.email))
                   AND a.completed_at IS NOT NULL
                 ORDER BY a.completed_at DESC";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("ii", $student_id, $student_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // ── GET INSTRUCTOR'S QUIZ LIST ───────────────────────────────────
    function getInstructorQuizzes($connection, $instructor_id) {
        $sql  = "SELECT q.id, q.title, COUNT(a.id) AS attempt_count
                 FROM quizzes q
                 LEFT JOIN attempts a
                    ON a.quiz_id = q.id AND a.completed_at IS NOT NULL
                 LEFT JOIN users session_user ON session_user.id = ?
                 LEFT JOIN users quiz_owner ON quiz_owner.id = q.instructor_id
                 WHERE q.instructor_id = ?
                    OR (session_user.email IS NOT NULL
                        AND session_user.role = 'instructor'
                        AND quiz_owner.email = session_user.email)
                 GROUP BY q.id, q.title, q.created_at
                 ORDER BY attempt_count DESC, q.created_at DESC, q.id DESC";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("ii", $instructor_id, $instructor_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // ── GET ALL ATTEMPTS FOR ONE QUIZ (INSTRUCTOR ANALYTICS) ────────
    function getQuizAttempts($connection, $quiz_id) {
        $sql  = "SELECT a.id AS attempt_id, u.name AS student_name, a.score, q.total_marks,
                        TIMEDIFF(a.completed_at, a.started_at) AS duration,
                        a.completed_at
                 FROM attempts a
                 JOIN users   u ON a.student_id = u.id
                 JOIN quizzes q ON a.quiz_id    = q.id
                 WHERE a.quiz_id = ? AND a.completed_at IS NOT NULL
                 ORDER BY a.score DESC";
        $stmt = $connection->prepare($sql);
        $stmt->bind_param("i", $quiz_id);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    // ── GET TOP 10 LEADERBOARD ───────────────────────────────────────
    function getLeaderboard($connection) {
        $sql    = "SELECT u.name,
                          SUM(a.score) AS total_score,
                          COUNT(a.id)  AS total_attempts
                   FROM attempts a
                   JOIN users u ON a.student_id = u.id
                   WHERE a.completed_at IS NOT NULL
                   GROUP BY a.student_id
                   ORDER BY total_score DESC
                   LIMIT 10";
        $result = $connection->query($sql);
        return $result->fetch_all(MYSQLI_ASSOC);
    }
}
?>
