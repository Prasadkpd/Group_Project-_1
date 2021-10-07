<?php


namespace App\Controllers;


use Core\View;
use App\Auth;
use App\Models\SpBookStaffModel;

class Spbookstaff extends \Core\Controller
{

    protected function before()
    {
        if(Auth::getUser()->type=='BookingHandlingStaff'){
            
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
        // $bookings= SpBookStaffModel::saBookViewBookings($id);
       
        // var_dump($bookings);
        //direct to the customer page
        View::renderTemplate('BookHandlingStaff/bStaffProfileView.html');


        
    }
    public function managebookingsAction()
    {
        $current_user= Auth::getUser();
        $id=$current_user->user_id;
        $bookings= SpBookStaffModel::saBookViewBookings($id);
        
        // var_dump($bookings);
        //direct to the customer page
        View::renderTemplate('BookHandlingStaff/bStaffViewBookingsView.html',['bookings'=>$bookings]);
       
    }

    public  function sabookstaffnotificationAction()
    {
        $current_user= Auth::getUser();
        $id=$current_user->user_id;
        $notifications= SpBookStaffModel::saBookNotification($id);
        View::renderTemplate('BookHandlingStaff/bStaffNotificationView.html',['notifications'=>$notifications]);
    }




    
}