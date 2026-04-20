<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CloudOps Incident Tracker</title>
    <link rel="icon" type="image/svg+xml" href="favicon.svg">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav class="nav">
        <div class="nav-brand">
            <div class="nav-logo">☁</div>
            <div class="nav-title">
                <h1>CloudOps Incident Tracker</h1>
                <p>Production Incident Management System</p>
            </div>
        </div>
        <span class="nav-version">v1.0 | Dockerized</span>
    </nav>

    <main class="main">
        <?php
        $total = $pdo->query("SELECT COUNT(*) FROM incidents")->fetchColumn();
        $open = $pdo->query("SELECT COUNT(*) FROM incidents WHERE status='Open'")->fetchColumn();
        $investigating = $pdo->query("SELECT COUNT(*) FROM incidents WHERE status='Investigating'")->fetchColumn();
        $resolved = $pdo->query("SELECT COUNT(*) FROM incidents WHERE status='Resolved'")->fetchColumn();
        $p1 = $pdo->query("SELECT COUNT(*) FROM incidents WHERE severity='P1' AND status!='Resolved'")->fetchColumn();
        ?>
        <div class="stats-grid">
            <div class="stat-card">
                <p class="stat-label">Total</p>
                <p class="stat-value"><?= $total ?></p>
            </div>
            <div class="stat-card red">
                <p class="stat-label">P1 Active</p>
                <p class="stat-value"><?= $p1 ?></p>
            </div>
            <div class="stat-card orange">
                <p class="stat-label">Open</p>
                <p class="stat-value"><?= $open ?></p>
            </div>
            <div class="stat-card yellow">
                <p class="stat-label">Investigating</p>
                <p class="stat-value"><?= $investigating ?></p>
            </div>
            <div class="stat-card green">
                <p class="stat-label">Resolved</p>
                <p class="stat-value"><?= $resolved ?></p>
            </div>
        </div>

        <h2 class="section-title">Quick Actions</h2>
        <div class="action-grid">
            <a href="add_incident.php" class="action-card">
                <div class="action-icon">🚨</div>
                <h3>Log New Incident</h3>
                <p>Record a production issue with severity and service details</p>
            </a>
            <a href="fetch_all.php" class="action-card blue">
                <div class="action-icon">📋</div>
                <h3>View All Incidents</h3>
                <p>Full incident log with timestamps and assignments</p>
            </a>
            <a href="fetch_by_severity.php" class="action-card purple">
                <div class="action-icon">🔍</div>
                <h3>Filter by Severity</h3>
                <p>Drill down by P1, P2, P3, or P4 priority</p>
            </a>
        </div>

        <footer class="footer">
            Built with PHP · MySQL · Docker Compose · Deployed on AWS EC2
        </footer>
    </main>
</body>
</html>
