<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/config/auth.php';
require_once __DIR__ . '/routes/web.php';

// Simple router dispatcher
$page = $_GET['page'] ?? 'home';

ob_start();
route_request($page);
$content = ob_get_clean();

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>MediCare Clinic System</title>
    <link rel="stylesheet" href="public/assets/css/style.css" />
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
<?php echo $content; ?>
</body>
</html>

