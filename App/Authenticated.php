<?php

/**
 * File: Authenticated.php.
 * Author: Self
 * Standard: PSR-2.
 * Do not change codes without permission.
 * Date: 2/22/2020
 */

namespace App;

use System\Core\BaseController;

abstract class Authenticated extends BaseController
{
    protected function before()
    {
        $this->requireLogin();
    }
}
