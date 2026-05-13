<?php
// ─────────────────────────────────────────────────────────────────────────────
// View/leaderboard.php
// Public leaderboard — top 10 students by cumulative score.
// Auto-refreshes the table every 30 seconds via AJAX + setInterval.
// Shows a countdown ("Refreshing in Xs") beside the table header.
// ─────────────────────────────────────────────────────────────────────────────
include "../Controller/LeaderboardController.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard — QuizPlatform</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f4f8;
            color: #2d3748;
            min-height: 100vh;
        }

        /* ── Topbar ──────────────────────────────────────────────── */
        .topbar {
            background: #1a202c;
            color: #e2e8f0;
            padding: 14px 32px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .topbar .brand { font-size: 1.1rem; font-weight: 700; }
        .topbar nav a {
            color: #63b3ed;
            text-decoration: none;
            font-size: .9rem;
            margin-left: 18px;
        }
        .topbar nav a:hover { text-decoration: underline; }

        /* ── Layout ──────────────────────────────────────────────── */
        .container { max-width: 760px; margin: 40px auto; padding: 0 20px 60px; }

        /* ── Page hero ───────────────────────────────────────────── */
        .hero {
            text-align: center;
            margin-bottom: 32px;
        }
        .hero .trophy { font-size: 3.5rem; display: block; margin-bottom: 10px; }
        .hero h1 {
            font-size: 2rem;
            font-weight: 800;
            color: #2d3748;
            letter-spacing: -1px;
        }
        .hero p { color: #718096; margin-top: 6px; }

        /* ── Table header row with countdown ─────────────────────── */
        .table-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }
        .table-header h2 {
            font-size: 1.05rem;
            font-weight: 700;
            border-left: 4px solid #ecc94b;
            padding-left: 10px;
            color: #2d3748;
        }
        .refresh-countdown {
            font-size: .82rem;
            color: #718096;
            background: #fff;
            border: 1px solid #e2e8f0;
            padding: 5px 14px;
            border-radius: 999px;
        }
        .refresh-countdown.refreshing {
            color: #2b6cb0;
            border-color: #bee3f8;
            background: #ebf8ff;
        }

        /* ── Table wrapper ───────────────────────────────────────── */
        .table-wrap {
            background: #fff;
            border-radius: 14px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,.10);
        }
        table { width: 100%; border-collapse: collapse; font-size: .95rem; }

        /* Header row */
        thead tr { background: #1a202c; }
        thead th {
            padding: 14px 18px;
            text-align: left;
            color: #e2e8f0;
            font-size: .8rem;
            font-weight: 600;
            letter-spacing: .5px;
            text-transform: uppercase;
        }

        /* Body rows */
        tbody tr {
            border-bottom: 1px solid #edf2f7;
            transition: background .15s;
        }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: #f7fafc; }
        tbody td { padding: 16px 18px; }

        /* ── Rank medal ──────────────────────────────────────────── */
        .rank {
            font-size: 1.4rem;
            text-align: center;
            width: 52px;
        }
        .rank-num {
            font-weight: 800;
            font-size: 1.1rem;
            color: #a0aec0;
        }

        /* ── Student name cell ───────────────────────────────────── */
        .student-name {
            font-weight: 700;
            font-size: 1rem;
            color: #2d3748;
        }
        .student-name.current-user {
            color: #2b6cb0;
        }
        .you-badge {
            display: inline-block;
            margin-left: 8px;
            background: #bee3f8;
            color: #2b6cb0;
            padding: 2px 8px;
            border-radius: 4px;
            font-size: .72rem;
            font-weight: 700;
            vertical-align: middle;
        }

        /* ── Score display ────────────────────────────────────────── */
        .score-col {
            font-size: 1.2rem;
            font-weight: 800;
            color: #2d3748;
        }
        .attempts-col {
            font-size: .85rem;
            color: #a0aec0;
        }

        /* Top 3 row accents */
        tbody tr:nth-child(1) { background: linear-gradient(90deg, #fff9db 0%, #fff 100%); }
        tbody tr:nth-child(2) { background: linear-gradient(90deg, #f7fafc 0%, #fff 100%); }
        tbody tr:nth-child(3) { background: linear-gradient(90deg, #fff5f5 0%, #fff 100%); }

        /* ── Empty state ─────────────────────────────────────────── */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #a0aec0;
        }
        .empty-state .icon { font-size: 3rem; display: block; margin-bottom: 12px; }

        /* ── Loading overlay ─────────────────────────────────────── */
        #loading-indicator {
            display: none;
            text-align: center;
            padding: 12px;
            color: #4299e1;
            font-size: .88rem;
        }

        /* ── Error toast ─────────────────────────────────────────── */
        #error-toast {
            display: none;
            background: #fff5f5;
            border: 1px solid #fc8181;
            border-radius: 8px;
            padding: 10px 16px;
            color: #c53030;
            font-size: .88rem;
            margin-bottom: 14px;
        }

        /* ── Navigation ──────────────────────────────────────────── */
        .nav-links {
            display: flex;
            justify-content: center;
            gap: 14px;
            margin-top: 24px;
        }
        .btn {
            padding: 9px 22px;
            border-radius: 8px;
            font-size: .9rem;
            font-weight: 600;
            text-decoration: none;
        }
        .btn-ghost   { background: #edf2f7; color: #4a5568; }
        .btn-ghost:hover { background: #e2e8f0; }
    </style>
</head>
<body>

<!-- ── Top bar ─────────────────────────────────────────────────────────── -->
<div class="topbar">
    <span class="brand">📝 QuizPlatform</span>
    <nav>
        <?php if (hasRole('student')): ?>
            <a href="my_results.php">My Results</a>
        <?php elseif (hasRole('instructor')): ?>
            <a href="analytics.php">Analytics</a>
        <?php endif; ?>
        <a href="../../Task1/View/logout.php">Logout</a>
    </nav>
</div>

<div class="container">

    <!-- ── Page hero ──────────────────────────────────────────────────── -->
    <div class="hero">
        <span class="trophy">🏆</span>
        <h1>Leaderboard</h1>
        <p>Top 10 students by cumulative score across all quizzes</p>
    </div>

    <!-- ── Error toast (shown by JS on AJAX failure) ──────────────────── -->
    <div id="error-toast"></div>

    <!-- ── Table header with countdown ────────────────────────────────── -->
    <div class="table-header">
        <h2>Top 10 Students</h2>
        <span id="countdown-badge" class="refresh-countdown">
            🔄 Refreshing in <strong id="countdown-num">30</strong>s
        </span>
    </div>

    <!-- ── Loading indicator ──────────────────────────────────────────── -->
    <div id="loading-indicator">⏳ Refreshing leaderboard...</div>

    <!-- ── The leaderboard table ────────────────────────────────────────
         id="leaderboard-body" is targeted by the AJAX refresh JS below. -->
    <div class="table-wrap" id="leaderboard-wrap">
        <table>
            <thead>
                <tr>
                    <th style="width:60px;text-align:center">Rank</th>
                    <th>Student</th>
                    <th>Total Score</th>
                    <th>Attempts</th>
                </tr>
            </thead>
            <tbody id="leaderboard-body">
                <?php echo buildLeaderboardRows($leaderboard, $current_user_id); ?>
            </tbody>
        </table>
    </div>

    <!-- ── Nav links ─────────────────────────────────────────────────── -->
    <div class="nav-links">
        <?php if (hasRole('student')): ?>
            <a href="my_results.php" class="btn btn-ghost">← My Results</a>
        <?php elseif (hasRole('instructor')): ?>
            <a href="analytics.php" class="btn btn-ghost">← Analytics</a>
        <?php endif; ?>
    </div>

</div><!-- /container -->

<?php
// ── PHP helper: generates <tbody> rows (used on initial render and in API) ──
// We define this here so the AJAX endpoint can also use it if we ever need server-rendered HTML.
// For the AJAX refresh we return JSON and let JS build the rows instead.
function buildLeaderboardRows(array $rows, int $current_user_id): string
{
    if (empty($rows)) {
        return '<tr><td colspan="4">
                    <div class="empty-state">
                        <span class="icon">📭</span>
                        No completed quiz attempts yet. Be the first!
                    </div>
                </td></tr>';
    }

    $medals = ['🥇', '🥈', '🥉'];
    $html   = '';

    foreach ($rows as $i => $row) {
        $rank        = $i + 1;
        $medal       = $medals[$i] ?? '';
        $isMe        = (int) $row['student_id'] === $current_user_id;
        $nameClass   = $isMe ? 'student-name current-user' : 'student-name';
        $youBadge    = $isMe ? '<span class="you-badge">YOU</span>' : '';
        $rankDisplay = $medal ?: "<span class='rank-num'>{$rank}</span>";
        $name        = htmlspecialchars($row['name']);
        $score       = (int) $row['total_score'];
        $attempts    = (int) $row['attempts_count'];

        $html .= "
        <tr>
            <td class='rank'>{$rankDisplay}</td>
            <td>
                <span class='{$nameClass}'>{$name}{$youBadge}</span>
            </td>
            <td class='score-col'>{$score}</td>
            <td class='attempts-col'>{$attempts} quiz" . ($attempts !== 1 ? 'zes' : '') . "</td>
        </tr>";
    }

    return $html;
}
?>

<!-- ──────────────────────────────────────────────────────────────────────── -->
<!-- AJAX AUTO-REFRESH JAVASCRIPT                                            -->
<!-- Fetches GET /Controller/api/leaderboard.php every 30 seconds.          -->
<!-- A setInterval countdown ticks down in the badge beside the table title. -->
<!-- ──────────────────────────────────────────────────────────────────────── -->
<script>
(function () {
    'use strict';

    const REFRESH_INTERVAL = 30;          // seconds between refreshes
    const ENDPOINT = '../Controller/api/leaderboard.php';

    // ── DOM references ────────────────────────────────────────────────────
    const tbody       = document.getElementById('leaderboard-body');
    const countdownEl = document.getElementById('countdown-num');
    const badgeEl     = document.getElementById('countdown-badge');
    const loadingEl   = document.getElementById('loading-indicator');
    const errorToast  = document.getElementById('error-toast');

    let secondsLeft = REFRESH_INTERVAL;

    // ── Medal / rank display helper ──────────────────────────────────────
    const medals = ['🥇', '🥈', '🥉'];

    /**
     * Builds <tbody> HTML from the JSON array returned by the API.
     * @param {Array}  rows           Array of { student_id, name, total_score, attempts_count }
     * @param {number} currentUserId  PHP-embedded current user id for "YOU" highlight
     */
    function buildRows(rows, currentUserId) {
        if (!rows || rows.length === 0) {
            return `<tr><td colspan="4" style="text-align:center;padding:40px;color:#a0aec0;">
                        No completed quiz attempts yet.
                    </td></tr>`;
        }

        return rows.map((row, i) => {
            const rank        = i + 1;
            const medal       = medals[i] || '';
            const rankDisplay = medal
                ? `<span style="font-size:1.4rem">${medal}</span>`
                : `<span class="rank-num">${rank}</span>`;

            const isMe     = parseInt(row.student_id) === currentUserId;
            const nameClass = isMe ? 'student-name current-user' : 'student-name';
            const youBadge  = isMe ? '<span class="you-badge">YOU</span>' : '';
            const attempts  = parseInt(row.attempts_count);
            const pluralQuiz = attempts !== 1 ? 'quizzes' : 'quiz';

            return `
                <tr>
                    <td class="rank">${rankDisplay}</td>
                    <td>
                        <span class="${nameClass}">${escHtml(row.name)}${youBadge}</span>
                    </td>
                    <td class="score-col">${parseInt(row.total_score)}</td>
                    <td class="attempts-col">${attempts} ${pluralQuiz}</td>
                </tr>`;
        }).join('');
    }

    /** Simple HTML-escape to avoid XSS on returned names. */
    function escHtml(str) {
        return str
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');
    }

    /** Show / hide the error toast. */
    function showError(msg) {
        errorToast.textContent = '⚠️ ' + msg;
        errorToast.style.display = 'block';
        setTimeout(() => { errorToast.style.display = 'none'; }, 5000);
    }

    /** Fetch fresh leaderboard data from the JSON API endpoint. */
    function fetchLeaderboard() {
        loadingEl.style.display = 'block';
        badgeEl.classList.add('refreshing');

        // Use XMLHttpRequest to match the coding style used in the repo
        const xhttp = new XMLHttpRequest();

        xhttp.onreadystatechange = function () {
            if (this.readyState !== 4) return;    // Not done yet

            loadingEl.style.display = 'none';
            badgeEl.classList.remove('refreshing');

            if (this.status === 200) {
                try {
                    const json = JSON.parse(this.responseText);

                    if (json.success) {
                        // Rebuild the table rows with fresh data
                        tbody.innerHTML = buildRows(json.data, <?php echo $current_user_id; ?>);
                    } else {
                        showError(json.message || 'Failed to load leaderboard.');
                    }
                } catch (e) {
                    showError('Invalid response from server.');
                }
            } else if (this.status === 401) {
                // Session expired — redirect to login
                window.location.href = '../../Task1/View/login.php';
            } else {
                showError('Server error (' + this.status + '). Retrying in ' + REFRESH_INTERVAL + 's.');
            }
        };

        xhttp.open('GET', ENDPOINT, true);
        xhttp.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
        xhttp.send();
    }

    // ── Countdown ticker ─────────────────────────────────────────────────
    // Decrements every second. When it hits 0, fires fetchLeaderboard()
    // and resets the counter.
    const ticker = setInterval(function () {
        secondsLeft--;
        countdownEl.textContent = secondsLeft;

        if (secondsLeft <= 0) {
            secondsLeft = REFRESH_INTERVAL;   // Reset before fetch (avoid double-trigger)
            fetchLeaderboard();
        }
    }, 1000);    // 1000 ms = 1 second

})();  // IIFE — keeps variables out of global scope
</script>
</body>
</html>
