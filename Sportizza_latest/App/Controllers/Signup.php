<?php


namespace App\Controllers;
use Core\View;
use \App\Models\User;


class Signup extends \Core\Controller
{
    public function indexAction()
    {
        View::renderTemplate('LoginSignup/customersignup.html');
    }
}

// namespace App\Controllers;

// use \Core\View;
// use \App\Models\User;

// /**
//  * Signup controller
//  *
//  * PHP version 7.0
//  */
// class Signup extends \Core\Controller
// {

//     /**
//      * Show the signup page
//      *
//      * @return void
//      */
//     public function indexAction()
//     {
//         View::renderTemplate('LoginSignup/signup.html');
//     }

//     /**
//      * Sign up a new user
//      *
//      * @return void
//      */
//     public function createAction()
//     {
//         $user = new User($_POST);

//         if ($user->save()) {

//             $this->redirect('/LoginSignup/success');

//         } else {

//             View::renderTemplate('LoginSignup/signup.html', [
//                 'user' => $user
//             ]);

//         }
//     }

//     /**
//      * Show the signup success page
//      *
//      * @return void
//      */
//     public function successAction()
//     {
//         View::renderTemplate('LoginSignup/success.html');
//     }
// }
