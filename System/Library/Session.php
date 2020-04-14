<?php

/**
 * File: Session.php.
 * Author: Self
 * Standard: PSR-2. (Use codesniffer or download web code sniffer from www.webcodesniffer.net)
 * Do not change codes without permission.
 * Date: 1/3/2020
 */

namespace System\Library;

class Session
{
    private static $sessionStarted = false;

    public static function start()
    {
        if (self::$sessionStarted === false) {
            session_start();
            self::$sessionStarted = true;
        }
    }

    public static function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public static function get($key, $secondKey = false)
    {
        if ($secondKey === true) {
            if (isset($_SESSION[$key][$secondKey])) {
                return $_SESSION[$key][$secondKey];
            }
        } else {
            if (isset($_SESSION[$key])) {
                return $_SESSION[$key];
            }
        }
        return false;
    }

    public static function regenerateSession()
    {
        if (session_status() != PHP_SESSION_ACTIVE) {
            session_start();
        }

        $new_session_id = session_create_id();
        $_SESSION['new_session_id'] = $new_session_id;

        $_SESSION['destroyed'] = time();

        session_write_close();

        session_id($new_session_id);
        //ini_set('session.use_strict_mode', 0);
        session_start();
        //ini_set('session.use_strict_mode', 1);

        // New session does not need them
        unset($_SESSION['destroyed'], $_SESSION['new_session_id']);
    }

    public static function display()
    {
        echo '<pre>';
        print_r($_SESSION);
        echo '</pre>';
    }

    public static function destroy()
    {
        if (self::$sessionStarted === true) {
            session_unset();
            session_destroy();
        }
    }
}