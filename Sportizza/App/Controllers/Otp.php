<?php
namespace App\Controllers;

use App\Models\User;
use Core\View;

class Otp extends \Core\Controller
{
    protected function before()
    {

    }
    public function indexAction()
    {
        View::renderTemplate('otp.html');
    }
    public function after()
    {
    }

    //check this again
    public function mobileverificationAction()
    {
        $user = new User($_POST);

        if ($user->save()) {

            header('Location: http://' . $_SERVER['HTTP_HOST'] . '/otp/success', true, 303);
            exit;

        } else {

            View::renderTemplate('LoginSignup/signup.html', [
                'user' => $user
            ]);

        }
    }

    //Check this again
     public function gobackAction()
    {
        $user = new User($_POST);
        View::renderTemplate('LoginSignup/signup.html', [
        'user' => $user
    ]);
    }
    public function resendotpAction()
    {
        //Get the mobile phone from USer.php model and call the send otp function in user model
       
    }

}
