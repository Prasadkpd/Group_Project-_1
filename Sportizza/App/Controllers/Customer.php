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
        

        View::renderTemplate('Customer/customerCartView.html');
    }

    public function bookingAction()
    {
        // var_dump($id);
        $id=100000004;
        $timeSlots=CustomerModel::customerViewTimeSlots($id);
        $arenaDetails=CustomerModel::customerViewArenaDetails($id);
        $arenaFacilites=CustomerModel::customerArenaFacilities($id);
        // var_dump($arenaDetails);
        View::renderTemplate('Customer/customerBookingView.html',
        ['timeSlots'=>$timeSlots,'arenaDetails'=>$arenaDetails,'arenaFacilites'=>$arenaFacilites]);
    }

   
}