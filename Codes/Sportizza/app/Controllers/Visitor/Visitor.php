<?php

namespace App\Controllers\Visitor;

use \Core\View;
/**
 * User visitor controller
 *
 * PHP version 7.4.12
 */
class Users extends \Core\Controller
{

    /**
     * Before filter
     *
     * @return void
     */
    protected function before()
    {
        // Make sure a customer user is logged in for example
        // return false;
    }

    /**
     * Show the index page
     *
     * @return void
     */
    public function indexAction()
    {
        echo 'User visitor index';
    }
}
