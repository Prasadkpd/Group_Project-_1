<?php

namespace App\Controllers;

use Core\View;
use Core\Image;
use \App\Controllers\Otp;
use \App\Models\SignupModel;


class Signup extends \Core\Controller
{
    //Rendering Customer's signup view
    public function indexAction()
    {
        View::renderTemplate('LoginSignup/customerSignupView.html');
    }

    // Start of Sign up a new user
    public function createAction()
    {
        //Assigning the data enetered by user in signup form to user variable
        $user = new SignupModel($_POST);
        //Assigning the errors (back end validation) to error variable
        $errors = $user->validate();
        //Save URL to be directed after OTP in the session with the hidden input
        $_SESSION['direct_url'] = $_POST['direct_url'];

        //If the user is succesfully saved
        if ($user->save()) {
            //Save user's username and mobile number in the session temporarily
            $_SESSION['temp_user'] = $_POST['username'];
            $_SESSION['mobile_number'] = $_POST['mobile_number'];

            //Send SMS to the User
            otp::sendSMS("mobile_number");
            //Redirect to Otp page
            $this->redirect('/Otp');
            exit;
        }

        //If the user is having errors in his inputs
        else {
            //Render Signup form with the errors
            View::renderTemplate('LoginSignup/customerSignupView.html', [
                'user' => $user, 'errors' => $errors
            ]);
        }
    }
    
    //Making the customer active after mobile verification and 
    // sending to login page(Called from view)
    public function activeuserAction()
    {
        //Obtaining the session's user and making him active
        SignupModel::activeuser($_SESSION['temp_user']);
        $this->redirect('/login');
    }
    // End of Sign up a new user
}
