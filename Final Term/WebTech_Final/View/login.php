<?php
session_start();

// Set session based on selected role — for Task 4 demo only
if (isset($_POST["role"])) {
    if ($_POST["role"] === "student") {
        $_SESSION["user_id"] = 1;       // matches sample data (Alice)
        $_SESSION["name"]    = "Alice";
        $_SESSION["role"]    = "student";
    } elseif ($_POST["role"] === "instructor") {
        $_SESSION["user_id"] = 4;       // matches sample data (Sir)
        $_SESSION["name"]    = "Sir";
        $_SESSION["role"]    = "instructor";
    }
    Header("Location: leaderboard.php");
    exit;
}

// Logout
if (isset($_GET["logout"])) {
    session_destroy();
    Header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Task 4 — Test Login</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, sans-serif; background: #f4f6f8; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .box { background: #fff; padding: 40px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); text-align: center; width: 320px; }
        h2 { color: #2c3e50; margin-bottom: 8px; }
        p  { color: #888; font-size: 13px; margin-bottom: 24px; }
        button { width: 100%; padding: 12px; margin-bottom: 12px; font-size: 15px; border: none; border-radius: 6px; cursor: pointer; color: #fff; }
        .student    { background: #2d6a4f; }
        .instructor { background: #7b3f00; }
        button:hover { opacity: 0.85; }
    </style>
</head>
<body>
<div class="box">
    <h2>Task 4 — Demo Login</h2>
    <p>Select a role to set the session</p>

    <form method="POST" action="">
        <button class="student" type="submit" name="role" value="student">
            Login as Student
        </button>
        <button class="instructor" type="submit" name="role" value="instructor">
            Login as Instructor
        </button>
    </form>
</div>
</body>
</html>
