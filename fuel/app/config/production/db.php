<?php
/**
 * -----------------------------------------------------------------------------
 *  Database settings for production environment
 * -----------------------------------------------------------------------------
 *
 *  These settings get merged with the global settings.
 *
 */

$db_url = getenv('DATABASE_URL');
if ($db_url) {
    // Parse DATABASE_URL (format: postgres://user:pass@host:port/dbname)
    $url = parse_url($db_url);
    
    $dsn = sprintf(
        'pgsql:host=%s;port=%d;dbname=%s',
        $url['host'],
        isset($url['port']) ? $url['port'] : 5432,
        ltrim($url['path'], '/')
    );
    
    $username = $url['user'];
    $password = $url['pass'];
} else {
    // Fallback for local production testing
    $dsn = 'mysql:host=localhost;dbname=fuel_prod';
    $username = 'fuel_app';
    $password = 'super_secret_password';
}

return array(
	'default' => array(
		'type'       => $db_url ? 'pdo' : 'mysqli',
		'connection' => array(
			'dsn'      => $dsn,
			'username' => $username,
			'password' => $password,
		),
		'identifier' => $db_url ? '"' : '`',
		'table_prefix' => '',
		'charset'      => 'utf8',
		'enable_cache' => true,
		'profiling'    => false,
	),
);
