<?php require __DIR__ . '/layouts/header.php'; ?>

<main class="landing">
    <section class="landing-hero">
        <div class="hero-text">
            <h1>Expert care for your health and wellness.</h1>
            <p>
                MediCare Clinic System helps clinics deliver seamless, compassionate
                care with smart appointment management, secure records, and real-time insights.
            </p>
            <div class="hero-actions">
                <a href="index.php?page=login" class="btn-primary">Book an appointment</a>
                <a href="#learn-more" class="btn-link">Learn more</a>
            </div>
            <div class="hero-tags">
                <span>General check‑ups</span>
                <span>Diagnostics</span>
                <span>Emergency care</span>
                <span>Laboratory tests</span>
            </div>
        </div>
        <div class="hero-visual">
            <div class="dna-card">
                <div class="pill-badge">Wellness +</div>
                <div class="pill-badge alt">Expertise +</div>
                <div class="hero-blob"></div>
                <div class="hero-stats">
                    <h4>Heart rate</h4>
                    <p>Goal 83 bpm · Current 97 bpm</p>
                    <div class="mini-chart"></div>
                </div>
            </div>
            <div class="social-floating">
                <span>IG</span>
                <span>FB</span>
                <span>X</span>
            </div>
        </div>
    </section>

    <section id="learn-more" class="landing-grid">
        <div class="card">
            <h3>For Administrators</h3>
            <p>Control users, appointments, billing, and analytics from a single powerful dashboard.</p>
        </div>
        <div class="card">
            <h3>For Staff</h3>
            <p>Doctors and receptionists get focused tools to manage daily clinical workflows.</p>
        </div>
        <div class="card">
            <h3>For Patients</h3>
            <p>Patients can book visits, view records, pay bills, and stay informed—anytime, anywhere.</p>
        </div>
    </section>
</main>

<footer class="mc-footer">
    <span>&copy; <?php echo date('Y'); ?> MediCare Clinic System</span>
</footer>

