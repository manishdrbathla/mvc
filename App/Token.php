<?php

/**
 * File: Token.php.
 * Author: Self
 * Standard: PSR-2.
 * Do not change codes without permission.
 * Date: 2/22/2020
 */

namespace App;

class Token
{

    protected $token;

    public function __construct($token_value = null)
    {
        if ($token_value) {
            $this->token = $token_value;
        } else {
            $this->token = bin2hex(
                random_bytes(16)
            );  // 16 bytes = 128 bits = 32 hex characters
        }
    }

    public function getValue()
    {
        return $this->token;
    }

    public function getHash()
    {
        return hash_hmac('sha256', $this->token, SECRET_KEY);  // sha256 = 64 chars
    }
}
