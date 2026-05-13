<?php
// ─────────────────────────────────────────────────────────────────────────────
// View/analytics.php
// Instructor Analytics — select quiz → see all attempts + class stats.
// ─────────────────────────────────────────────────────────────────────────────
include "../Controller/AnalyticsController.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics — QuizPlatform</title>
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
        .container { max-width: 980px; margin: 36px auto; padding: 0 20px 60px; }

        /* ── Quiz selector card ───────────────────────────────────── */
        .selector-card {
            background: #fff;
            border-radius: 12px;
            padding: 28px 28px;
            box-shadow: 0 2px 14px rgba(0,0,0,.08);
            margin-bottom: 28px;
            display: flex;
            gap: 14px;
            align-items: flex-end;
            flex-wrap: wrap;
        }
        .selector-card label {
            display: block;
            font-size: .82rem;
            font-weight: 600;
            color: #718096;
            margin-bottom: 6px;
            text-transform: uppercase;
            letter-spacing: .5px;
        }
        .selector-card select {
            padding: 10px 14px;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            font-size: .95rem;
            color: #2d3748;
            background: #f7fafc;
            min-width: 320px;
            cursor: pointer;
            outline: none;
        }
        .selector-card select:focus { border-color: #4299e1; }
        .btn-submit {
            padding: 10px 24px;
            background: #4299e1;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: .95rem;
            font-weight: 600;
            cursor: pointer;
        }
        .btn-submit:hover { background: #3182ce; }

        /* ── Error banner ─────────────────────────────────────────── */
        .error-box {
            background: #fff5f5;
            border: 1px solid #fc8181;
            border-radius: 8px;
            padding: 14px 18px;
            color: #c53030;
            font-size: .92rem;
            margin-bottom: 20px;
        }

        /* ── Section title ───────────────────────────────────────── */
        h2.section-title {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 14px;
            border-left: 4px solid #4299e1;
            padding-left: 10px;
        }

        /* ── Summary widgets ─────────────────────────────────────── */
        .widgets {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin-bottom: 24px;
        }
        @media (max-width: 700px) { .widgets { grid-template-columns: repeat(2, 1fr); } }
        .widget {
            background: #fff;
            border-radius: 12px;
            padding: 18px 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,.07);
            border-top: 4px solid;
        }
        .widget.blue   { border-color: #4299e1; }
        .widget.green  { border-color: #48bb78; }
        .widget.red    { border-color: #fc8181; }
        .widget.purple { border-color: #9f7aea; }
        .widget .label { font-size: .78rem; text-transform: uppercase; letter-spacing: .6px; color: #718096; }
        .widget .value { font-size: 1.9rem; font-weight: 800; margin-top: 6px; }

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
        tbody td { padding: 13px 16px; }

        /* Summary footer row */
        tfoot tr { background: #ebf8ff; border-top: 2px solid #bee3f8; }
        tfoot td {
            padding: 14px 16px;
            font-weight: 700;
            font-size: .88rem;
            color: #2b6cb0;
        }

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

        /* Score bar */
        .score-bar-wrap { display: flex; align-items: center; gap: 10px; }
        .score-bar { flex:1; height:7px; background:#edf2f7; border-radius:4px; overflow:hidden; }
        .score-bar-fill { height:100%; border-radius:4px; }
        .bar-green { background: #48bb78; }
        .bar-red   { background: #fc8181; }
        .score-txt { font-size:.82rem; font-weight:600; white-space:nowrap; }

        /* ── Empty state ─────────────────────────────────────────── */
        .empty-state { text-align:center; padding:50px 20px; color:#a0aec0; }
        .empty-state .icon { font-size:2.8rem; display:block; margin-bottom:10px; }

        /* ── No quiz selected placeholder ────────────────────────── */
        .placeholder {
            background: #fff;
            border-radius: 12px;
            padding: 60px 40px;
            text-align: center;
            box-shadow: 0 2px 14px rgba(0,0,0,.08);
            color: #a0aec0;
        }
        .placeholder .icon { font-size: 3rem; display: block; margin-bottom: 14px; }
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

    <!-- ── Quiz selector form ──────────────────────────────────────────── -->
    <div class="selector-card">
        <form method="GET" action="" style="display:flex;gap:14px;align-items:flex-end;flex-wrap:wrap;">
            <div>
                <label for="quiz_id">Select Quiz to Analyse</label>
                <select name="quiz_id" id="quiz_id">
                    <option value="">— Choose a quiz —</option>
                    <?php foreach ($quizzes as $quiz): ?>
                        <option value="<?php echo $quiz['id']; ?>"
                            <?php echo ($selected_quiz_id === (int)$quiz['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($quiz['title']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn-submit">Load Analytics →</button>
        </form>
    </div>

    <!-- ── Error ───────────────────────────────────────────────────────── -->
    <?php if ($error): ?>
        <div class="error-box">⚠️ <?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <!-- ── Results area ────────────────────────────────────────────────── -->
    <?php if ($selected_quiz_id > 0 && !$error): ?>

        <!-- 4 summary stat widgets -->
        <?php if ($summary): ?>
        <div class="widgets">
            <div class="widget blue">
                <div class="label">Total Attempts</div>
                <div class="value"><?php echo $summary['total_attempts']; ?></div>
            </div>
            <div class="widget green">
                <div class="label">Class Average</div>
                <div class="value"><?php echo $summary['avg_score'] ?? '—'; ?></div>
            </div>
            <div class="widget purple">
                <div class="label">Highest Score</div>
                <div class="value"><?php echo $summary['highest_score'] ?? '—'; ?></div>
            </div>
            <div class="widget red">
                <div class="label">Pass Rate</div>
                <div class="value"><?php echo $summary['pass_rate']; ?>%</div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Attempts table -->
        <h2 class="section-title">
            Student Attempts — <?php echo htmlspecialchars($selected_quiz_title); ?>
        </h2>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Student Name</th>
                        <th>Date</th>
                        <th>Duration</th>
                        <th>Score</th>
                        <th>Result</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($attempts)): ?>
                        <tr>
                            <td colspan="6">
                                <div class="empty-state">
                                    <span class="icon">📭</span>
                                    No students have completed this quiz yet.
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($attempts as $i => $row): ?>
                            <tr>
                                <td style="color:#a0aec0;font-size:.85rem;"><?php echo $i + 1; ?></td>

                                <td style="font-weight:600;">
                                    <?php echo htmlspecialchars($row['student_name']); ?>
                                </td>

                                <td style="color:#718096;font-size:.88rem;">
                                    <?php echo $row['date']; ?>
                                </td>

                                <td style="color:#718096;font-size:.88rem;">
                                    <?php echo $row['duration']; ?>
                                </td>

                                <!-- Score bar -->
                                <td>
                                    <div class="score-bar-wrap">
                                        <div class="score-bar">
                                            <div class="score-bar-fill <?php echo $row['passed'] ? 'bar-green' : 'bar-red'; ?>"
                                                 style="width:<?php echo $row['pct']; ?>%"></div>
                                        </div>
                                        <span class="score-txt">
                                            <?php echo $row['score']; ?>/<?php echo $row['total_marks']; ?>
                                        </span>
                                    </div>
                                </td>

                                <!-- Pass/Fail badge -->
                                <td>
                                    <span class="badge <?php echo $row['passed'] ? 'pass' : 'fail'; ?>">
                                        <?php echo $row['passed'] ? '✅ Pass' : '❌ Fail'; ?>
                                    </span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>

                <!-- ── Summary footer row ──────────────────────────── -->
                <?php if ($summary && $summary['total_attempts'] > 0): ?>
                <tfoot>
                    <tr>
                        <td colspan="3" style="text-transform:uppercase;letter-spacing:.5px;">
                            📊 Class Summary
                        </td>
                        <td>—</td>
                        <td>
                            Avg: <?php echo $summary['avg_score']; ?> &nbsp;|&nbsp;
                            High: <?php echo $summary['highest_score']; ?> &nbsp;|&nbsp;
                            Low: <?php echo $summary['lowest_score']; ?>
                        </td>
                        <td>
                            Pass Rate: <?php echo $summary['pass_rate']; ?>%
                            (<?php echo $summary['passed_count']; ?>/<?php echo $summary['total_attempts']; ?>)
                        </td>
                    </tr>
                </tfoot>
                <?php endif; ?>
            </table>
        </div>

    <?php else: ?>
        <!-- Placeholder when no quiz is selected yet -->
        <div class="placeholder">
            <span class="icon">📈</span>
            Select a quiz from the dropdown above to view student performance analytics.
        </div>
    <?php endif; ?>

</div><!-- /container -->
</body>
</html>
