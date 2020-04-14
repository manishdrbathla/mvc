<?php

namespace App\Controller;

use App\Authenticated;
use System\Core\View;
/**
 * ItemController controller (example)
 *
 * PHP version 7.0
 */

class ItemController extends Authenticated
{

    /**
     * Require the user to be authenticated before giving access to all methods in the controller
     *
     * @return void
     */

    protected function before()
    {
        $this->requireLogin();
    }


    /**
     * ItemController index
     *
     * @return void
     */
    public function indexAction()
    {
        echo 'hi';
    }
}
