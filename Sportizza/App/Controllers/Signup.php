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
        $_SESSION['direct_url']=$_POST['direct_url'];
        var_dump($_SESSION['direct_url']);

        if ($user->save()){
            otp::sendSMS("mobile_number");
            // $this->redirect('/Signup/success',['direct_url'=>$direct_url]);
            $this->redirect('/Signup/success');
            exit;

        } 
        else {
            // $this->redirect('/Signup/failure');
            // exit;
            View::renderTemplate('LoginSignup/customerSignupView.html', [
                'user' => $user, 'errors' => $errors]);

        }
    }
    /**
     * Show the signup success page
     *
     * @return void
     */
    public function successAction()
    {
        // $id=$this->route_params['id'];
        // var_dump($id);
        var_dump($_SESSION['otp']);
        
        
        //direct to the message modal page
        View::renderTemplate('otp.html');
    }
    // public function failureAction()
    // {
    //     // var_dump($_SESSION['otp']);
    //     //direct to the message modal page
    //     // View::renderTemplate('LoginSignup/customerSignupView.html', [
    //     //     'user' => $user, 'errors' => $errors]);
    //     View::renderTemplate('LoginSignup/customerSignupView.html');
    // }

}
