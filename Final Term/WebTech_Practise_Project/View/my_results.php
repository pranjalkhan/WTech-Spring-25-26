<?php
// ─────────────────────────────────────────────────────────────────────────────
// View/my_results.php
// Student "My Results" page — lists all past quiz attempts.
// ─────────────────────────────────────────────────────────────────────────────
include "../Controller/MyResultsController.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Results — QuizPlatform</title>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f0f4f8;
            color: #2d3748;
            min-height: 100vh;
        }

        /* ── Top bar ─────────────────────────────────────────────── */
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
        .container { max-width: 960px; margin: 36px auto; padding: 0 20px 60px; }

        /* ── Summary widgets ─────────────────────────────────────── */
        .widgets {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 16px;
            margin-bottom: 30px;
        }
        .widget {
            background: #fff;
            border-radius: 12px;
            padding: 22px 24px;
            box-shadow: 0 2px 12px rgba(0,0,0,.07);
            border-top: 4px solid;
        }
        .widget.blue  { border-color: #4299e1; }
        .widget.green { border-color: #48bb78; }
        .widget.gold  { border-color: #ecc94b; }
        .widget .label { font-size: .8rem; text-transform: uppercase; letter-spacing: .6px; color: #718096; }
        .widget .value { font-size: 2.2rem; font-weight: 800; margin-top: 6px; color: #2d3748; }

        /* ── Section title ───────────────────────────────────────── */
        h2.section-title {
            font-size: 1.15rem;
            font-weight: 700;
            margin-bottom: 14px;
            border-left: 4px solid #4299e1;
            padding-left: 10px;
            color: #2d3748;
        }

        /* ── Table ───────────────────────────────────────────────── */
        .table-wrap {
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 2px 14px rgba(0,0,0,.08);
        }
        table { width: 100%; border-collapse: collapse; font-size: .92rem; }
        thead tr { background: #2d3748; color: #e2e8f0; }
        thead th {
            padding: 13px 16px;
            text-align: left;
            font-size: .8rem;
            font-weight: 600;
            letter-spacing: .5px;
            text-transform: uppercase;
        }
        tbody tr { border-bottom: 1px solid #edf2f7; transition: background .15s; }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: #f7fafc; }
        tbody td { padding: 14px 16px; }

        /* ── Badges ──────────────────────────────────────────────── */
        .badge {
            display: inline-block;
            padding: 3px 12px;
            border-radius: 999px;
            font-size: .78rem;
            font-weight: 700;
        }
        .badge.pass { background: #c6f6d5; color: #276749; }
        .badge.fail { background: #fed7d7; color: #9b2c2c; }

        /* ── Score bar ───────────────────────────────────────────── */
        .score-bar-wrap {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .score-bar {
            flex: 1;
            height: 8px;
            background: #edf2f7;
            border-radius: 4px;
            overflow: hidden;
        }
        .score-bar-fill {
            height: 100%;
            border-radius: 4px;
            transition: width .4s;
        }
        .bar-green { background: #48bb78; }
        .bar-red   { background: #fc8181; }
        .score-pct { font-size: .82rem; font-weight: 600; white-space: nowrap; }

        /* ── Detail link ─────────────────────────────────────────── */
        .btn-detail {
            padding: 5px 14px;
            background: #ebf8ff;
            color: #2b6cb0;
            border-radius: 6px;
            text-decoration: none;
            font-size: .82rem;
            font-weight: 600;
        }
        .btn-detail:hover { background: #bee3f8; }

        /* ── Empty state ─────────────────────────────────────────── */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #a0aec0;
        }
        .empty-state .icon { font-size: 3rem; display: block; margin-bottom: 12px; }
    </style>
</head>
<body>

<!-- ── Top bar ─────────────────────────────────────────────────────────── -->
<div class="topbar">
    <span class="brand">📝 QuizPlatform</span>
    <nav>
        Welcome, <strong><?php echo htmlspecialchars($_SESSION['name']); ?></strong>
        <a href="leaderboard.php">🏆 Leaderboard</a>
        <a href="../../Task1/View/logout.php">Logout</a>
    </nav>
</div>

<div class="container">

    <!-- ── Summary widgets ─────────────────────────────────────────────── -->
    <div class="widgets">
        <div class="widget blue">
            <div class="label">Total Attempts</div>
            <div class="value"><?php echo $total_attempts; ?></div>
        </div>
        <div class="widget green">
            <div class="label">Quizzes Passed</div>
            <div class="value"><?php echo $passed_count; ?></div>
        </div>
        <div class="widget gold">
            <div class="label">Total Score Earned</div>
            <div class="value"><?php echo $total_score; ?></div>
        </div>
    </div>

    <!-- ── Attempts table ──────────────────────────────────────────────── -->
    <h2 class="section-title">All Quiz Attempts</h2>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Quiz Title</th>
                    <th>Date Taken</th>
                    <th>Duration</th>
                    <th>Score</th>
                    <th>Result</th>
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($attempts)): ?>
                    <tr>
                        <td colspan="7">
                            <div class="empty-state">
                                <span class="icon">📋</span>
                                You haven't completed any quizzes yet. Head to the quiz list to get started!
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($attempts as $i => $row): ?>
                        <tr>
                            <td style="color:#a0aec0;font-size:.85rem;"><?php echo $i + 1; ?></td>

                            <td style="font-weight:600;">
                                <?php echo htmlspecialchars($row['title']); ?>
                            </td>

                            <td style="color:#718096;font-size:.88rem;">
                                <?php echo $row['date_taken']; ?>
                            </td>

                            <td style="color:#718096;font-size:.88rem;">
                                <?php echo $row['duration']; ?>
                            </td>

                            <!-- Score with mini progress bar -->
                            <td>
                                <div class="score-bar-wrap">
                                    <div class="score-bar">
                                        <div class="score-bar-fill <?php echo $row['passed'] ? 'bar-green' : 'bar-red'; ?>"
                                             style="width:<?php echo $row['pct']; ?>%">
                                        </div>
                                    </div>
                                    <span class="score-pct">
                                        <?php echo $row['score']; ?>/<?php echo $row['total_marks']; ?>
                                    </span>
                                </div>
                            </td>

                            <!-- Pass / Fail badge -->
                            <td>
                                <span class="badge <?php echo $row['passed'] ? 'pass' : 'fail'; ?>">
                                    <?php echo $row['passed'] ? '✅ Pass' : '❌ Fail'; ?>
                                </span>
                            </td>

                            <!-- Link to detailed result -->
                            <td>
                                <a class="btn-detail"
                                   href="result.php?attempt_id=<?php echo $row['attempt_id']; ?>">
                                    View →
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

</div><!-- /container -->
</body>
</html>
