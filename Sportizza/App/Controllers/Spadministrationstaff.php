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

        //Rendering the administration staff home view(sports arena profile)
        View::renderTemplate('AdministrationStaff/aStaffProfileView.html');
    }
    //End of Landing page of administration staff

    //Start of Manage bookings of administration staff
    public function managebookingsAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();
        $id = $current_user->user_id;
        //Assigning the sports arena's bookings to view
        $bookings = SpAdministrationStaffModel::saAdminViewBookings($id);
        //Assigning the available timeslots of sports arena
        $add_bookings=SpAdministrationStaffModel::saAdminViewAvailableTimeSlots($id);
        //Assigning the sports arena's bookings to cancel view
        $cancelBookings = SpAdministrationStaffModel::saAdminCancelBookings($id);
        //Assigning the sports arena's bookings to get cash payment view
        $bookingPayments = SpAdministrationStaffModel::saAdminBookingPayment($id);

        //Rendering the administration staff's manage booking view
        View::renderTemplate('AdministrationStaff/aStaffManageBookingsView.html', [
            'bookings' => $bookings, 'timeslots'=>$add_bookings,
            'cancelBookings' => $cancelBookings, 'bookingPayments' => $bookingPayments
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
        $cancel_booking = SpAdministrationStaffModel::bookingCancellation($booking_id, $user_id,$_POST['Reason']);
       
        //If booking cancellation is successful
        if ($cancel_booking) {
            //Send booking cancellation successfull notification
            NotificationModel::customerEmergBookingCancelNotification($current_user,$booking_id);           
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
        $user = SpAdministrationStaffModel::saAdminAddTimeSlots(
            $id,
            $_POST['startTime'],
            $_POST['timeSlotDuration'],
            $_POST['slotPrice'],
            $_POST['facilityName']
        );

        //Redirected to manage timeslot
        $this->redirect('/Spadministrationstaff/managetimeslot');
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
        $timeslot_id= $this->route_params['id'];
        $current_user = Auth::getUser();
        $id = $current_user->user_id;

       SpAdministrationStaffModel::saAdminDeleteTimeSlots($id,$timeslot_id);
       $this->redirect('/spadministrationstaff/managetimeslot');

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
            SpAdministrationStaffModel::saAdminAddFacility($id, $_POST['fname']);

            $this->redirect('/Spadministrationstaff/managefacility');
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

    $combined = explode("__",$combined);
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
        $facility_id= $this->route_params['id'];
        $current_user = Auth::getUser();
        $id = $current_user->user_id;

       SpAdministrationStaffModel::saAdminDeleteFacility($id,$facility_id);
       $this->redirect('/spadministrationstaff/managefacility');
    }
    //End of Delete Facility of administration staff


    //Start of Update Facility of administration staff
    public function updatefacilityAction()
    {
        $facility_id= $this->route_params['id'];
        $current_user = Auth::getUser();
        $id = $current_user->user_id;

       SpAdministrationStaffModel::saAdminUpdateFacility($id,$facility_id, $_POST['Facility_name']);
       $this->redirect('/spadministrationstaff/managefacility');
    }
    //End of Delete Facility of administration staff

    //Start of Edit Arena profile of administration staff
    public function saAdmineditarenaprofileAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();
        //Rendering the administration staff's edit profile arena view
        View::renderTemplate('AdministrationStaff/aStaffEditArenaProfile.html');
    }

    //End of Edit Arena profile of administration staff

    public function cartAction()
    {
        View::renderTemplate('AdministrationStaff/aStaffCartNewView.html');
    }
    //End of Adding bookings from sports arena of administration staff
}
