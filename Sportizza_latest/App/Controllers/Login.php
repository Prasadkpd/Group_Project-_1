<?php


namespace App\Controllers;
use Core\View;
use \App\Models\LoginModel;
use \App\Auth;


class Login extends \Core\Controller
{
    public function indexAction()
    {
        View::renderTemplate('LoginSignup/login.html');
    }


    //Login for a user
    public function loginAction(){
    


        $user=LoginModel::authenticate($_POST['username'],$_POST['password']);
        // echo($user);
        if ($user) {

            Auth::login($user);

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
        if(! Auth::isLoggedIn()){
            $this->redirect('/login');
        }
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
    public function destroyAction()
    {
        Auth::logout();

        $this->redirect('/');
    }
    }
