<?php


namespace App\Controllers;
use Core\View;
use \App\Models\User;


class Signup extends \Core\Controller
{
    public function indexAction()
    {
        View::renderTemplate('LoginSignup/customersignup.html');
    }

    /**
     * Sign up a new user
     *
     * @return void
     */
    public function createAction()
    {
        $user = new User($_POST);

        if ($user->save()) {

            header('Location: http://' . $_SERVER['HTTP_HOST'] . '/signup/success', true, 303);
            exit;

        } else {

            View::renderTemplate('LoginSignup/signup.html', [
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
        //direct to the message modal page
        View::renderTemplate('otp.html');
    }
}