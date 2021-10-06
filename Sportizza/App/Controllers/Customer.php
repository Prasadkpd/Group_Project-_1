<?php


namespace App\Controllers;

use Core\View;
use \App\Auth;
use App\Models\CustomerModel;
use App\Models\EditProfileModel;
use App\Flash;
use App\Models\SignupModel;

use App\Models\NotificationModel;
class Customer extends Authenticated
{

    protected function before()
    {
        if(Auth::getUser()==null){
            $this->redirect('/login');
        }
        if(Auth::getUser()->type=='Customer'){
            
            return true;
        }
        else{
            View::renderTemplate('500.html');
            return false;
        }
    }
  
    

    public function indexAction()
    {
       $current_user= Auth::getUser();
        $id=$current_user->user_id;
        $bookings= CustomerModel::customerBookings($id);
        $favourie_list= CustomerModel::customerFavouriteList($id);
        $notifications= CustomerModel::customerNotification($id);
        // var_dump($bookings);
        //direct to the customer page
        View::renderTemplate('Customer/customerDashboardView.html',['bookings'=>$bookings,'list'=>$favourie_list,'notifications'=>$notifications]);
    }

    public  function cartAction()
    {
        View::renderTemplate('Customer/customerCartNewView.html');
    }

    public function bookingAction()
    {
        // var_dump($_GET);
        $id=$this->route_params['id'];
        // var_dump($id);
        $timeSlots=CustomerModel::customerViewTimeSlots($id);
        $arenaDetails=CustomerModel::customerViewArenaDetails($id);
        // $arenaFacilites=CustomerModel::customerArenaFacilities($id);
        // var_dump($arenaDetails);
        View::renderTemplate('Customer/customerBookingView.html',
        ['timeSlots'=>$timeSlots,'arenaDetails'=>$arenaDetails]);
        // View::renderTemplate('Customer/customerBookingView.html',
        // ['timeSlots'=>$timeSlots,'arenaDetails'=>$arenaDetails,'arenaFacilites'=>$arenaFacilites]);
    }

    public function hidebookingAction()
    {
        $current_user= Auth::getUser();
        $timeslot_id=$this->route_params['id'];
        $addCart = CustomerModel::customerAddToCart($timeslot_id,$current_user);
        
    }

    public function deletebookingAction()
    {
        $current_user= Auth::getUser();
        $booking_id=$this->route_params['id'];
        $booked_date = CustomerModel::customerSelectBookingDate($booking_id);
        $booked_time = strtotime($booked_date);
        $current_time=time();
        if($current_time - $booked_time <= 259200) {
            $deletebooking = CustomerModel::customerDeleteBooking($booking_id);
            $notify_check=NotificationModel::cancelNotificationBookingSuccess($current_user,$booking_id);
            if($deletebooking) {
                if($notify_check) {
                    $this->redirect('/Customer');
                }
            }
        }
    }

    // public function paymentAction()
    // {
        
    //     $merchant_id         = $_POST['merchant_id'];
    //     $order_id             = $_POST['order_id'];
    //     $payhere_amount     = $_POST['amount'];
    //     $payhere_currency    = $_POST['currency'];
    //     $status_code         = $_POST['status_code'];
    //     $md5sig                = $_POST['md5sig'];
    //     $method             =$_POST['method'];
        
    //     if ($method=='VISA'||$method=='MASTER'){
    //         $card_holder_name = $_POST['card_holder_name'];
    //         $card_no = $_POST['card_no'];
    //         $card_expiry = $_POST['card_expiry'];
    //     }
        
    //     // $md5sig = strtoupper (md5 ( $merchant_id . $order_id . $payhere_amount . $payhere_currency . $status_code + strtoupper(md5($payhere_secret)) ) );
        
    //     $merchant_secret = '4fTw237GjlF4Ob8177KghV8LWMTRKbBKu4ErpPXVXnC1'; // Replace with your Merchant Secret (Can be found on your PayHere account's Settings page)

    //     $local_md5sig = strtoupper (md5 ( $merchant_id . $order_id . $payhere_amount . $payhere_currency . $status_code . strtoupper(md5($merchant_secret)) ) );

    //     if (($local_md5sig === $md5sig) AND ($status_code == 2) ){
    //             //TODO: Update your database as payment success
    //     }

    // }



    public function paymentsuccessAction()
    {
        $current_user= Auth::getUser();
        // $cid=$current_user->user_id;
        $invoice_id=100000000;

        // Calling the notification
        $notify_check=NotificationModel::addNotificationBookingSuccess($current_user,$invoice_id);
        // D\Redirecting
        if($notify_check) {
            $this->redirect('/Customer');
        } else {
            $this->redirect('/Customer/cart');
        }
    }

    public function addtofavoritelistAction()
    {
        $customer_id=Auth::getUser()->user_id;
        CustomerModel::customerAddFavoriteList($_POST['arena_id'],$customer_id);
        $this->redirect('/Customer/booking/'.$_POST['arena_id']);
    }


    // public function customerEditProfileAction(){
    //     View::renderTemplate('Customer\editprofileView.html');
    // }

    // public function customerupdatepasswordAction()
    // {
    //     $Model = new EditProfileModel($_POST);      
    //     // $errors = $user->validate();
    //     $user=Auth::getUser();

    //     // $_SESSION['direct_url']=$_POST['direct_url'];
    //     // var_dump($_SESSION['direct_url']);

    //     if ($Model->saveNewPassword($user)){
    //         // otp::sendSMS("mobile_number");
    //         // $this->redirect('/Signup/success',['direct_url'=>$direct_url]);
    //         Flash::addMessage('successfully updated');
    //         $this->redirect('/customer/customerEditProfile');
    //         exit;

    //     } 
    //     else {
    //         // $this->redirect('/Signup/failure');
    //         // exit;
    //         // View::renderTemplate('LoginSignup/customerSignupView.html', [
    //         //     'user' => $user, 'errors' => $errors]);
    //         Flash::addMessage('updated failed',Flash::WARNING);
    //         $this->redirect('/customer/customerEditProfile');
    //     }









    //     // $oldPassword=$_POST['oldPassword'];
    //     // $newPassword=$_POST['newPassword'];
    //     // $username=Auth::getUser();

    //     // $result=EditProfileModel::PasswordValidate($username->username,  $oldPassword,$newPassword);

    //     // if($result){
    //     //     Flash::addMessage('updated successful');
    //     //     $this->redirect('/Customer');
    //     // }
    //     // else{
    //     //     Flash::addMessage('updated failed',Flash::WARNING);
    //     //     $this->redirect('/Customer');

    //     // }

    //     // var_dump($_GET);
    //     // $id=$this->route_params['id'];
    //     // var_dump($id);
    //     // $timeSlots=CustomerModel::customerViewTimeSlots($id);
    //     // $arenaDetails=CustomerModel::customerViewArenaDetails($id);
    //     // $arenaFacilites=CustomerModel::customerArenaFacilities($id);
    //     // var_dump($arenaDetails);
    //     // View::renderTemplate('Customer/customerBookingView.html',
    //     // ['timeSlots'=>$timeSlots,'arenaDetails'=>$arenaDetails,'arenaFacilites'=>$arenaFacilites]);
    // }


    // public function customerupdatedetailsAction()
    // {
    //     // $firstName=$_POST['firstName'];
    //     // $lastName=$_POST['lastName'];
    //     // $newUsername=$_POST['username'];

    //     // $user=Auth::getUser();

    //     // if($user->username==$newUsername){
    //     //     EditProfileModel::updateUserDetails($user->username,$firstName,$lastName);
    //     //     Flash::addMessage('successfully updated');
    //     //     $this->redirect('/Customer');
    //     // }

    //     // else{
    //     //     $result=EditProfileModel::UsernameValidate($user->username,$newUsername);
    //     //     if($result){
    //     //         EditProfileModel::updateUserDetails($newUsername,$firstName,$lastName);
    //     //         Flash::addMessage('successfully updated');

    //     //         $this->redirect('/Customer');
    //     //     }
    //     //     else{
    //     //         Flash::addMessage('updated failed',Flash::WARNING);
    //     //         $this->redirect('/Customer');
                
    //     //     }
    //     // }





    //     // $firstName=$_POST['firstName'];
    //     // $lastName=$_POST['lastName'];
    //     // $newUsername=$_POST['username'];
    //     $user = new EditProfileModel($_POST);
    //     $oldUsername=Auth::getUser()->username;
    //     if ($user->updateNewUserDetails($oldUsername)){
            
    //         Flash::addMessage('successfully updated');
    //         $this->redirect('/customer/customerEditProfile');
    //     }
        
    //     else{
    //         Flash::addMessage('updated failed',Flash::WARNING);
    //         $this->redirect('/customer/customerEditProfile');
            
    //     }
        

        

    //     // var_dump($_GET);
    //     // $id=$this->route_params['id'];
    //     // var_dump($id);
    //     // $timeSlots=CustomerModel::customerViewTimeSlots($id);
    //     // $arenaDetails=CustomerModel::customerViewArenaDetails($id);
    //     // $arenaFacilites=CustomerModel::customerArenaFacilities($id);
    //     // var_dump($arenaDetails);
    //     // View::renderTemplate('Customer/customerBookingView.html',
    //     // ['timeSlots'=>$timeSlots,'arenaDetails'=>$arenaDetails,'arenaFacilites'=>$arenaFacilites]);
    // }



    // public function customerupdatemobileAction()
    // {
        
    //     $_SESSION['direct_url']=('/Customer/customerdirecttoenternumber');
    //     otp::sendSMS("mobile_number");
    //     View::renderTemplate('otp.html');
    // }

    // public function customerdirecttoenternumberAction()
    // {
        
        
    //     View::renderTemplate('Customer/customerEnterMobile.html');
    // }
    // public function redirectotppageAction(){
    //     $_SESSION['direct_url']=('/Customer/customerEditProfile');
    //     // Flash::addMessage('updated failed');
        
    //     otp::sendSMS("mobile_number");
    //     View::renderTemplate('otp.html');
    // }



   
}