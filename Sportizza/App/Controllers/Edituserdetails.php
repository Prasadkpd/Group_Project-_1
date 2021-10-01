<?php


namespace App\Controllers;

use Core\View;
use \App\Auth;
use App\Models\CustomerModel;
use App\Models\EditProfileModel;
use App\Flash;
use App\Models\SignupModel;

class Edituserdetails extends Authenticated
{

    // protected function before()
    // {
    //     if(Auth::getUser()->type=='Customer'){
            
    //         return true;
    //     }
    //     else{
    //         View::renderTemplate('500.html');
    //         return false;
    //     }
    // }
  
    

    // public function indexAction()
    // {
    //    $current_user= Auth::getUser();
    //     $id=$current_user->user_id;
    //     $bookings= CustomerModel::customerBookings($id);
    //     $favourie_list= CustomerModel::customerFavouriteList($id);
    //     $notifications= CustomerModel::customerNotification($id);
    //     // var_dump($bookings);
    //     //direct to the customer page
    //     View::renderTemplate('Customer/customerDashboardView.html',['bookings'=>$bookings,'list'=>$favourie_list,'notifications'=>$notifications]);
    // }




    public function EditProfileAction(){
        View::renderTemplate('editprofileView.html');
    }

    public function updatepasswordAction()
    {
        $Model = new EditProfileModel($_POST);      
        // $errors = $user->validate();
        $user=Auth::getUser();

        // $_SESSION['direct_url']=$_POST['direct_url'];
        // var_dump($_SESSION['direct_url']);

        if ($Model->saveNewPassword($user)){
            // otp::sendSMS("mobile_number");
            // $this->redirect('/Signup/success',['direct_url'=>$direct_url]);
            Flash::addMessage('successfully updated');
            $this->redirect('/Edituserdetails/EditProfile');
            exit;

        } 
        else {
            // $this->redirect('/Signup/failure');
            // exit;
            // View::renderTemplate('LoginSignup/customerSignupView.html', [
            //     'user' => $user, 'errors' => $errors]);
            Flash::addMessage('updated failed',Flash::WARNING);
            $this->redirect('/Edituserdetails/EditProfile');
        }
    }


    public function updatedetailsAction()
    {

        $user = new EditProfileModel($_POST);
        $oldUsername=Auth::getUser()->username;
        if ($user->updateNewUserDetails($oldUsername)){
            
            Flash::addMessage('successfully updated');
            $this->redirect('/Edituserdetails/EditProfile');
        }
        
        else{
            Flash::addMessage('updated failed',Flash::WARNING);
            $this->redirect('/Edituserdetails/EditProfile');
            
        }
        

    }



    public function updatemobileAction()
    {
        
        $_SESSION['direct_url']=('/Edituserdetails/directtoenternumber');
        otp::sendSMS("mobile_number");
        View::renderTemplate('otp.html');
    }

    public function directtoenternumberAction()
    {
        
        
        View::renderTemplate('EnterMobile.html');
    }
    public function redirectotppageAction(){
        $_SESSION['direct_url']=('/Edituserdetails/updateMobileNumber');
        $_SESSION['number']=$_POST['mobile_number'];
        // Flash::addMessage('updated failed');
        
        otp::sendSMS("mobile_number");
        View::renderTemplate('otp.html');
    }
    public function updateMobileNumber(){

        
        // $user = new EditProfileModel($_SESSION);
        $username=Auth::getUser()->username;
        if (EditProfileModel::updateNewMobileNumber($username,$_SESSION['number'])){
            
            Flash::addMessage('successfully updated');
            $this->redirect('/Edituserdetails/EditProfile');
        }
        
        else{
            Flash::addMessage('updated failed',Flash::WARNING);
            $this->redirect('/Edituserdetails/EditProfile');
            
        }
        
    }



   
}