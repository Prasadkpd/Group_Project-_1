<?php


namespace App\Controllers;
use Core\View;
use \App\Models\User;
use \App\Auth;
use \App\Models\CustomerModel;


class Login extends \Core\Controller
{
    public function indexAction()
    {
        View::renderTemplate('LoginSignup/loginView.html');
    }


    //Login for a user
    public function loginAction(){
    


        $user=User::authenticate($_POST['username'],$_POST['password']);
        // echo($user);
        if ($user) {

            Auth::login($user);

            //Redirects to home page atm (Change it to customised home page)

            if($user->type=='Admin'){
                $this->redirect('/login/adminlogin');
            }

            elseif($user->type=='Customer'){
                
                $this->redirect('/login/customerlogin');
                // $this->redirect(Auth::getReturnToPage());
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
        View::renderTemplate('Admin/adminFAQView.html');
    }

    public function customerloginAction()
    {
        $current_user= Auth::getUser();
        $id=$current_user->user_id;
        $bookings= CustomerModel::customerBookings($id);
        // var_dump($bookings);
        //direct to the customer page
        View::renderTemplate('Customer/customerDashboardView.html',['bookings'=>$bookings]);
    }
 
    public function bookinghandlingstaffloginAction()
    {
        //direct to the customer page
        View::renderTemplate('BookHandelStaff/aStaffManageBookingsView.html');
    }
    
    public function administrationstaffloginAction()
    {
        //direct to the customer page
        View::renderTemplate('AdministrationStaff/aStaffManageFacilityView.html');
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

        $this->redirect('/login');
    }
    }
