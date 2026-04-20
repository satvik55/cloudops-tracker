<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Incidents | CloudOps Tracker</title>
    <link rel="icon" type="image/svg+xml" href="favicon.svg">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav class="nav">
        <a href="index.php" class="back-link">← Back to Dashboard</a>
    </nav>

    <main class="main-wide">
        <h1 class="page-title">📋 All Incidents</h1>
        <p class="page-sub">Complete incident log, newest first</p>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Service</th>
                        <th>Severity</th>
                        <th>Status</th>
                        <th>Assigned To</th>
                        <th>Logged At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $stmt = $pdo->query("SELECT * FROM incidents ORDER BY id DESC");
                    $rows = $stmt->fetchAll();
                    if (count($rows) === 0) {
                        echo '<tr><td colspan="7" class="empty-row">No incidents logged yet. <a href="add_incident.php">Log the first one</a></td></tr>';
                    }
                    foreach ($rows as $row) {
                        $sevClass = strtolower($row['severity']);
                        $statusClass = strtolower($row['status']);
                        echo "<tr>";
                        echo "<td class='id'>#{$row['id']}</td>";
                        echo "<td><strong>".htmlspecialchars($row['title'])."</strong></td>";
                        echo "<td><span class='badge badge-service'>".htmlspecialchars($row['service'])."</span></td>";
                        echo "<td><span class='badge badge-$sevClass'>".htmlspecialchars($row['severity'])."</span></td>";
                        echo "<td><span class='badge badge-$statusClass'>".htmlspecialchars($row['status'])."</span></td>";
                        echo "<td class='dim'>".htmlspecialchars($row['assigned_to'] ?: '—')."</td>";
                        echo "<td class='timestamp'>".htmlspecialchars($row['timestamp'])."</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
