<?php include 'db.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filter by Severity | CloudOps Tracker</title>
    <link rel="icon" type="image/svg+xml" href="favicon.svg">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav class="nav">
        <a href="index.php" class="back-link">← Back to Dashboard</a>
    </nav>

    <main class="main-wide">
        <h1 class="page-title">🔍 Filter Incidents by Severity</h1>
        <p class="page-sub">Drill down by priority level</p>

        <form action="" method="post" class="filter-bar">
            <div class="form-group">
                <label>Severity Level</label>
                <select name="severity">
                    <option value="P1">P1 - Critical</option>
                    <option value="P2">P2 - High</option>
                    <option value="P3">P3 - Medium</option>
                    <option value="P4">P4 - Low</option>
                </select>
            </div>
            <button type="submit" name="filter" class="btn">Filter</button>
        </form>

        <?php
        if (isset($_POST['filter'])) {
            $severity = $_POST['severity'];
            $stmt = $pdo->prepare("SELECT * FROM incidents WHERE severity = ? ORDER BY id DESC");
            $stmt->execute([$severity]);
            $rows = $stmt->fetchAll();

            echo '<div class="table-wrap">';
            echo '<div class="table-header">Showing <strong>'.count($rows).'</strong> '.htmlspecialchars($severity).' incident(s)</div>';
            echo '<table>';
            echo '<thead><tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Service</th>
                    <th>Status</th>
                    <th>Assigned To</th>
                    <th>Logged At</th>
                  </tr></thead><tbody>';

            if (count($rows) === 0) {
                echo '<tr><td colspan="6" class="empty-row">No incidents at this severity level 🎉</td></tr>';
            }

            foreach ($rows as $row) {
                $statusClass = strtolower($row['status']);
                echo "<tr>";
                echo "<td class='id'>#{$row['id']}</td>";
                echo "<td><strong>".htmlspecialchars($row['title'])."</strong></td>";
                echo "<td><span class='badge badge-service'>".htmlspecialchars($row['service'])."</span></td>";
                echo "<td><span class='badge badge-$statusClass'>".htmlspecialchars($row['status'])."</span></td>";
                echo "<td class='dim'>".htmlspecialchars($row['assigned_to'] ?: '—')."</td>";
                echo "<td class='timestamp'>".htmlspecialchars($row['timestamp'])."</td>";
                echo "</tr>";
            }
            echo '</tbody></table></div>';
        }
        ?>
    </main>
</body>
</html>
