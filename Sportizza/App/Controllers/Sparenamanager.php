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

        //Rendering the manager home view(sports arena profile)
        View::renderTemplate('Manager/mStaffProfileView.html');
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
        $deleteFacilities = SpArenaManagerModel::managerDeleteFacility($id);
        //Assigning the sports arena's facilities to add (facility) view
        $updateFacilities = SpArenaManagerModel::managerUpdateFacility($id);

        //Rendering the manager's timeslot view
        View::renderTemplate('Manager/mStaffManageFacilityView.html', [
            'viewFacilities' => $viewFacilities,
            'deleteFacilities' => $deleteFacilities, 'updateFacilities' => $updateFacilities
        ]);
    }
    //End of Manage Facility of manager view

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
        //Rendering the manager's edit profile arena view
        View::renderTemplate('Manager/mStaffEditArenaProfile.html');
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
    //End of Add Timeslot of manager

    //Start of Add Facility of manager
    public function manageraddfacilityAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();
        $username = $current_user->username;
        $id = $current_user->user_id;

        // Authenticate the credentials
        $facility = LoginModel::authenticate(
            $username,
            $_POST['password']
        );

        // If authentication is valid
        if ($facility) {
            // Add the facility of the sports arena to the database
            SpArenaManagerModel::managerAddFacility($id, $_POST['facilityName']);
        }
        // Redirected to manage facility
        $this->redirect('/Sparenamanager/managefacility');
    }
    //End of Add Facility of manager




}
