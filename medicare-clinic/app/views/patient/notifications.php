<?php
require __DIR__ . '/../layouts/patient_sidebar.php';
require_once __DIR__ . '/../../../config/database.php';
$pdo = $GLOBALS['pdo'] ?? null;
$user = current_user();
$sidebar = render_patient_sidebar();
$notifications = [];
$unreadCount = 0;
if ($pdo && $user) {
    $stmt = $pdo->prepare("SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$user['id']]);
    $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
    $unreadCount = count(array_filter($notifications, fn($n) => empty($n['read_at'])));
}
$read = !empty($_GET['read']);
?>
<div class="app-shell">
    <?php echo $sidebar; ?>
    <main class="main-content">
        <header class="main-header">
            <h1>Notifications</h1>
            <div class="header-right">
                <?php if ($unreadCount > 0): ?>
                    <a href="index.php?page=patient-notifications&mark_all=1" class="btn-outline">Mark All as Read</a>
                <?php endif; ?>
                <div class="user-info">
                    <span class="role">Patient</span>
                    <span class="name"><?php echo htmlspecialchars($user['name'] ?? 'Patient'); ?></span>
                </div>
            </div>
        </header>

        <?php if ($read): ?>
            <p class="auth-success">Notification(s) marked as read.</p>
        <?php endif; ?>

        <section class="grid-3">
            <div class="summary-card">
                <h4>Unread</h4>
                <div class="summary-value"><?php echo $unreadCount; ?></div>
                <p class="summary-change">Notifications</p>
            </div>
            <div class="summary-card">
                <h4>Total</h4>
                <div class="summary-value"><?php echo count($notifications); ?></div>
                <p class="summary-change">All notifications</p>
            </div>
            <div class="summary-card">
                <h4>—</h4>
                <div class="summary-value">—</div>
                <p class="summary-change">—</p>
            </div>
        </section>

        <section class="panel">
            <div class="panel-header">
                <h3>All Notifications</h3>
            </div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Message</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($notifications as $n): ?>
                    <tr style="<?php echo empty($n['read_at']) ? 'background: var(--mc-cyan-soft);' : ''; ?>">
                        <td><?php echo htmlspecialchars($n['created_at']); ?></td>
                        <td><?php echo htmlspecialchars($n['type']); ?></td>
                        <td><strong><?php echo htmlspecialchars($n['title']); ?></strong><br><?php echo htmlspecialchars($n['message']); ?></td>
                        <td>
                            <?php if (empty($n['read_at'])): ?>
                                <span class="badge">Unread</span>
                            <?php else: ?>
                                <span class="badge cyan">Read</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if (empty($n['read_at'])): ?>
                                <a href="index.php?page=patient-notifications&mark_read=<?php echo (int)$n['id']; ?>" class="btn-outline small">Mark Read</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($notifications)): ?>
                    <tr><td colspan="5">No notifications yet.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </main>
</div>
