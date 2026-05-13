<?php include "../Controller/ResultController.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Quiz Result</title>
    <link rel="stylesheet" href="style/result.css">
</head>
<body>

<nav>
    <a href="leaderboard.php">🏆 Leaderboard</a>
    <a href="my_results.php">📋 My Results</a>
</nav>

<div class="container">
    <h2>📝 Quiz Result — <?php echo $attempt["title"]; ?></h2>

    <div class="score-box">
        Score: <?php echo $score; ?> / <?php echo $total; ?>
        (<?php echo round($percent, 1); ?>%)
    </div>

    <div class="banner <?php echo $pass ? "pass" : "fail"; ?>">
        <?php echo $pass ? "✅ PASS" : "❌ FAIL"; ?>
    </div>

    <h3>Question Breakdown</h3>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Question</th>
                <th>Your Answer</th>
                <th>Correct Answer</th>
                <th>Result</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($breakdown as $i => $row): ?>
            <tr class="<?php echo $row["is_correct"] ? "correct" : "wrong"; ?>">
                <td><?php echo $i + 1; ?></td>
                <td><?php echo htmlspecialchars($row["question_text"]); ?></td>
                <td><?php echo htmlspecialchars($row["selected_answer"]); ?></td>
                <td><?php echo htmlspecialchars($row["correct_answer"]); ?></td>
                <td><?php echo $row["is_correct"] ? "✅" : "❌"; ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

</body>
</html>
