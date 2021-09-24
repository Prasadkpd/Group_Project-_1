<?php


namespace App\Controllers;
use Core\View;
use Core\Image;

use \App\Controllers\Otp;

use \App\Models\SignupModel;


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
        $user = new SignupModel($_POST);
        $errors = $user->validate();

        //What should be placed here instead of string $file_name?
        $img_errors = Image::__construct("image_7");

        if ($user->save()) {

            // otp::sendSMS($_POST["mobile_number"]);
            //Have redirect instead
            $this->redirect('/Signup/success');
            exit;

        } else {

            View::renderTemplate('LoginSignup/customerSignupView.html', [
                'user' => $user, 'errors' => $errors, 'img_errors' => $img_errors]);

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
