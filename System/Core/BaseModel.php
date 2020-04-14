<?php

/**
 * File: BaseModel.php.
 * Author: Self
 * Standard: PSR-2.
 * Do not change codes without permission.
 * Date: 2/22/2020
 */

namespace System\Core;

use System\Library\MyPDO;

abstract class BaseModel
{
    protected $db;
    protected $data;

    public function __construct()
    {
        $this->db = MyPDO::instance();
    }
}
