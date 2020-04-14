<?php

/**
 * File: BaseController.php.
 * Author: Self
 * Standard: PSR-2.
 * Do not change codes without permission.
 * Date: 2/22/2020
 */

namespace System\Core;

use \App\Auth;

abstract class BaseController
{
    protected $registry;

    public function __construct($registry)
    {
        $this->registry = $registry;
    }

    public function __get($key)
    {
        return $this->registry->get($key);
    }

    public function __set($key, $value)
    {
        $this->registry->set($key, $value);
    }

    public function __call($name, $args)
    {
        $method = $name . 'Action';

        if (method_exists($this, $method)) {
            if ($this->before() !== false) {
                call_user_func_array([$this, $method], $args);
                $this->after();
            }
        } else {
            echo 'Method $method not found in controller ' . get_class($this);
        }
    }

    protected function before()
    {
    }

    protected function after()
    {
    }

    public function redirect($url)
    {
        header('Location: http://' . $_SERVER['HTTP_HOST'] . $url, true, 303);
        exit;
    }

    public function requireLogin()
    {
        if (!Auth::getUser()) {
            Auth::rememberRequestedPage();
            $this->redirect('/home');
        }
    }
}
