<?php


namespace App\Controllers;
use Core\View;
use \App\Models\User;
use \App\Auth;
use \App\Models\LoginModel;
use \App\Models\AdminModel;
use \App\Flash;
use App\Models\EditProfileModel;

class Login extends \Core\Controller
{
    public function indexAction()
    {
        View::renderTemplate('LoginSignup/loginView.html');
    }


    //Login for a user
    public  function loginAction(){

        $user=LoginModel::authenticate($_POST['username'],$_POST['password']);
        if ($user) {

            // Auth::login($user);
            Auth::login($user);

            //Redirects to customised home page of each user)

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
            

            $message="Invalid username or password";
            $_SESSION['error']=$message;
            
            $this->redirect('/login');
            // View::renderTemplate('LoginSignup/loginView.html',['message'=>$message]);
            // var_dump($message);
            
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



    //forget password
    public  function forgotpasswordAction(){

        if(EditProfileModel::findByMobileNumber($_POST['mobile'])){
            $_SESSION['direct_url']=('/login/enternewpassword');
        $_SESSION['temp_mobile']=$_POST['mobile'];
        otp::sendSMS("mobile_number");
        View::renderTemplate('otp.html');
        }
        else{
            Flash::addMessage('not registered account for that number',Flash::WARNING);
            $this->redirect('/login');
        }
        

        
    }


    public  function enternewpasswordAction(){

        
        View::renderTemplate('passwordResetView.html');

        
    }

    public  function savenewpasswordAction(){

        
        $Model = new EditProfileModel($_POST);      
        // $errors = $user->validate();
        $mobile_number=$_SESSION['temp_mobile'];

        // $_SESSION['direct_url']=$_POST['direct_url'];
        // var_dump($_SESSION['direct_url']);

        if ($Model->saveForgotPassword($mobile_number)){
            // otp::sendSMS("mobile_number");
            // $this->redirect('/Signup/success',['direct_url'=>$direct_url]);
            Flash::addMessage('successfully updated Password');
            $this->redirect('/login');
            exit;

        } 
        else {
            // $this->redirect('/Signup/failure');
            // exit;
            // View::renderTemplate('LoginSignup/customerSignupView.html', [
            //     'user' => $user, 'errors' => $errors]);
            Flash::addMessage('update password is  failed try again',Flash::WARNING);
            $this->redirect('/login');
        }

        
    }


     //Logout for a user
    public function destroyAction()
    {
        Auth::logout();

        $this->redirect('/login/showLogoutMessage');
        
    }
    public function showLogoutMessageAction(){
        Flash::addMessage('logout successful');
        $this->redirect('/login');
    }


}
