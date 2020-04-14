<?php

/**
 * File: HomeController.php.
 * Author: Self
 * Standard: PSR-12.
 * Do not change codes without permission.
 * Date: 3/22/2020
 */

namespace App\Controller;

use System\Core\BaseController;
use System\Core\View;

class HomeController extends BaseController
{
    public function indexAction()
    {
        View::renderTemplate('Home/index.twig');
    }
}