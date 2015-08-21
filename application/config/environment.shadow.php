<?php
/**
 * (SHADOW FILE: COPY THE CONTENTS OF THIS TO "environment.php" FILE CHANGING VALUES AS NEEDED FOR YOUR LOCAL SERVER)
 */

/**
 *---------------------------------------------------------------
 * APPLICATION ENVIRONMENT
 *---------------------------------------------------------------
 *
 * You can load different configurations depending on your
 * current environment. Setting the environment also influences
 * things like logging and error reporting.
 *
 * This can be set to anything, but default usage is:
 *
 *     development
 *     qa
 *     production
 *
 */
define('ENVIRONMENT', 'development');

/** 'true' if the system should write to syslog, else it will write to the PHP error_log */
define('USE_SYSLOG', 'false');

/** in addition to logging error messages, 'true' will also log debug level messages */
define('LOG_DEBUG_MESSAGES', 'true');

/** Facebook OAuth Apps */
define('FACEBOOK_APP_KEY', 'Facebook APP Key Here');
define('FACEBOOK_SECRET_KEY', 'Facebook Secret Here');

/** LinkedIn OAuth App */
define('LINKEDIN_APP_KEY', 'LinkedIn App Key Here');
define('LINKEDIN_SECRET_KEY', 'LinkedIn Secret Key Here');
define('LINKEDIN_OAUTH_TOKEN', 'LinkedIn Token');
define('LINKEDIN_OAUTH_SECRET', 'LinkedIn Secret');

/** Static resources prepend/append */
define('STATIC_RESOURCES_PREPEND', $_SERVER['SERVER_NAME'].'/');
define('STATIC_RESOURCES_APPEND', '123457890');

/** Google Re-Captcha public and private keys */
define('RECAPTCHA_PUBLIC_KEY', 'Google Recaptcha Key HERE');
define('RECAPTCHA_PRIVATE_KEY', 'Google Recaptcha Private Key HERE');

/** Google Analytics Website Identifiers */
define('GANALYTIC_CODE', 'UA-XXX-X');
define('GANALYTIC_CROSS', 'UA-XXX-X');
define('GOOGLE_SITE_VERIFICATION_ID', 'GOOGLE SITE VERIFY ID HERE');