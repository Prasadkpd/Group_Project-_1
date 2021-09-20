<?php


namespace App\Controllers;
use Core\View;
use \App\Models\User;
use \App\Auth;
use \App\Models\CustomerModel;
use \App\Models\AdminModel;


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

            // Auth::login($user);
            Auth::login($user);

            //Redirects to home page atm (Change it to customised home page)

            if($user->type=='Admin'){
                $this->redirect('/Admin');
            }

            elseif($user->type=='Customer'){
                
                // $this->redirect('/login/customerlogin');
                $this->redirect('/Customer');
                // $this->redirect(Auth::getReturnToPage());
            }
            elseif($user->type=='Manager'){
                $this->redirect('/Sparenamanager');
            }
            elseif($user->type=='AdministrationStaff'){
                $this->redirect('/Spadministrationstaff');
            }

            elseif($user->type=='BookingHandlingStaff'){
                $this->redirect('/Spbookstaff');
            }
           
        } else {
            // $message="invalid username or password";
            // View::renderTemplate('LoginSignup/loginView.html', [
            //     'username' => $_POST['username'],'message'=>$message
            // ]);

            $message="invalid username or password";
            $this->redirect('/login', [
                'username' => $_POST['username'],'message'=>$message
            ]);
            
        }

        
    }


    // public function adminloginAction()
    // {

        
    //     // $customers= AdminModel::adminRemoveCustomers();
    //     // $inactiveSportsArenas= AdminModel::adminAddSportsArenas();
    //     // $activeSportsArenas= AdminModel::adminRemoveSportsArenas();
    //     // //direct to the admin page
    //     // View::renderTemplate('Admin/adminManageUsersView.html',
    //     // ['customers'=>$customers,'inactiveArenas'=>$inactiveSportsArenas,'activeArenas'=>$activeSportsArenas]);
    // }

    // public function customerloginAction()
    // {
        
        
        
    // }
 
    // public function bookinghandlingstaffloginAction()
    // {
    //     //direct to the customer page
    //     View::renderTemplate('BookHandelStaff/aStaffManageBookingsView.html');
    // }
    
    // public function administrationstaffloginAction()
    // {
    //     //direct to the customer page
    //     View::renderTemplate('AdministrationStaff/aStaffManageFacilityView.html');
    // }

    // public function managerloginAction()
    // {
    //     //direct to the customer page
    //     View::renderTemplate('Manager/mStaffManageBookingsView.html');
    // }

     //Logout for a user
    public function destroyAction()
    {
        Auth::logout();

        $this->redirect('/login');
    }
    }
