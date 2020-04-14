<?php

/**
 * File: Registry.php.
 * Author: Self
 * Standard: PSR-2.
 * Do not change codes without permission.
 * Date: 2/22/2020
 */

namespace System\Core;

final class Registry
{
    private $data = array();

    public function get($key)
    {
        return (isset($this->data[$key]) ? $this->data[$key] : null);
    }

    public function set($key, $value)
    {
        $this->data[$key] = $value;
    }

    public function has($key)
    {
        return isset($this->data[$key]);
    }
}