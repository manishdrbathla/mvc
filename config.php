<?php

/**
 * File: config.php.
 * Author: Self
 * Standard: PSR-12. (Use codesniffer or download web code sniffer from www.webcodesniffer.net)
 * Do not change codes without permission.
 * Date: 1/19/2020
 */

$domains = array(
    'vediyum.com', 'dev.vediyum.com', 'staging.vediyum.com', 'localhost'
);

$protocol = ((in_array($_SERVER['HTTP_HOST'], $domains, true)
        && (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'))
    || $_SERVER['SERVER_PORT'] === 443)
    ? 'https://' : 'http://';

// Defining directories paths for use in application.
define('BASE_DIR', str_replace('\\', '/', realpath(__DIR__)) . '/', false);
define('BASE_URL', $protocol . $_SERVER['HTTP_HOST'] . rtrim(dirname($_SERVER['SCRIPT_NAME']), '/.\\') . '/', false);
define('APP_DIR', BASE_DIR . 'App/', false);
define('TEMP_DIR', BASE_DIR . 'App/View/Template/', false);
define('SYSTEM_DIR', BASE_DIR . 'System/', false);
define('LIBRARY_DIR', SYSTEM_DIR . 'Library/', false);
define('ERROR_DIR', BASE_DIR . 'error/', false);

/* Check for Application Environment status on .htaccess, if not found or working, we set it via php. Status needs to be
Development, Staging or Production. If production, this sets error display to off else displays errors in development
and staging.*/

if (!defined('APPLICATION_ENVIRONMENT')) {
    $domain = strtolower($_SERVER['HTTP_HOST']);

    if (in_array($domain, $domains, true)) {
        if ($domain === 'vediyum.com') {
            define('APPLICATION_ENVIRONMENT', 'production', false);
        } else {
            define('APPLICATION_ENVIRONMENT', 'development', false);
        }
    }
}

// Error logging file
define('ERROR_LOGGING_FILE', SYSTEM_DIR . 'Log/errors.log', false);

// Site title, include option
define('SITETITLE', ' - Vediyum.com', false);

// Defining email
define('INFO_MAIL', 'info@vediyum.com', false);
define('PROB_EMAIL', 'problem@vediyum.com', false);

define('DB_HOST', 'localhost');
define('DB_NAME', 'login');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_CHAR', 'utf8');

const SECRET_KEY = 'CCB9EF1657E5E';
