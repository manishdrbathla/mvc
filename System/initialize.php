<?php
/**
 * File: initialize.php.
 * Author: Self
 * Standard: PSR-2.
 * Do not change codes without permission.
 * Date: 2/22/2020
 */


// Check web server php version. Min. requirement PHP 7.0.0 plus.
if (PHP_VERSION_ID < 70000) {
    exit('PHP 7.0.0+ Required');
}

// Set default time zone
date_default_timezone_set('Asia/Kolkata');

//// Enforce SSL connection
//if ((empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on')) {
//    header('Location: https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']);
//    exit();
//}