<?php


namespace App\Controllers;
use Core\View;
use \App\Models\User;
use \App\Controllers\Otp;



class Signup extends \Core\Controller
{
    public function indexAction()
    {
        View::renderTemplate('LoginSignup/customerSignupView.html');
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

            otp::sendSMS($_POST["mobile_number"]);
            //Have redirect instead
            $this->redirect('/Signup/success');
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
