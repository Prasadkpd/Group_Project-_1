<?php


namespace App\Controllers;

use Core\View;
use \App\Auth;
use App\Models\CustomerModel;
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
        View::renderTemplate('Customer/customerBookingView.html');
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

   
}