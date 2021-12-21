<?php

namespace App\Controllers;

use App\Models\User;
use Core\View;
use \App\Auth;
use \App\Models\LoginModel;
use \App\Models\AdminModel;
use \App\Models\SignupModel;
use \App\Flash;

class Otp extends \Core\Controller
{

    public $url = 0;
    protected function before()
    {
    }
    public function indexAction()
    {
        var_dump($_SESSION['otp']);        
        View::renderTemplate('otp.html');
    }
    public function after()
    {
    }


    //Start of resend OTP
    public function resendotpAction()
    {
        //Resend OTP to the mobile number 
        otp::sendSMS($_SESSION['mobile_number']);
        $this->redirect('/otp');
    }
    //End of resend OTP

    //Function to send an SMS
    public static function sendSMS($mobile_number)
    {
        //our mobile number
        $user = "94765282976";
        //our account password
        $password = 4772;
        //Random OTP code
        $otp = mt_rand(100000, 999999);

        // stores the otp code and mobile number into session
        $_SESSION['otp'] = $otp;
        $_SESSION['mobile_number'] = $mobile_number;

        //Message to be sent
        $text = urlencode("Enter the OTP code:" . $otp . "");
        // Replacing the initial 0 with 94
        $to = substr_replace($mobile_number, '94', 0, 0);
        //Base URL
        $baseurl = "http://www.textit.biz/sendmsg";
        // regex to create the url
        $url = "$baseurl/?id=$user&pw=$password&to=$to&text=$text";

        $ret = file($url);
        $res = explode(":", $ret[0]);
        // var_dump($otp);

        if (trim($res[0]) == "OK") {
            echo "Message Sent - ID : " . $res[1];
        } else {
            echo "Sent Failed - Error : " . $res[1];
        }
    }

    //Function to compare the sent OTP and the user entered input OTP
    public  function compareOTPAction()
    {
        //Obtaining the user entered OTP
        $temp = [
            $_POST['input1'], $_POST['input2'], $_POST['input3'],
            $_POST['input4'], $_POST['input5'], $_POST['input6']
        ];

        //Concatinating the integers user entered
        $otp = implode($temp);
        // var_dump($otp);

        //If 2 OTPs match
        if ($otp == $_SESSION['otp']) {
            //Redirected to the direct url saved in the session
            $this->redirect($_SESSION['direct_url']);
        } else {
            //Redirected to the OTP view with the error messages
            Flash::addMessage('OTP is wrong', Flash::WARNING);
            $this->redirect('/otp');
        }
    }
}
