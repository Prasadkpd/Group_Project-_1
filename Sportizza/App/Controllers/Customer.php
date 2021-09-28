<?php


namespace App\Controllers;

use Core\View;
use \App\Auth;
use App\Models\CustomerModel;
use App\Models\EditProfileModel;
use App\Flash;
use App\Models\SignupModel;

class Customer extends Authenticated
{

    protected function before()
    {
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
        $arenaFacilites=CustomerModel::customerArenaFacilities($id);
        // var_dump($arenaDetails);
        View::renderTemplate('Customer/customerBookingView.html',
        ['timeSlots'=>$timeSlots,'arenaDetails'=>$arenaDetails,'arenaFacilites'=>$arenaFacilites]);
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
        // Calling the notification
        
        // Notification::sendNotification($subject,$description,$p_level,$user_id);
        // D\Redirecting
        $this->redirect('/Customer');
    }

    public function customerupdatepasswordAction()
    {
        

        $oldPassword=$_POST['oldPassword'];
        $newPassword=$_POST['newPassword'];
        $username=Auth::getUser();

        $result=EditProfileModel::PasswordValidate($username->username,  $oldPassword,$newPassword);

        if($result){
            Flash::addMessage('updated successful');
            $this->redirect('/Customer');
        }
        else{
            Flash::addMessage('updated failed',Flash::WARNING);
            $this->redirect('/Customer');

        }

        // var_dump($_GET);
        // $id=$this->route_params['id'];
        // var_dump($id);
        // $timeSlots=CustomerModel::customerViewTimeSlots($id);
        // $arenaDetails=CustomerModel::customerViewArenaDetails($id);
        // $arenaFacilites=CustomerModel::customerArenaFacilities($id);
        // var_dump($arenaDetails);
        // View::renderTemplate('Customer/customerBookingView.html',
        // ['timeSlots'=>$timeSlots,'arenaDetails'=>$arenaDetails,'arenaFacilites'=>$arenaFacilites]);
    }


    public function customerupdatedetailsAction()
    {
        $firstName=$_POST['firstName'];
        $lastName=$_POST['lastName'];
        $newUsername=$_POST['username'];

        $user=Auth::getUser();

        if($user->username==$newUsername){
            EditProfileModel::updateUserDetails($user->username,$firstName,$lastName);
            Flash::addMessage('successfully updated');
            $this->redirect('/Customer');
        }

        else{
            $result=EditProfileModel::UsernameValidate($user->username,$newUsername);
            if($result){
                EditProfileModel::updateUserDetails($newUsername,$firstName,$lastName);
                Flash::addMessage('successfully updated');

                $this->redirect('/Customer');
            }
            else{
                Flash::addMessage('updated failed',Flash::WARNING);
                $this->redirect('/Customer');
                
            }
        }
        

        

        // var_dump($_GET);
        // $id=$this->route_params['id'];
        // var_dump($id);
        // $timeSlots=CustomerModel::customerViewTimeSlots($id);
        // $arenaDetails=CustomerModel::customerViewArenaDetails($id);
        // $arenaFacilites=CustomerModel::customerArenaFacilities($id);
        // var_dump($arenaDetails);
        // View::renderTemplate('Customer/customerBookingView.html',
        // ['timeSlots'=>$timeSlots,'arenaDetails'=>$arenaDetails,'arenaFacilites'=>$arenaFacilites]);
    }

   
}