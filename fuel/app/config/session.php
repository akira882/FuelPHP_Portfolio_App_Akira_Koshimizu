<?php
/**
 * Application Session Configuration
 */

return array(
    // Set the session driver to 'db' for persistent storage in PostgreSQL
    'driver' => 'db',

    // Database specific settings
    'db' => array(
        'database' => 'default', // Use the default db connection
        'table'    => 'sessions', // Table name
        'gc_probability' => 5,    // 5% chance of garbage collection
    ),

    // Security settings
    'match_ip'  => false, // Important for cloud load balancers (Render)
    'match_ua'  => true,  // Still check User Agent for security
    'expiration_time' => 7200, // 2 hours
    'expire_on_close' => false,
);
