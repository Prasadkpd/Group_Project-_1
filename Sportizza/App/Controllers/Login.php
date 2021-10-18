<?php

namespace App\Controllers;

use Core\View;
use \App\Models\User;
use \App\Auth;
use \App\Models\LoginModel;
use \App\Models\AdminModel;
use \App\Flash;
use App\Models\EditProfileModel;

class Login extends \Core\Controller
{
    //Before action to return true
    protected function before()
    {
    }
    //After action to return true
    protected function after()
    {
    }
    //Rendering the login view
    public function indexAction()
    {
        View::renderTemplate('LoginSignup/loginView.html');
    }

    //Start of Login of a user
    public function loginAction()
    {
        //Assigning the username and password entered in login form
        $user = LoginModel::authenticate($_POST['username'], $_POST['password']);

        //If a user is available with a username and a password
        if ($user) {

            // Adding user details to the session
            Auth::login($user);

            //Redirects to customised home page of each user
            if ($user->type == 'Admin') {
                $this->redirect('/Admin');
            } elseif ($user->type == 'Customer') {
                $this->redirect('/Customer');
            } elseif ($user->type == 'Manager') {
                $this->redirect('/Sparenamanager');
            } elseif ($user->type == 'AdministrationStaff') {
                $this->redirect('/Spadministrationstaff');
            } elseif ($user->type == 'BookingHandlingStaff') {
                $this->redirect('/Spbookstaff');
            }
        }
        //If the entered details are not valid, redirect to login with errors
        else {
            Flash::addMessage('Invalid username or password', Flash::WARNING);
            $this->redirect('/login');
        }
    }
    //End of Login of a user

    //Start of Forget password of a user
    public function forgotpasswordAction()
    {
        //Checking whether a user exists with this mobile number
        if (EditProfileModel::findByMobileNumber($_POST['mobile'])) {
            //Saving the url to be directed and the mobile number in the session
            $_SESSION['direct_url'] = ('/login/enternewpassword');
            $_SESSION['temp_mobile'] = $_POST['mobile'];

            //Sending the OTP to the mobile number
            otp::sendSMS($_POST['mobile']);
            $this->redirect('/otp');
        }
        //If there's no user with that mobile number, redirect to login with errors
        else {
            Flash::addMessage('There is no registered account for that number!', Flash::WARNING);
            $this->redirect('/login');
        }
    }

    //Rendering the new password form
    public  function enternewpasswordAction()
    {
        View::renderTemplate('passwordResetView.html');
    }

    //Start of forget password action
    public function savenewpasswordAction()
    {
        //Assigning the old password and new password entered from the form
        $Model = new EditProfileModel($_POST);
        //Accessing the user entered mobile number
        $mobile_number = $_SESSION['temp_mobile'];

        //Saving the new password after validation from model
        if ($Model->saveForgotPassword($mobile_number)) {
            //Direct to login page with the success message
            Flash::addMessage('Your password has been successfully updated.');
            $this->redirect('/login');
            exit;
        }
        //Failure of Saving the new password after validation from model
        else {
            Flash::addMessage('Password reset is failed, please try again', Flash::WARNING);
            $this->redirect('/login/enternewpassword');
        }
    }
    //End of forget password action

    //Start of Logout for a user
    public function destroyAction()
    {
        Auth::logout();
        //Redirect to home page after logout
        $this->redirect('/login');
    }
    //End of Logout for a user
}
