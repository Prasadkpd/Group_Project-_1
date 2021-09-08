<?php

namespace App\Controllers;

use \Core\View;
use \App\Models\User;

/**
 * Signup controller
 *
 * PHP version 7.4.12
 */
class Signup extends \Core\Controller
{
    /**
     * Show the Signup page
     *
     * @return void
     */
    public function newAction()
    {
        View::renderTemplate('Signup/spArenaApplication.html');
    }
    
    /**
     * Sign up a new sports arena
     *
     * @return void
     */
    public function createAction()
    {
        $user = new User($_POST);

        if ($user->spArenaReg()) {

            header('Location: http://' . $_SERVER['HTTP_HOST'] . '/Signup/success', true, 303);
            exit;

        } else {

            View::renderTemplate('Signup/spArenaApplication.html', [
                'user' => $user
            ]);

        }
    }
    /**
     * Show the signup success page
     *
     * @return void
     */
    public function successAction()
    {
        View::renderTemplate('Signup/success.html');
    }


}
