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
    //  public function gobackAction()
    // {
    //     $user = new User($_POST);
    //     View::renderTemplate('LoginSignup/signup.html', [
    //     'user' => $user
    // ]);
    // }
    public function resendotpAction($mobile_number)
    {
        
       otp::sendSMS($mobile_number);
    }

    public static function sendSMS($mobile_number)
    {
        //our mobile number
        $user = "94765282976";
        //our account password
        $password = 4772;
        //Random OTP code
        $otp= mt_rand(100000,999999);
        //SMS Sent
        $text = urlencode("Enter the OTP code:". $otp ."");
        // Replacing the initial 0 with 94
        $to = substr_replace($mobile_number, '94', 0, 0);
        //Base URL
        $baseurl = "http://www.textit.biz/sendmsg";
        // regex to create the url
        $url = "$baseurl/?id=$user&pw=$password&to=$to&text=$text";

        $ret = file($url);
        $res = explode(":", $ret[0]);

        if (trim($res[0]) == "OK") {
            echo "Message Sent - ID : " . $res[1];
        } else {
            echo "Sent Failed - Error : " . $res[1];
        }

    }

}
