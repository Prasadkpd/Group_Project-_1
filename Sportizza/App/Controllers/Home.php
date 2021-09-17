<?php


namespace App\Controllers;
use \Core\View;

class Home extends \Core\Controller
{
   
    public function indexAction()
    {
       View::renderTemplate('Home/visitor.html');
    }
    // public function loginAction()
    // {
    //     View::renderTemplate('LoginSignup/login.html');
    // }
    // public function customersignupAction()
    // {
    //     View::renderTemplate('LoginSignup/customersignup.html');
    // }
    // public function sparenasignupAction()
    // {
    //     View::renderTemplate('LoginSignup/spArenaSignup.html');
    // }

}