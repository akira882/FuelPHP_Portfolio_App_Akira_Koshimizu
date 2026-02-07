<?php
/**
 * Debug Script: Check Database Tables & Users
 * This script will display the content of 'users' and 'sessions' tables to verify registration status.
 * IMPORTANT: Delete this file after use!
 */

// Basic CSS for readability
$style = "
    body { font-family: sans-serif; padding: 20px; background: #f0f0f0; }
    .container { max-width: 800px; margin: 0 auto; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
    h1 { color: #333; border-bottom: 2px solid #eee; padding-bottom: 10px; }
    h2 { color: #555; margin-top: 30px; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { border: 1px solid #ddd; padding: 10px; text-align: left; }
    th { background: #f9f9f9; font-weight: bold; }
    tr:nth-child(even) { background: #f8f8f8; }
    .status { padding: 5px 10px; border-radius: 4px; font-weight: bold; font-size: 0.9em; }
    .ok { background: #d4edda; color: #155724; }
    .error { background: #f8d7da; color: #721c24; }
    .warning { background: #fff3cd; color: #856404; }
";

echo "<html><head><title>Database Check</title><style>{$style}</style></head><body><div class='container'>";
echo "<h1>Database Verification Tool</h1>";

try {
    // 1. Connect to Database
    $db_url = getenv('DATABASE_URL');
    if (!$db_url) {
        throw new Exception("DATABASE_URL environment variable is not set.");
    }

    $url = parse_url($db_url);
    $dsn = sprintf(
        'pgsql:host=%s;port=%d;dbname=%s',
        $url['host'],
        isset($url['port']) ? $url['port'] : 5432,
        ltrim($url['path'], '/')
    );
    
    $pdo = new PDO($dsn, $url['user'], $url['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "<p class='status ok'>✅ Connected to Database successfully.</p>";

    // 2. Check 'users' table
    echo "<h2>User Accounts (Registered Users)</h2>";
    $stmt = $pdo->query("SELECT id, username, email, created_at, last_login, updated_at FROM users ORDER BY id DESC LIMIT 20");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($users) > 0) {
        echo "<table><thead><tr><th>ID</th><th>Username</th><th>Email</th><th>Last Login</th><th>Created At</th></tr></thead><tbody>";
        foreach ($users as $user) {
            $created = $user['created_at'] ? date('Y-m-d H:i:s', $user['created_at']) : '-';
            $last_login = ($user['last_login'] && $user['last_login'] !== '0') ? date('Y-m-d H:i:s', $user['last_login']) : 'Never';
            
            echo "<tr>";
            echo "<td>" . htmlspecialchars($user['id']) . "</td>";
            echo "<td>" . htmlspecialchars($user['username']) . "</td>";
            echo "<td>" . htmlspecialchars($user['email']) . "</td>";
            echo "<td>" . htmlspecialchars($last_login) . "</td>";
            echo "<td>" . htmlspecialchars($created) . "</td>";
            echo "</tr>";
        }
        echo "</tbody></table>";
        echo "<p>Found " . count($users) . " user(s).</p>";
    } else {
        echo "<p class='status warning'>⚠️ No users found in the database.</p>";
    }

    // 3. Check 'sessions' table
    echo "<h2>Active Sessions (Login Status)</h2>";
    try {
        $stmt = $pdo->query("SELECT count(*) as count FROM sessions");
        $session_count = $stmt->fetchColumn();
        echo "<p>Total active sessions: <strong>" . $session_count . "</strong></p>";
        
        // Check table structure for payload type
        $stmt = $pdo->query("SELECT data_type FROM information_schema.columns WHERE table_name = 'sessions' AND column_name = 'payload'");
        $type = $stmt->fetchColumn();
        echo "<p>Session Payload Type: <strong>" . htmlspecialchars($type) . "</strong> (Should be 'text')</p>";
        
        if ($type === 'text') {
            echo "<p class='status ok'>✅ Session table structure appears correct.</p>";
        } else {
             echo "<p class='status warning'>⚠️ Session payload type is '{$type}'. If login fails, check this.</p>";
        }

    } catch (Exception $e) {
        echo "<p class='status error'>❌ Failed to query 'sessions' table: " . htmlspecialchars($e->getMessage()) . "</p>";
    }

} catch (Exception $e) {
    echo "<p class='status error'>❌ Error: " . htmlspecialchars($e->getMessage()) . "</p>";
}

echo "</div></body></html>";
