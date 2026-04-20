<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Log Incident | CloudOps Tracker</title>
    <link rel="icon" type="image/svg+xml" href="favicon.svg">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav class="nav">
        <a href="index.php" class="back-link">← Back to Dashboard</a>
    </nav>

    <main class="main-narrow">
        <h1 class="page-title">🚨 Log New Incident</h1>
        <p class="page-sub">Record a production issue for the on-call team</p>

        <?php
        $message = '';
        if (isset($_POST['submit'])) {
            $title = $_POST['title'];
            $service = $_POST['service'];
            $severity = $_POST['severity'];
            $status = $_POST['status'];
            $assigned_to = $_POST['assigned_to'];
            $description = $_POST['description'];
            $timestamp = date('Y-m-d H:i:s');

            $stmt = $pdo->prepare("INSERT INTO incidents (title, service, severity, status, assigned_to, description, timestamp) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$title, $service, $severity, $status, $assigned_to, $description, $timestamp]);
            $message = "✅ Incident logged successfully!";
        }
        ?>

        <?php if ($message): ?>
            <div class="alert-success"><?= $message ?></div>
        <?php endif; ?>

        <form action="" method="post" class="form-card">
            <div class="form-group">
                <label>Incident Title *</label>
                <input type="text" name="title" required placeholder="e.g. API Gateway returning 503 errors">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Affected Service *</label>
                    <select name="service" required>
                        <option value="API">API</option>
                        <option value="Database">Database</option>
                        <option value="Frontend">Frontend</option>
                        <option value="Auth">Auth</option>
                        <option value="Network">Network</option>
                        <option value="Storage">Storage</option>
                        <option value="Kubernetes">Kubernetes</option>
                        <option value="CI/CD">CI/CD</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Severity *</label>
                    <select name="severity" required>
                        <option value="P1">P1 - Critical</option>
                        <option value="P2">P2 - High</option>
                        <option value="P3" selected>P3 - Medium</option>
                        <option value="P4">P4 - Low</option>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label>Status *</label>
                    <select name="status" required>
                        <option value="Open">Open</option>
                        <option value="Investigating">Investigating</option>
                        <option value="Resolved">Resolved</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Assigned To</label>
                    <input type="text" name="assigned_to" placeholder="e.g. on-call-team">
                </div>
            </div>

            <div class="form-group">
                <label>Description</label>
                <textarea name="description" rows="4" placeholder="Symptoms, error messages, affected users..."></textarea>
            </div>

            <button type="submit" name="submit" class="btn btn-full">Log Incident</button>
        </form>
    </main>
</body>
</html>
