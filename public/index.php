<?php

/**
 * File: index.php.
 * Author: Self
 * Standard: PSR-2. (Use codesniffer or download web code sniffer from www.webcodesniffer.net)
 * Do not change codes without permission.
 * Date: 1/19/2020
 */

use Aidantwoods\SecureHeaders\SecureHeaders;
use System\Core\Registry;
use System\Core\Router;
use System\Library\Request;
use System\Library\Session;
use ParagonIE\AntiCSRF\AntiCSRF;

// Include Config file
include_once dirname(__DIR__) . '/config.php';

// Load initialize file to check, set environment plus a few core files
require_once SYSTEM_DIR . 'initialize.php';

// Composer
require dirname(__DIR__) . '/vendor/autoload.php';

$headers = new SecureHeaders();
$headers->strictMode();
$headers->cspNonce('script');
$headers->apply();

//Start Session
//Session::start();
session_start();

// Registry
$registry = new Registry();

// Request
$registry->set('request', new Request());

//Anti Csrf
$csrf = new AntiCSRF();
$registry->set('AntiCSRF', new AntiCSRF());

//HTML Purifier
$config = HTMLPurifier_Config::createDefault();
$registry->set('purifier', new HTMLPurifier($config));

// Router AltoRouter (wwww.altorouter.com)
$router = new Router();

$router->map(
    'GET',
    '/',

    static function ($controller = 'home', $action = 'index') use ($registry) {

        $controller .= 'Controller';
        $file = APP_DIR . 'Controller/' . $controller . '.php';

        if (is_file($file)) {
            include_once($file);

            $namespace = 'App\Controller\\';
            $relative_controller = $namespace . $controller;
            $relative_controller = new $relative_controller($registry);

            if (is_callable(array($relative_controller, 'index'))) {
                return call_user_func(array($relative_controller, 'index'));
            }
            echo 'Function not callable';

        } else {
            echo 'file missing';
        }
    }
);

$router->map(
    'GET|POST',
    '/[a:controller]/[a:action]?',
    static function ($controller, $action = null) use ($registry) {

        $controller .= 'Controller';
        $file = APP_DIR . 'Controller/' . $controller . '.php';

        if (is_file($file)) {
            include_once($file);

            $namespace = 'App\Controller\\';
            $relative_controller = $namespace . $controller;
            $relative_controller = new $relative_controller($registry);

            if (!isset($action)) {
                $action = 'index';
            }

            if (is_callable(array($relative_controller, $action))) {
                return call_user_func(array($relative_controller, $action));
            }
            echo 'Function not callable';

        } else {
            //header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found", true, 404);
            include 'error/404.php';
        }
    }
);

$router->map(
    'GET|POST',
    '/[a:controller]/[a:action]/[a:params]',
    function ($controller, $action, $params) use ($registry) {

        $controller .= 'Controller';
        $file = APP_DIR . 'Controller/' . $controller . '.php';

        if (is_file($file)) {
            include_once($file);

            $namespace = 'App\Controller\\';
            $relative_controller = $namespace . $controller;
            $relative_controller = new $relative_controller($registry);

            if (is_callable(array($relative_controller, $action))) {
                return call_user_func(array($relative_controller, $action), $params);
            }
            echo 'Function not callable';

        } else {
            include 'error/404.php';
        }
    }
);

$router->map(
    'GET|POST',
    '/[a:controller]/[a:action]/[a:key]',
    function ($controller, $action, $params) use ($registry) {

        $controller .= 'Controller';
        $file = APP_DIR . 'Controller/' . $controller . '.php';

        if (is_file($file)) {
            include_once($file);

            $namespace = 'App\Controller\\';
            $relative_controller = $namespace . $controller;
            $relative_controller = new $relative_controller($registry);

            if (is_callable(array($relative_controller, $action))) {
                return call_user_func(array($relative_controller->$action($params)));
            }
            echo 'Function not callable';

        } else {
            include 'error/404.php';
        }
    }
);

$match = $router->match();

// call closure or throw error
if ($match && is_callable($match['target'])) {
    call_user_func_array($match['target'], $match['params']);
} else {
    include ERROR_DIR . '404.php';
}

