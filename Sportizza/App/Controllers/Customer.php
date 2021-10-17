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
    //Start of blocking a user after login
    //Blocking unauthorised access after login as a user
    protected function before()
    {
        //Checking whether the user type is customer
        if (Auth::getUser()->type == 'Customer') {
            return true;
        }
        //Return to error page
        else {
            View::renderTemplate('500.html');
            return false;
        }
    }
    //End of blocking a user after login

    //Start of Landing page of customer
    public function indexAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();
        $id = $current_user->user_id;
        //Assigning the customer's bookings 
        $bookings = CustomerModel::customerBookings($id);
        //Assigning the customer's favourite list 
        $favourie_list = CustomerModel::customerFavouriteList($id);
        //Assigning the customer's notifications 
        $notifications = CustomerModel::customerNotification($id);

        //Rendering the customers home view
        View::renderTemplate(
            'Customer/customerDashboardView.html',
            [
                'bookings' => $bookings, 'list' => $favourie_list,
                'notifications' => $notifications
            ]
        );
    }
    //End of Landing page of customer

    //Start of Cart page of customer
    public  function cartAction()
    {
        //Rendering the customers cart view
        View::renderTemplate('Customer/customerCartNewView.html');
    }
    //End of Cart page of customer

    //Start of booking page of customer
    public function bookingAction()
    {
        //Assigning the sports arena's ID
        $id = $this->route_params['id'];

        //Assigning the sports arenas timeslots
        $timeSlots = CustomerModel::customerViewTimeSlots($id);
        //Assigning the sports arenas details
        $arenaDetails = CustomerModel::customerViewArenaDetails($id);

        //Rendering the customers booking view
        View::renderTemplate(
            'Customer/customerBookingView.html',
            ['timeSlots' => $timeSlots, 'arenaDetails' => $arenaDetails]
        );
    }
    //End of booking page of customer

    //Start of adding timeslots to customer by removing from the view
    public function hidebookingAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();
        $timeslot_id = $this->route_params['id'];
        //Adding timeslot to customer cart
        $addCart = CustomerModel::customerAddToCart($timeslot_id, $current_user);
    }
    //End of adding timeslots to customer by removing from the view

    //Start of cancel bookings from customer's my bookings view
    public function deletebookingAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();

        //Assigning bookings related to customer
        $booking_id = $this->route_params['id'];
        $booked_date = CustomerModel::customerSelectBookingDate($booking_id);
        $booked_time = strtotime($booked_date);

        //Obtaining the current time
        $current_time = time();
        //Checking if there's 3 days before the booking time
        if ($current_time - $booked_time <= 259200) {

            //Cancel the booking
            $deletebooking = CustomerModel::customerDeleteBooking($booking_id);
            //Sending booking cancellation notification
            $notify_check = NotificationModel::cancelNotificationBookingSuccess($current_user, $booking_id);

            //Redirect to customer's dashboard view if success
            if ($deletebooking && $notify_check) {
                $this->redirect('/Customer');
            }
        }
    }
    //End of cancel bookings from customer's my bookings view


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

    //Start of sending notification for all users after a card payment
    public function paymentsuccessAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();


        $invoice_id = 100000000;

        // Calling the notification
        $notify_check = NotificationModel::addNotificationBookingSuccess($current_user, $invoice_id);
        // D\Redirecting
        if ($notify_check) {
            $this->redirect('/Customer');
        } else {
            $this->redirect('/Customer/cart');
        }
    }

    //Start of adding sportsarena to Favourite list
    public function addtofavoritelistAction()
    {
        //Get the current user's details with session using Auth
        $customer_id = Auth::getUser()->user_id;
        //Adding sports arena to favorite list
        CustomerModel::customerAddFavoriteList($_POST['arena_id'], $customer_id);
        //redirect into customer boooking view
        $this->redirect('/Customer/booking/' . $_POST['arena_id']);
    }
    //End of adding sportsarena to Favourite list
}
