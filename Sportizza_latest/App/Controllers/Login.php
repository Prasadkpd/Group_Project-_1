<?php


namespace App\Controllers;
use Core\View;
use \App\Models\LoginModel;


class Login extends \Core\Controller
{
    public function indexAction()
    {
        View::renderTemplate('LoginSignup/login.html');
    }


    //Login for a user
    public function createAction(){
    
        // $user = LoginModel::findByUsername($_POST['username']);

        // var_dump($user);


        $user=LoginModel::authenticate($_POST['username'],$_POST['password']);
        // echo($user);
        if ($user) {

            // session_regenerate_id(true);
            // $_SESSION['user_id'] = $user->id;
            //Redirects to home page atm (Change it to customised home page)

            if($user->type=='Admin'){
                $this->redirect('/login/adminlogin');
            }

            elseif($user->type=='Customer'){
                $this->redirect('/login/customerlogin');
            }
            elseif($user->type=='Manager'){
                $this->redirect('/login/managerlogin');
            }
            elseif($user->type=='AdministrationStaff'){
                $this->redirect('/login/administrationstafflogin');
            }

            elseif($user->type=='BookingHandlingStaff'){
                $this->redirect('/login/bookinghandlingstafflogin');
            }
           
        } else {

            View::renderTemplate('LoginSignup/login.html', [
                'username' => $_POST['username'],
            ]);

            
        }

        
    }


    public function adminloginAction()
    {
        //direct to the admin page
        View::renderTemplate('Admin/admin-FAQ.html');
    }

    public function customerloginAction()
    {
        //direct to the customer page
        View::renderTemplate('Customer/new_customer_dashboard.html');
    }
 
    public function bookinghandlingstaffloginAction()
    {
        //direct to the customer page
        View::renderTemplate('BookHandelStaff/manage-bookings.html');
    }
    public function administrationstaffloginAction()
    {
        //direct to the customer page
        View::renderTemplate('AdministrationStaff/manage-facility.html');
    }

    public function managerloginAction()
    {
        //direct to the customer page
        View::renderTemplate('Customer/new_customer_dashboard.html');
    }

     //Logout for a user
    // public function destroyAction()
    // {
    //     // Unset all of the session variables
    //     $_SESSION = [];

    //     // Delete the session cookie
    //     if (ini_get('session.use_cookies')) {
    //         $params = session_get_cookie_params();

    //         setcookie(
    //             session_name(),
    //             '',
    //             time() - 42000,
    //             $params['path'],
    //             $params['domain'],
    //             $params['secure'],
    //             $params['httponly']
    //         );
    //     }

    //     // Finally destroy the session
    //     session_destroy();

    //     $this->redirect('/');
    // }
    }
