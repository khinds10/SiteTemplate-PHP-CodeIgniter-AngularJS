<?php 
/*
| -------------------------------------------------------------------
| WEBSITE SERVER CONFIGURATION
| -------------------------------------------------------------------
| Edit this file to suit your DEV environment
| 
| NOTE: create your own "environment.php" file from this file as an example, 
|   do NOT commit "environment.php" to source control if you have a public repository!
*/
 
// WEBSITE Host
define('WEBSITE_HOST', 'www.mywebsite.com');

// MEMCACHED Config
define('MEMCACHED_HOST', 'localhost');
define('MEMCACHED_PORT', 11211);

// MySQL Config 
define('MYSQL_HOST', 'localhost');
define('MYSQL_PORT', 3306);
define('MYSQL_DB', 'photo_net');
define('MYSQL_USER', 'dbadmin');
define('MYSQL_PASS', '0rangUtan');

// Debug Dettings
define('LOG_DEBUG_MESSAGES', 'true');
define('LOG_FILES_LOCATION','/tmp/');
define('SHOW_DEBUG_BACKTRACE', 'true');

// Codeigniter Session Settings
define('SESSION_TYPE', 'memcached');
define('SESSION_COOKIE_NAME', 'website_ci_session');