<?php

namespace App\Controllers;

use Core\View;
use App\Auth;
use App\Models\SpAdministrationStaffModel;
use App\Models\NotificationModel;
use App\Models\LoginModel;

class Spadministrationstaff extends Authenticated
{
    //Start of blocking a user after login
    //Blocking unauthorised access after login as a user
    protected function before()
    {
        //Checking whether the user type is administration staff
        if (Auth::getUser()->type == 'AdministrationStaff') {
            return true;
        } //Return to error page
        else {
            View::renderTemplate('500.html');
            return false;
        }
    }

    protected function after()
    {
    }
    //End of blocking a user after login

    //Start of Landing page of administration staff
    public function indexAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();
        $id = $current_user->user_id;

        $arena_details = SpAdministrationStaffModel::arenaProfileView($id);
        $arena_details['google_map_link'] = preg_replace('/\%\d\w/', ' , ', substr($arena_details['google_map_link'], 48));

        // var_dump($arena_details);
        //Rendering the manager home view(sports arena profile)
        View::renderTemplate('AdministrationStaff/aStaffProfileView.html', ['arena_details' => $arena_details]);
    }
    //End of Landing page of administration staff

    //Start of updating the arena profile by saAdmin
    public function editarenaprofileAction()
    {
        $arena_id = $this->route_params['id'];
        SpAdministrationStaffModel::editArenaProfile(
            $arena_id,
            $_POST['arena_name'],
            $_POST['location'],
            $_POST['contact'],
            $_POST['category'],
            $_POST['google_map_link'],
            $_POST['description'],
            $_POST['other_facilities'],
            $_POST['payment_method']
        );
        $this->redirect("/Spadministrationstaff");
    }

    //Start of Manage bookings of administration staff
    public function managebookingsAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();
        $id = $current_user->user_id;

        //Assigning the sports arena's bookings to view
        $bookings = SpAdministrationStaffModel::saAdminViewBookings($id);
        //Assigning the available timeslots of sports arena
        $add_bookings = SpAdministrationStaffModel::saAdminViewAvailableTimeSlots($id);
        //Assigning the sports arena's bookings to cancel view
        $cancelBookings = SpAdministrationStaffModel::saAdminCancelBookings($id);
        //Assigning the sports arena's bookings to get cash payment view
        $bookingPayments = SpAdministrationStaffModel::saAdminBookingPayment($id);

        // var_dump($add_bookings);
        //Rendering the administration staff's manage booking view
        View::renderTemplate('AdministrationStaff/aStaffManageBookingsView.html', [
            'bookings' => $bookings, 'timeSlots' => $add_bookings,
            'cancelBookings' => $cancelBookings, 'bookingPayments' => $bookingPayments
            // ,'arenaDetails' => $arenaDetails,
        ]);
    }
    //End of Manage bookings of administration staff

    //Start of getting cash payments from customers
    public function getpaymentAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();
        $booking_id = $this->route_params['id'];
        //Update the booking's payment status
        $cash_update = SpAdministrationStaffModel::updateBookingPayment($booking_id);

        //If the cash payment is successful
        if ($cash_update) {
            //Send payment successfull notification
            NotificationModel::managerNotificationBookingSuccess($current_user, $booking_id);
            $this->redirect('/Spadministrationstaff/saadminnotification');
        }
    }
    //End of getting cash payments from customers


    //Start of emergency booking cancellation from arena
    public function bookingcancellationAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();
        $user_id = $current_user->user_id;
        $booking_id = $this->route_params['id'];
        //Update the booking's payment status
        $cancel_booking = SpAdministrationStaffModel::bookingCancellation($booking_id, $user_id, $_POST['Reason']);

        //If booking cancellation is successful
        if ($cancel_booking) {
            //Send booking cancellation successfull notification
            NotificationModel::customerEmergBookingCancelNotification($current_user, $booking_id);
            $this->redirect('/Spadministrationstaff/saadminnotification');
        }
    }
    //End of getting cash payments from customers



    //Start of Notification of administration staff
    public function saadminnotificationAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();
        $id = $current_user->user_id;
        //Assigning the notifications related to user
        $notifications = SpAdministrationStaffModel::saAdminNotification($id);

        //Rendering the administration staff's notification view
        View::renderTemplate(
            'AdministrationStaff/aStaffNotificationView.html',
            ['notifications' => $notifications]
        );
    }
    //End of Notification of administration staff

    //Start of Manage Timeslot of administration staff view
    public function managetimeslotAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();
        $id = $current_user->user_id;
        //Assigning the sports arena's timeslots to view
        $viewTimeSlots = SpAdministrationStaffModel::saAdminViewTimeSlots($id);
        //Assigning the sports arena's timeslots to delete view
        // $deleteTimeSlots = SpAdministrationStaffModel::saAdminDeleteTimeSlots($id,$timeslot_id);
        // //Assigning the sports arena's timeslots to add (facility) view
        $selectFacility = SpAdministrationStaffModel::saAdminGetFacilityName($id);

        //Rendering the administration staff's timeslot view
        View::renderTemplate(
            'AdministrationStaff/aStaffManageTimeslotsView.html',
            [
                'timeSlots' => $viewTimeSlots, 'deleteTimeSlots' => $viewTimeSlots,
                'selectFacility' => $selectFacility
            ]
        );
    }
    //End of Manage Timeslot of administration staff

    //Start of Add Timeslot of administration staff
    public function AddTimeslotAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();
        $id = $current_user->user_id;

        //Adding timeslot to the sports arena 
        $time_slot_id = SpAdministrationStaffModel::saAdminAddTimeSlots(
            $id,
            $_POST['startTime'],
            $_POST['timeSlotDuration'],
            $_POST['slotPrice'],
            $_POST['facilityName']
        );
        if ($time_slot_id) {
            NotificationModel::saAdminAddtimeslotSuccessNotification($current_user, $time_slot_id);
            //Redirected to manage timeslot
            $this->redirect('/Spadministrationstaff/managetimeslot');
        }
    }

    //End of Add Timeslot of administration staff
    public function validatetimeslotsAction()
    {
        $current_user = Auth::getUser();
        $id = $current_user->user_id;

        $combined = $this->route_params['id'];

        $iTime = substr($combined, 0, 4);
        $sTime = substr_replace($iTime, ":", 2, 0);
        $duration = substr($combined, 4, 1);
        $fac = substr($combined, 5, 9);
        $price = substr($combined, 14);

        $timeslot_check = SpAdministrationStaffModel::CheckExistingTimeslots($id, $sTime, $duration, $price, $fac);

        if (!$timeslot_check) {
            echo true;
        }
    }
    //End of Add Timeslot of manager

    //Start of Delete Timeslot of administration staff
    public function deletetimeslotAction()
    {
        $timeslot_id = $this->route_params['id'];
        $current_user = Auth::getUser();

        $success = SpAdministrationStaffModel::saAdminDeleteTimeSlots($current_user, $timeslot_id);
        if ($success) {
            $this->redirect('/spadministrationstaff/managetimeslot');
        }
    }
    //End of Delete Timeslot of administration staff

    //Start of Manage Facility of administration staff view
    public function managefacilityAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();
        $id = $current_user->user_id;

        //Assigning the sports arena's facilities to view
        $viewFacilities = SpAdministrationStaffModel::saAdminViewFacility($id);

        //Rendering the administration staff's timeslot view
        View::renderTemplate(
            'AdministrationStaff/aStaffManageFacilityView.html',
            [
                'viewFacilities' => $viewFacilities
            ]
        );
    }
    //End of Manage Facility of administration staff view

    // //Start of Add Facility of administration staff
    public function createfacilityAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();
        $id = $current_user->user_id;
        $username = $current_user->username;

        $facility = LoginModel::authenticate(
            $username,
            $_POST['Userpassword']
        );

        if ($facility) {
            //Send the notification to the sports arena's staff
            $success=SpAdministrationStaffModel::saAdminAddFacility($id, $_POST['fname']);
if ($success) {
    NotificationModel::saAdminAddFacilitySuccessNotification($current_user, $_POST['fname']);
            $this->redirect('/Spadministrationstaff/managefacility');
}
        }
    }
    //End of Add Facility of administration staff

    //Start of validate Facility name of administration staff
    public function validatefacilitynameAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();
        $id = $current_user->user_id;
        $combined = $this->route_params['arg'];

        $facility_name = str_replace("_", " ", $combined);

        //Call the function in model and echo the resturn result
        $result = SpAdministrationStaffModel::findFacilityByName($id, $facility_name);

        if (!$result) {
            echo true;
        }
    }
    //End of validate Facility name of administration staff
    //Start of Update Facility name of administration staff
    public function validateAndUpdatefacilitynameAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();
        $id = $current_user->user_id;
        $combined = $this->route_params['arg'];

        $combined = explode("__", $combined);
        $facility_id = $combined[1];
        $facility_name = str_replace("_", " ", $combined[0]);

        // echo $facility_id . "_" . $facility_name;
        // return true;

        //Call the function in model and echo the resturn result
        $result = SpAdministrationStaffModel::findFacilityExcludeByName($id, $facility_id, $facility_name);

        echo $result;
    }
    //End of Update Facility name of administration staff

    //Start of Delete Facility of administration staff
    public function deletefacilityAction()
    {
        $facility_id = $this->route_params['id'];
        $current_user = Auth::getUser();
        $id = $current_user->user_id;

        $success=SpAdministrationStaffModel::saAdminDeleteFacility($current_user, $facility_id);
        if($success){
            $this->redirect('/spadministrationstaff/managefacility');
        }
        
    }
    //End of Delete Facility of administration staff


    //Start of Update Facility of administration staff
    public function updatefacilityAction()
    {
        $facility_id = $this->route_params['id'];
        $current_user = Auth::getUser();
       
        SpAdministrationStaffModel::saAdminUpdateFacility($current_user, $facility_id, $_POST['Facility_name']);
        $this->redirect('/spadministrationstaff/managefacility');
    }
    //End of Delete Facility of administration staff

    //Start of Edit Arena profile of administration staff
    //Start of Edit Arena profile of manager
    public  function saAdmineditarenaprofileAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();
        $id = $current_user->user_id;
        $arena_details = SpAdministrationStaffModel::arenaProfileView($id);

        //    var_dump($arena_details);
        //Rendering the manager's edit profile arena view
        View::renderTemplate('AdministrationStaff/aStaffEditArenaProfile.html', ['arena_details' => $arena_details]);
    }
    //End of Edit Arena profile of manager staff

    //End of Edit Arena profile of administration staff

    //Start of booking page of customer
    public function searchtimeslotdateAction()
    {
        $current_user = Auth::getUser();
        $saadmin_id = $current_user->user_id;
        //Assigning the relevant variables
        $combined = $this->route_params['arg'];

        $combined = explode("__", $combined);
        //   $arena_id = $combined[0];
        $date = str_replace("_", "-", $combined[1]);

        //Assigning the sports arenas timeslots
        $timeSlots = SpAdministrationStaffModel::saAdminSearchTimeSlotsDate($saadmin_id, $date);

        echo $timeSlots;
    }
    //End of booking page of customer


    //Start of adding timeslots to customer by removing from the view by SaAdmin
    public function hidebookingAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();
        $spadmin_id = $current_user->user_id;

        //Assigning the relevant variables
        $combined = $this->route_params['arg'];

        $combined = explode("__", $combined);
        $timeslot_id = $combined[0];
        $bookingDate = str_replace("_", "-", $combined[1]);
        $paymentMethod = 'cash';

        //Adding timeslot to customer cart
        $addCart = SpAdministrationStaffModel::saAdminAddToCart($spadmin_id, $timeslot_id, $bookingDate, $paymentMethod);

        if ($addCart) {
            echo true;
        }
    }
    //End of adding timeslots to customer by removing from the view  by SaAdmin

    //Start of Cart page of customer
    public  function cartAction()
    {
        $current_user = Auth::getUser();
        $user_id = $current_user->user_id;
        $cart = SpAdministrationStaffModel::saAdminCartView($user_id);

        $cashSum = 0;
        $cardSum = 0;
        $allSum = 0;
        $i = 0;
        for ($i; $i < count($cart); $i++) {

            if ($cart[$i]->payment_method == "cash") {
                $cashSum += $cart[$i]->price_per_booking;
            } else {
                $cardSum += $cart[$i]->price_per_booking;
            }
        }
        $allSum = $cashSum + $cardSum;



        //Rendering the customers cart view
        View::renderTemplate('AdministrationStaff/aStaffCartNewView.html', [
            'cart' => $cart,
            'allSum' => $allSum, 'cardSum' => $cardSum, 'cashSum' => $cashSum
        ]);
    }



    public function saAdminBookingsuccessnotificationAction()
    {

        $current_user = Auth::getUser();
        $saAdmin_id = $current_user->user_id;

        $first_name = $_POST['first_name'];
        // echo($first_name);
        $last_name = $_POST['last_name'];
        // echo($last_name);
        $primary_contact = $_POST['phone'];
        // echo($primary_contact);

        $payment_id = SpAdministrationStaffModel::saAdminAddbookingPaymentSuccess($saAdmin_id, $first_name, $last_name, $primary_contact);

        //Update the booking's payment status


        //If the cash payment is successful
        if ($payment_id) {
            //Send payment successfull notification
            NotificationModel::saAdminAddbookingPaymentSuccessNotification($current_user, $first_name, $last_name, $primary_contact, $payment_id);
            $this->redirect('/spadministrationstaff/managebookings');
        }
    }
}
  //End of Cart page of customer
