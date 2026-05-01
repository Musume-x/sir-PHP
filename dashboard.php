<?php
$sessionPath = __DIR__ . '/sessions';
if (!is_dir($sessionPath)) {
    mkdir($sessionPath, 0755, true);
}
session_save_path($sessionPath);
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
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

<h1>Welcome, <?php echo htmlspecialchars($_SESSION["username"]); ?>!</h1>
<a href="logout.php">Logout</a>

</body>
</html>