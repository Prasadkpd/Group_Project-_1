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

            $this->redirect('signup/success');

        } else {
            

            // View::renderTemplate('LoginSignup/signup.html', [
            //     'user' => $user
            // ]);

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
        View::renderTemplate('Home/visitor.html');
    }
}
