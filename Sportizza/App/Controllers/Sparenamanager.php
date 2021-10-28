<?php

namespace App\Controllers;

use Core\View;
use App\Auth;
use App\Models\SpArenaManagerModel;
use App\Models\NotificationModel;
use App\Models\LoginModel;

class Sparenamanager extends \Core\Controller
{
    //Start of blocking a user after login
    //Blocking unauthorised access after login as a user
    protected function before()
    {
        //Checking whether the user type is manager
        if (Auth::getUser()->type == 'Manager') {
            return true;
        }
        //Return to error page
        else {
            View::renderTemplate('500.html');
            return false;
        }
    }
    protected function after()
    {
    }
    //End of blocking a user after login

    //Start of Landing page of manager
    public function indexAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();
        $id = $current_user->user_id;

        $arena_details = SpArenaManagerModel::arenaProfileView($id);
//        var_dump($arena_details);
        //Rendering the manager home view(sports arena profile)
        View::renderTemplate('Manager/mStaffProfileView.html',['arena_details' => $arena_details]);
    }
    //End of Landing page of manager

    //Start of Manage bookings of manager
    public function managebookingsAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();
        $id = $current_user->user_id;
        //Assigning the sports arena's bookings to view
        $bookings = SpArenaManagerModel::managerViewBookings($id);
        //Assigning the sports arena's bookings to cancel view
        $cancelBookings = SpArenaManagerModel::managerCancelBookings($id);
        //Assigning the sports arena's bookings to get cash payment view
        $bookingPayments = SpArenaManagerModel::managerBookingPayment($id);

        //Rendering the manager's manage booking view
        View::renderTemplate('Manager/mStaffManageBookingsView.html', [
            'bookings' => $bookings,
            'cancelBookings' => $cancelBookings, 'bookingPayments' => $bookingPayments
        ]);
    }
    //End of Manage bookings of manager

    //Start of getting cash payments from customers
    public function getpaymentAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();
        $booking_id = $this->route_params['id'];
        //Update the booking's payment status
        $cash_update = SpArenaManagerModel::updateBookingPayment($booking_id);

        //If the cash payment is successful
        if ($cash_update) {
            //Send payment successfull notification
            NotificationModel::managerNotificationBookingSuccess($current_user, $booking_id);
            $this->redirect('/Sparenamanager/managernotification');
        }
    }

    //Start of Notification of manager
    public  function managernotificationAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();
        $id = $current_user->user_id;
        //Assigning the notifications related to user
        $notifications = SpArenaManagerModel::managerNotification($id);

        //Rendering the manager's notification view
        View::renderTemplate('Manager/mStaffNotificationView.html', ['notifications' => $notifications]);
    }
    //End of Notification of manager

    //Start of Manage Timeslot of manager view
    public  function managetimeslotAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();
        $id = $current_user->user_id;
        //Assigning the sports arena's timeslots to view
        $viewTimeSlots = SpArenaManagerModel::managerViewTimeSlots($id);
        //Assigning the sports arena's timeslots to delete view
        $deleteTimeSlots = SpArenaManagerModel::managerDeleteTimeSlots($id);
        //Assigning the sports arena's timeslots to add (facility) view
        $selectFacility = SpArenaManagerModel::managerGetFacilityName($id);

        //Rendering the manager's timeslot view
        View::renderTemplate('Manager/mStaffManageTimeslotsView.html', [
            'timeSlots' => $viewTimeSlots,
            'deleteTimeSlots' => $deleteTimeSlots, 'selectFacility' => $selectFacility
        ]);
    }
    //End of Manage Timeslot of manager view

    //Start of Manage Facility of manager view
    public function managefacilityAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();
        $id = $current_user->user_id;

        //Assigning the sports arena's facilities to view
        $viewFacilities = SpArenaManagerModel::managerViewFacility($id);
        //Assigning the sports arena's facilities to delete view
        $deleteFacilities = SpArenaManagerModel::managerViewDeleteFacility($id);
        //Assigning the sports arena's facilities to add (facility) view
        $updateFacilities = SpArenaManagerModel::managerViewUpdateFacility($id);

        //Rendering the manager's timeslot view
        View::renderTemplate('Manager/mStaffManageFacilityView.html', [
            'viewFacilities' => $viewFacilities,
            'deleteFacilities' => $deleteFacilities, 'updateFacilities' => $updateFacilities
        ]);
    }
    //End of Manage Facility of manager view
    // //Start of Add Facility of administration staff
    public function createfacilityAction()
    {
        //Get the current user's details with session using Auth
        $current_user= Auth::getUser();
        $id=$current_user->user_id;
        $username=$current_user->username;


        $facility=LoginModel::authenticate(
            $username,
            $_POST['Userpassword']
        );

        if($facility){
            //Send the notification to the sports arena's staff
            SpArenaManagerModel::managerAddFacility($id,$_POST['fname']);

            $this->redirect('/Sparenamanager/managefacility');
        }
    }
    //End of Add Facility of administration staff
    public function removeFacilityAction()
    {
        $facility_id = $this->route_params['id'];
        SpArenaManagerModel::removeFacility($facility_id);
        $this->redirect('/Sparenamanager/managefacility');
    }

    public function updatefacilityAction(){
        $facility_id = $this->route_params['id'];
        SpArenaManagerModel::updateFacility($facility_id,$_POST['New_Facility_name']);
        $this->redirect('/Sparenamanager/managefacility');
    }

    //Start of Manage Users of manager view
    public function manageusersAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();
        $id = $current_user->user_id;
        //Assigning the sports arena staff's details to view
        $viewStaff = SpArenaManagerModel::managerViewStaff($id);
        //Assigning the sports arena staff's details to delete view
        $removeStaff = SpArenaManagerModel::managerRemoveStaff($id);

        //Rendering the manager's users view
        View::renderTemplate('Manager/mStaffManageUsersView.html', [
            'viewStaff' => $viewStaff,
            'removeStaff' => $removeStaff
        ]);
    }
    //End of Manage Users of manager view

    //Start of validate Facility name of administration staff
    public function validatefacilitynameAction()
    {
        //Get the current user's details with session using Auth
        $current_user= Auth::getUser();
        $id=$current_user->user_id;

        $combined = $this->route_params['arg'];

        $facility_name = str_replace("_", " ", $combined);


        //Call the function in model and echo the resturn result
        $result= SpArenaManagerModel::findFacilityByName($id,$facility_name);

        if(!$result){
            echo true;
        }
    }
    //End of validate Facility name of administration staff

    //Start of Analytics of manager view
    public function manageanalyticsAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();
        $id = $current_user->user_id;

        // Generating chart 1
        $chart1 = SpArenaManagerModel::managerChart1($id);
        // Generating chart 2
        $chart2 = SpArenaManagerModel::managerChart2($id);
        // Generating chart 3
        $chart3 = SpArenaManagerModel::managerChart3($id);
        // Generating chart 4
        $chart4 = SpArenaManagerModel::managerChart4($id);

        //Rendering the manager's analytics view
        View::renderTemplate(
            'Manager/mStaffAnalyticsView.html',
            ['chart1' => $chart1, 'chart2' => $chart2, 'chart3' => $chart3, 'chart4' => $chart4]
        );
    }
    //End of Analytics of manager view

    //Start of Edit Arena profile of manager
    public  function managereditarenaprofileAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();
        $id = $current_user->user_id;
        $arena_details = SpArenaManagerModel::arenaProfileView($id);
//        var_dump($arena_details);
        //Rendering the manager's edit profile arena view
        View::renderTemplate('Manager/mStaffEditArenaProfile.html',['arena_details' => $arena_details]);
    }
    //End of Edit Arena profile of manager staff
    public  function cartAction()
    {
        View::renderTemplate('Manager/mStaffCartNewView.html');
    }

    //Start of Add Timeslot of manager
    public function manageraddtimeslotsAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();
        $id = $current_user->user_id;

        //Adding timeslot to the sports arena 
        SpArenaManagerModel::managerAddTimeSlots(
            $id,
            $_POST['startTime'],
            $_POST['timeSlotDuration'],
            $_POST['slotPrice'],
            $_POST['facilityName']
        );

        //Redirected to manage timeslot
        $this->redirect('/Sparenamanager/managetimeslot');
        
    }

    public function managervalidatetimeslotsAction(){
        $current_user= Auth::getUser();
        $id=$current_user->user_id;
        
        $combined = $this->route_params['id'];

        $iTime = substr($combined,0,4);
        $sTime = substr_replace($iTime, ":", 2, 0);
        $duration = substr($combined,4,1);
        $fac = substr($combined,5,9);
        $price = substr($combined,14);
        
        $timeslot_check = SpArenaManagerModel::managerCheckExistingTimeslots($id,$sTime,$duration,$price,$fac);

        if(!$timeslot_check){
           echo true;
        }

    }
    //End of Add Timeslot of manager

}
