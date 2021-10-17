<?php

namespace App\Controllers;

use \App\Auth;

// Authenticated base controller
abstract class Authenticated extends \Core\Controller
{
    // Require the user to be authenticated before giving access to 
    // all methods with respected to that user in the controller
    protected function before()
    {
        $this->requireLogin();
    }
}
