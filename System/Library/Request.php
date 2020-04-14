<?php

/**
 * File: Session.php.
 * Author: Self
 * Standard: PSR-2. (Use codesniffer or download web code sniffer from www.webcodesniffer.net)
 * Do not change codes without permission.
 * Date: 1/3/2020
 */

namespace System\Library;

final class Request
{
    public $get = array();
    public $post = array();
    public $cookie = array();
    public $files = array();
    public $server = array();

    public function __construct()
    {
        $this->get = $this->clean($_GET);
        $this->post = $this->clean($_POST);
        $this->request = $this->clean($_REQUEST);
        $this->cookie = $this->clean($_COOKIE);
        $this->files = $this->clean($_FILES);
        $this->server = $this->clean($_SERVER);
    }

    public function clean($data)
    {
        if (is_array($data)) {
            foreach ($data as $key => $value) {
                unset($data[$key]);
                $data[$this->clean($key)] = $this->clean($value);
            }
        } else {
            $data = htmlspecialchars($data, ENT_COMPAT, 'UTF-8');
        }

        return $data;
    }
}