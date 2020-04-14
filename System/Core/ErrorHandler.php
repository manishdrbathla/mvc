<?php

/**
 * File: BaseController.php.
 * Author: Self
 * Standard: PSR-2.
 * Do not change codes without permission.
 * Date: 2/22/2020
 */

namespace System\Core;

final class ErrorHandler
{
    private $startTime = 0;

    public function __construct()
    {
        $this->startTime = microtime(true);
        ob_start();
        error_reporting(E_ALL);
        ini_set('display_error', 0);
        ini_set('log_errors', 1);

        set_error_handler(array($this, 'scriptError'));
        register_shutdown_function(array($this, 'shutdown'));
    }

    public function scriptError($errno, $errstr, $errfile, $errline)
    {
        if (!headers_sent()) {
            header('HTTP/1.1 500 Internal Server Error');
        }
        if (ob_get_contents() !== false) {
            ob_end_clean();
        }

        switch ($errno) {
            case E_ERROR:
                $errseverity = 'ERROR';
                break;
            case E_WARNING:
                $errseverity = 'WARNING';
                break;
            case E_NOTICE:
                $errseverity = 'NOTICE';
                break;
            case E_CORE_ERROR:
                $errseverity = 'CORE ERROR';
                break;
            case E_CORE_WARNING:
                $errseverity = 'CORE WARNING';
                break;
            case E_COMPILE_ERROR:
                $errseverity = 'COMPILE ERROR';
                break;
            case E_COMPILE_WARNING:
                $errseverity = 'COMPILE WARNING';
                break;
            case E_USER_ERROR:
                $errseverity = 'USER ERROR';
                break;
            case E_USER_WARNING:
                $errseverity = 'USER WARNING';
                break;
            case E_USER_NOTICE:
                $errseverity = 'USER NOTICE';
                break;
            case E_STRICT:
                $errseverity = 'STRICT STANDARDS';
                break;
            case E_RECOVERABLE_ERROR:
                $errseverity = 'RECOVERABLE ERROR';
                break;
            case E_DEPRECATED:
                $errseverity = 'DEPRECATED';
                break;
            case E_USER_DEPRECATED:
                $errseverity = 'USER DEPRECATED';
                break;
            default:
                $errseverity = 'ERROR';
                break;
        }

        $error_occurrence_time = date('d M Y H:i:s');
        error_log("[$error_occurrence_time]: $errseverity:\n$errstr\nin $errfile on line no. $errline \n\n", 3,
            ERROR_LOGGING_FILE);
    }

    public function shutdown()
    {
        $isError = false;
        if ($error = error_get_last()) {
            switch ($error['type']) {
                case E_ERROR:
                case E_CORE_ERROR:
                case E_COMPILE_ERROR:
                case E_USER_ERROR:
                case E_RECOVERABLE_ERROR:
                case E_CORE_WARNING:
                case E_COMPILE_WARNING:
                    $isError = true;
                    $this->scriptError($error['type'], $error['message'], $error['file'], $error['line']);
                    break;
            }
        }
        // Mail Alert the admin
        echo 'Something went terribly wrong. We have informed the team';
        $to = 'register@example.com';
        $subject = "Shutdown: Fatal error in {$error['file']} on line no. {$error['line']}";
        $message = var_export($error, true) . PHP_EOL;
        mail($to, $subject, $message);
    }
}