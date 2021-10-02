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
    public function resendotpAction()
    {
        otp::sendSMS($_SESSION['mobile_number']);
        $this->redirect('/Signup/success');
        
    }
    public function gobackAction(){
        $user = new SignupModel($_POST);
        // $this->redirect('/Otp/otpsuccess');
        $this->redirect('/Signup/failure',['user'=>$user]);
        
    }

    public static function sendSMS($mobile_number)
    {
        // $url = $direct_url;
        //our mobile number
        $user = "94765282976";
        //our account password
        $password = 4772;
        //Random OTP code
        $otp = mt_rand(100000, 999999);
        
        // stores the otp into a session variable
        $_SESSION['otp'] = $otp;
        $_SESSION['mobile_number'] = $mobile_number;
        
        //SMS Sent
        $text = urlencode("Enter the OTP code:" . $otp . "");
        // Replacing the initial 0 with 94
        $to = substr_replace($mobile_number, '94', 0, 0);
        //Base URL
        $baseurl = "http://www.textit.biz/sendmsg";
        // regex to create the url
        $url = "$baseurl/?id=$user&pw=$password&to=$to&text=$text";

        $ret = file($url);
        $res = explode(":", $ret[0]);
        var_dump($otp);

        if (trim($res[0]) == "OK") {
            echo "Message Sent - ID : " . $res[1];
        } else {
            echo "Sent Failed - Error : " . $res[1];
        }
    }
    public  function compareOTPAction()
    {
        $temp=[$_POST['input1'],$_POST['input2'],$_POST['input3'],
        $_POST['input4'],$_POST['input5'],$_POST['input6']];
        $otp=implode($temp);
        var_dump($otp);
        
        if($otp==$_SESSION['otp']){
            Flash::addMessage('updated successfully');
            $this->redirect($_SESSION['direct_url']);
        }
        else{
            Flash::addMessage('OTP is wrong',Flash::WARNING);
            $this->redirect('/Signup/success');
        }

    }
}
