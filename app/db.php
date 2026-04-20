<?php
$host = 'mysql';
$dbname = 'cloudops_tracker';
$user = 'root';
$pass = 'rootpassword';

$pdo = null;
$maxRetries = 30;
$retryDelay = 2; // seconds

for ($i = 1; $i <= $maxRetries; $i++) {
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_TIMEOUT => 3,
        ]);
        break; // success
    } catch (PDOException $e) {
        if ($i === $maxRetries) {
            http_response_code(503);
            die("<div style='font-family:sans-serif;padding:40px;background:#0f172a;color:#f1f5f9;min-height:100vh;'>
                <h2>⚠ Database is still starting up</h2>
                <p>MySQL container is initializing. Please refresh this page in a few seconds.</p>
                <p style='color:#94a3b8;font-size:13px;'>Error: " . htmlspecialchars($e->getMessage()) . "</p>
            </div>");
        }
        sleep($retryDelay);
    }
}
?>
