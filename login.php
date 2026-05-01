<?php
$sessionPath = __DIR__ . '/sessions';
if (!is_dir($sessionPath)) {
    mkdir($sessionPath, 0755, true);
}
session_save_path($sessionPath);
session_start();

require "db.php";

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["username"];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password";
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <script>
        // Basic back/forward navigation guard
        (function () {
            if (!window.history || !window.history.pushState) {
                return;
            }
            // Push a new state so that the first back press stays on the app
            window.addEventListener('load', function () {
                history.pushState({ page: 'stay' }, '', window.location.href);
            });

            window.addEventListener('popstate', function (event) {
                // Whenever user tries to go back/forward, push them back to current page
                if (!event.state || event.state.page === 'stay') {
                    history.pushState({ page: 'stay' }, '', window.location.href);
                }
            });
        })();
    </script>
</head>
<body>

<h2>Login</h2>

<?php if ($error): ?>
    <p style="color:red;"><?php echo $error; ?></p>
<?php endif; ?>

<form method="post" autocomplete="off">
    <label>Username</label><br>
    <input type="text" name="username" required autocomplete="off"><br><br>

    <label>Password</label><br>
    <input type="password" name="password" required autocomplete="new-password"><br><br>

    <button type="submit">Login</button>
</form>

</body>
</html>