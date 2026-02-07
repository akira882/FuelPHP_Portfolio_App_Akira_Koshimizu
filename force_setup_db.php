<?php
/**
 * Force Setup Database for PostgreSQL on Render
 * This script bypasses FuelPHP's MySQL-centric DBUtil and creates tables using native PGSQL.
 */

$db_url = getenv('DATABASE_URL');
if (!$db_url) {
    die("DATABASE_URL not found. Skipping forced setup.\n");
}

$url = parse_url($db_url);
$dsn = sprintf(
    'pgsql:host=%s;port=%d;dbname=%s',
    $url['host'],
    isset($url['port']) ? $url['port'] : 5432,
    ltrim($url['path'], '/')
);

try {
    $pdo = new PDO($dsn, $url['user'], $url['pass']);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connected to PostgreSQL for forced setup.\n";

    // 1. Users Table (Auth Package)
    $pdo->exec("CREATE TABLE IF NOT EXISTS \"users\" (
        \"id\" SERIAL PRIMARY KEY,
        \"username\" VARCHAR(50) NOT NULL UNIQUE,
        \"password\" VARCHAR(255) NOT NULL,
        \"salt\" VARCHAR(32) NOT NULL DEFAULT '',
        \"group\" INTEGER DEFAULT 1,
        \"email\" VARCHAR(255) NOT NULL UNIQUE,
        \"last_login\" VARCHAR(25) DEFAULT '0',
        \"login_hash\" VARCHAR(255) DEFAULT '',
        \"profile_fields\" TEXT,
        \"created_at\" INTEGER DEFAULT 0,
        \"updated_at\" INTEGER DEFAULT 0
    )");
    
    // Existing table support: Add salt column if it was missed during initial setup
    $pdo->exec("ALTER TABLE \"users\" ADD COLUMN IF NOT EXISTS \"salt\" VARCHAR(32) NOT NULL DEFAULT ''");
    echo "Verified 'users' table (including 'salt' column).\n";

    // 2. Projects Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS \"projects\" (
        \"id\" SERIAL PRIMARY KEY,
        \"name\" VARCHAR(255) NOT NULL,
        \"description\" TEXT,
        \"due_date\" INTEGER,
        \"user_id\" INTEGER NOT NULL,
        \"created_at\" INTEGER DEFAULT 0,
        \"updated_at\" INTEGER DEFAULT 0
    )");
    echo "Verified 'projects' table.\n";

    // 3. Tasks Table
    $pdo->exec("CREATE TABLE IF NOT EXISTS \"tasks\" (
        \"id\" SERIAL PRIMARY KEY,
        \"title\" VARCHAR(255) NOT NULL,
        \"content\" TEXT,
        \"user_id\" INTEGER NOT NULL,
        \"project_id\" INTEGER,
        \"done\" BOOLEAN NOT NULL DEFAULT FALSE,
        \"priority\" INTEGER NOT NULL DEFAULT 1,
        \"due_date\" INTEGER,
        \"created_at\" INTEGER DEFAULT 0,
        \"updated_at\" INTEGER DEFAULT 0
    )");
    echo "Verified 'tasks' table.\n";

    // 4. Task Checklists
    $pdo->exec("CREATE TABLE IF NOT EXISTS \"task_checklists\" (
        \"id\" SERIAL PRIMARY KEY,
        \"task_id\" INTEGER NOT NULL,
        \"title\" VARCHAR(255) NOT NULL,
        \"is_completed\" BOOLEAN NOT NULL DEFAULT FALSE,
        \"sort_order\" INTEGER NOT NULL DEFAULT 0,
        \"created_at\" INTEGER DEFAULT 0,
        \"updated_at\" INTEGER DEFAULT 0
    )");
    echo "Verified 'task_checklists' table.\n";

    // 5. Project Members
    $pdo->exec("CREATE TABLE IF NOT EXISTS \"project_members\" (
        \"id\" SERIAL PRIMARY KEY,
        \"project_id\" INTEGER NOT NULL,
        \"user_id\" INTEGER NOT NULL,
        \"role\" VARCHAR(50) NOT NULL DEFAULT 'member',
        \"created_at\" INTEGER DEFAULT 0,
        \"updated_at\" INTEGER DEFAULT 0,
        UNIQUE(\"project_id\", \"user_id\")
    )");
    echo "Verified 'project_members' table.\n";

    // 6. Project Files
    $pdo->exec("CREATE TABLE IF NOT EXISTS \"project_files\" (
        \"id\" SERIAL PRIMARY KEY,
        \"project_id\" INTEGER NOT NULL,
        \"user_id\" INTEGER NOT NULL,
        \"filename\" VARCHAR(255) NOT NULL,
        \"filepath\" VARCHAR(500) NOT NULL,
        \"filesize\" INTEGER NOT NULL,
        \"mimetype\" VARCHAR(100),
        \"created_at\" INTEGER DEFAULT 0,
        \"updated_at\" INTEGER DEFAULT 0
    )");
    echo "Verified 'project_files' table.\n";

    // 7. Sessions Table (for database session driver)
    $pdo->exec("CREATE TABLE IF NOT EXISTS \"sessions\" (
        \"session_id\" varchar(40) NOT NULL PRIMARY KEY,
        \"previous_id\" varchar(40) NOT NULL,
        \"user_agent\" text NOT NULL,
        \"ip_hash\" char(32) NOT NULL DEFAULT '',
        \"created\" int DEFAULT 0 NOT NULL,
        \"updated\" int DEFAULT 0 NOT NULL,
        \"payload\" longtext NOT NULL
    )");
    $pdo->exec("CREATE UNIQUE INDEX IF NOT EXISTS \"previous_id_index\" ON \"sessions\" (\"previous_id\")");
    echo "Verified 'sessions' table.\n";

    echo "Forced database setup completed successfully.\n";

} catch (Exception $e) {
    echo "Error during forced setup: " . $e->getMessage() . "\n";
    exit(1);
}
