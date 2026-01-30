<?php
require __DIR__ . '/../layouts/staff_sidebar.php';
$user = current_user();
$role = ucfirst(current_role() ?? 'Staff');
$sidebar = render_staff_sidebar();
?>
<div class="app-shell">
    <?php echo $sidebar; ?>
    <main class="main-content">
        <header class="main-header">
            <h1><?php echo $role; ?> Appointments</h1>
            <div class="header-right">
                <div class="search-bar">
                    <input type="text" placeholder="Search appointments..." />
                </div>
                <button class="btn-primary">+ New Appointment</button>
            </div>
        </header>

        <section class="grid-2">
            <div class="panel">
                <div class="panel-header">
                    <h3>Today's Appointments</h3>
                    <select>
                        <option>All</option>
                        <option>Morning</option>
                        <option>Afternoon</option>
                    </select>
                </div>
                <ul class="appointment-list compact">
                    <li>
                        <div>
                            <strong>09:00 AM</strong>
                            <p>Jacob Jones · Initial Visit</p>
                        </div>
                        <button class="btn-outline small">Open</button>
                    </li>
                    <li>
                        <div>
                            <strong>10:15 AM</strong>
                            <p>Jenny Wilson · Follow‑up</p>
                        </div>
                        <button class="btn-outline small">Open</button>
                    </li>
                    <li>
                        <div>
                            <strong>11:30 AM</strong>
                            <p>Brooklyn Simmons · Lab review</p>
                        </div>
                        <button class="btn-outline small">Open</button>
                    </li>
                </ul>
            </div>

            <div class="panel">
                <div class="panel-header">
                    <h3>Upcoming</h3>
                    <select>
                        <option>Next 7 days</option>
                        <option>Next 30 days</option>
                    </select>
                </div>
                <ul class="appointment-list compact">
                    <li>
                        <div>
                            <strong>Tomorrow · 08:30 AM</strong>
                            <p>Leslie Alexander · General check‑up</p>
                        </div>
                        <span class="badge">Scheduled</span>
                    </li>
                    <li>
                        <div>
                            <strong>Fri · 02:00 PM</strong>
                            <p>Arlene McCoy · Cardiology</p>
                        </div>
                        <span class="badge">Scheduled</span>
                    </li>
                </ul>
            </div>
        </section>
    </main>
</div>

