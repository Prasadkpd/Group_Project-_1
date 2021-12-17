<?php

namespace App\Controllers;

use App\Models\SpArenaStaffAdd;
use Core\View;
use App\Auth;
use App\Models\SpArenaManagerModel;
use App\Models\NotificationModel;
use App\Models\LoginModel;

class Sparenamanager extends Authenticated
{
    //Start of blocking a user after login
    //Blocking unauthorised access after login as a user
    protected function before()
    {
        //Checking whether the user type is manager
        if (Auth::getUser()->type == 'Manager') {
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

    //Start of Landing page of manager
    public function indexAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();
        $id = $current_user->user_id;

        $arena_details = SpArenaManagerModel::arenaProfileView($id);
        $arena_details['google_map_link'] = preg_replace('/\%\d\w/', ' , ', substr($arena_details['google_map_link'], 48));

//        var_dump($arena_details);
        //Rendering the manager home view(sports arena profile)
        View::renderTemplate('Manager/mStaffProfileView.html', ['arena_details' => $arena_details]);
    }

    //End of Landing page of manager
    //Start of Edit Arena profile of manager
    public function managereditarenaprofileAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();
        $id = $current_user->user_id;
        $arena_details = SpArenaManagerModel::arenaProfileView($id);

        //    var_dump($arena_details);
        //Rendering the manager's edit profile arena view
        View::renderTemplate('Manager/mStaffEditArenaProfile.html', ['arena_details' => $arena_details]);
    }

    //End of Edit Arena profile of manager staff
    
    public function editarenaprofileAction()
    {
        $arena_id = $this->route_params['id'];
        SpArenaManagerModel::editArenaProfile($arena_id, $_POST['arena_name'], $_POST['location'], $_POST['contact'], $_POST['category'], $_POST['google_map_link'], $_POST['description'], $_POST['other_facilities'], $_POST['payment_method']);
        $this->redirect("/Sparenamanager");
    }

    //Start of Manage bookings of manager
    public function managebookingsAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();
        $id = $current_user->user_id;
        //Assigning the sports arena's bookings to view
        $bookings = SpArenaManagerModel::managerViewBookings($id);
        //Assigning the sports arena's bookings to cancel view
        $add_bookings = SpArenaManagerModel::managerViewAvailableTimeSlots($id);
        $cancelBookings = SpArenaManagerModel::managerCancelBookings($id);
        //Assigning the sports arena's bookings to get cash payment view
        $bookingPayments = SpArenaManagerModel::managerBookingPayment($id);

        //Rendering the manager's manage booking view
        View::renderTemplate('Manager/mStaffManageBookingsView.html', [
            'bookings' => $bookings,'timeSlots'=>$add_bookings,
            'cancelBookings' => $cancelBookings, 'bookingPayments' => $bookingPayments
        ]);
    }
    //End of Manage bookings of manager
    //Start of adding bookings by saAdmin's search action
    public function searchtimeslotdateAction()
    {
        //Get the current user's details with session using Auth'
        $current_user = Auth::getUser();
        $manager_id = $current_user->user_id;

        //Assigning the relevant variables
        $combined = $this->route_params['arg'];
        $date = str_replace("_", "-", $combined);

        //Assigning the sports arenas timeslots
        $timeSlots = SpArenaManagerModel::managerSearchTimeSlotsDate($manager_id, $date);
        echo $timeSlots;
    }
    //End of booking page of customer
    //Start of adding timeslots to visitor by removing from the add bookings view by Sparenamanager
    public function hidebookingAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();
        $manager_id = $current_user->user_id;

        //Assigning the relevant variables
        $combined = $this->route_params['arg'];
        $combined = explode("__", $combined);
        $timeslot_id = $combined[0];
        $bookingDate = str_replace("_", "-", $combined[1]);
        $paymentMethod = 'cash';

        //Adding timeslot to customer cart
        $addCart = SpArenaManagerModel::managerAddToCart($manager_id, $timeslot_id, $bookingDate, $paymentMethod);

        //If succesful, return true for ajax function
        if ($addCart) {
            echo true;
        }
    }
    //End of adding timeslots to customer by removing from the add bookings view  by Sparenamanager
    public function cartAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();
        $user_id = $current_user->user_id;

        //Passing cart items if items are added to the cart
        $cart = SpArenaManagerModel::managerCartView($user_id);
         
        //Calculating total card payment and total payment values for cart view
        $cashSum = 0;
        $cardSum = 0;
        $allSum = 0;
        $i = 0;

        for ($i; $i < count($cart); $i++) {

                //If booking's payment method is cash
            if ($cart[$i]->payment_method == "cash") {
                $cashSum += $cart[$i]->price_per_booking;

            //If booking's payment method is card
            } else {
                $cardSum += $cart[$i]->price_per_booking;
            }
        }

        //Total price of all the bookings
        $allSum = $cashSum + $cardSum;

        //Rendering the saAdmin's cart view
        View::renderTemplate('Manager/mStaffCartNewView.html', [
                'cart' => $cart,
                'allSum' => $allSum, 'cardSum' => $cardSum, 'cashSum' => $cashSum
            ]);
    }

    public function clearbookingAction()
    {
        //Assigning the relevant variables
        $booking_id = $this->route_params['id'];

        $clearedSlot = SpArenaManagerModel::clearBookingCart($booking_id);

        if ($clearedSlot) {
            echo true;
        }
    }

    public function managerBookingsuccessnotificationAction()
    {
        //Get the current user's details with session using Auth'
        $current_user = Auth::getUser();
        $manager_id = $current_user->user_id;

        //Assigning the variables
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $primary_contact = $_POST['phone'];

        //Get payment for that bookings
        $payment_id = SpArenaManagerModel::managerAddbookingPaymentSuccess($manager_id, $first_name, $last_name, $primary_contact);

        //If the cash payment is successful
        if ($payment_id) {

            //Send payment successfull notification
            $success = NotificationModel::saAdminAddbookingPaymentSuccessNotification($current_user, $first_name, $last_name, $payment_id);

            //If notification is successful
            if ($success) {
                $this->redirect('/Sparenamanager/managebookings');
            }
        }
    }
    //Start of emergency booking cancellation from arena
    public function bookingcancellationAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();
        $user_id = $current_user->user_id;
        $booking_id = $this->route_params['id'];
        //Update the booking's payment status
        $success = SpArenaManagerModel::bookingCancellation($booking_id, $user_id, $_POST['Reason']);
       
        //If booking cancellation is successful
        if ($success) {
            //Send booking cancellation successfull notification
            NotificationModel::customerEmergBookingCancelNotification($current_user, $booking_id);
            $this->redirect('/Sparenamanager/managernotification');
        }
    }
    //End of getting cash payments from customers

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
    public function managernotificationAction()
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
    public function managetimeslotAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();
        $id = $current_user->user_id;
        //Assigning the sports arena's timeslots to view
        $viewTimeSlots = SpArenaManagerModel::managerViewTimeSlots($id);
        //Assigning the sports arena's timeslots to delete view
        $deleteTimeSlots = SpArenaManagerModel::managerViewDeleteTimeSlots($id);
        //Assigning the sports arena's timeslots to add (facility) view
        $selectFacility = SpArenaManagerModel::managerGetFacilityName($id);

        //Rendering the manager's timeslot view
        View::renderTemplate('Manager/mStaffManageTimeslotsView.html', [
            'timeSlots' => $viewTimeSlots,
            'deleteTimeSlots' => $deleteTimeSlots, 'selectFacility' => $selectFacility
        ]);
    }

    //End of Manage Timeslot of manager view
    public function managervalidatetimeslotsAction()
    {
        $current_user = Auth::getUser();
        $id = $current_user->user_id;

        $combined = $this->route_params['id'];

        $iTime = substr($combined, 0, 4);
        $sTime = substr_replace($iTime, ":", 2, 0);
        $duration = substr($combined, 4, 1);
        $fac = substr($combined, 5, 9);
        $price = substr($combined, 14);

        $timeslot_check = SpArenaManagerModel::managerCheckExistingTimeslots($id, $sTime, $duration, $fac);

        if (!$timeslot_check) {
            echo true;
        }
    }
    //End of Add Timeslot of manager

    //Start of Add Timeslot of manager
    public function manageraddtimeslotsAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();
        $id = $current_user->user_id;

        //Adding timeslot to the sports arena
        $timeslot_id = SpArenaManagerModel::managerAddTimeSlots(
            $id,
            $_POST['startTime'],
            $_POST['timeSlotDuration'],
            $_POST['slotPrice'],
            $_POST['facilityName']
        );
        echo $timeslot_id;
        //If time slot is successfully added
        if ($timeslot_id) {
            $success = NotificationModel::saAdminAddtimeslotSuccessNotification($current_user, $timeslot_id);
            // If notification is successful
            if ($success) {
                $this->redirect('/Sparenamanager/managetimeslot');
            }
        }

        //Redirected to manage timeslot
    }
    public function removetimeslotAction()
    {
         $current_user = Auth::getUser();
        $timeSlot_Id = $this->route_params['id'];
        $success = SpArenaManagerModel::removeTimeSlot($current_user,$timeSlot_Id);
        if($success){
            $this->redirect('/Sparenamanager/managetimeslot');
        }
    }

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
        $current_user = Auth::getUser();
        $id = $current_user->user_id;
        $username = $current_user->username;


        $user = LoginModel::authenticate(
            $username,
            $_POST['Userpassword']
        );

        if ($user) {
            //Send the notification to the sports arena's staff
            $facility_added_id = SpArenaManagerModel::managerAddFacility($id, $_POST['fname']);

            if ($facility_added_id) {
                $executed = NotificationModel::saAdminAddFacilitySuccessNotification($current_user, $_POST['fname'], $facility_added_id);

                if ($executed) {
                    $this->redirect('/Sparenamanager/managefacility');
                }
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
        $result = SpArenaManagerModel::findFacilityByName($id, $facility_name);

        if (!$result) {
            echo true;
        }
    }
    //End of validate Facility name of administration staff

    public function removeFacilityAction()
    {
        $current_user = Auth::getUser();
        $facility_id = $this->route_params['id'];
        $success = SpArenaManagerModel::removeFacility($facility_id);
        if ($success) {
            //Sending deletion notification to the sports arena's staff
            $executed = NotificationModel::arenaDeleteFacilityNotification($current_user, $facility_id);
            
            //If notification is successful
            if ($executed) {
                $this->redirect('/Sparenamanager/managefacility');
            }
        }
    }

    public function updatefacilityAction()
    {
        $current_user = Auth::getUser();
        $facility_id = $this->route_params['id'];
        $executed = SpArenaManagerModel::updateFacility($current_user,$facility_id, $_POST['New_Facility_name']);
        if($executed){
            $this->redirect('/Sparenamanager/managefacility');
        }
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
    public function createstaffAction()
    {
        $current_user = Auth::getUser();
        $id = $current_user->user_id;
        //Assigning the data enetered by user in signup form to user variable
        $is_added = SpArenaManagerModel::addStaff(
            $id,
            $_POST['first_name'],
            $_POST['last_name'],
            $_POST['mobile_number'],
            $_POST['username'],
            $_POST['password'],
            $_POST['staff_type'],
            $_FILES['image']
        );
        if ($is_added) {
            $this->redirect('/Sparenamanager/manageusers');
        }
    }
    //Start of Remove Users
    public function removestaff()
    {
        $current_user = Auth::getUser();
        $current_user_id = $current_user->user_id;
        $removed_user_id = $this->route_params['id'];
        $is_user_removed = SpArenaManagerModel::removestaff($current_user_id, $removed_user_id);
        if ($is_user_removed) {
            $this->redirect("/Sparenamanager/manageusers");
        }
    }
    //End of Remove Users
    //Start of validate Facility name of administration staff
    public function validateusernameAction()
    {
        $userName = $this->route_params['arg'];

        //Call the function in model and echo the return result
        $result = SpArenaManagerModel::findUserName($userName);

        if (!$result) {
            echo true;
        }
    }
    //End of validate Facility name of administration staff

    //Start of validate Facility name of administration staff
    public function validatemobilenumberAction()
    {
        $mobileNo = $this->route_params['id'];

        //Call the function in model and echo the return result
        $result = SpArenaManagerModel::findMobileNo($mobileNo);

        if (!$result) {
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
        $arenaName = SpArenaManagerModel::arenaOfManager($id);
        
        //Rendering the manager's analytics view
        View::renderTemplate(
            'Manager/mStaffAnalyticsView.html',
            ['chart1' => $chart1, 'chart2' => $chart2, 'chart3' => $chart3,
             'chart4' => $chart4,'arenaName'=>$arenaName]
        );
    }
    //End of Analytics of manager view

    // Start of reshaping charts
    public function reshapechartAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();
        $id = $current_user->user_id;

        $dateValue = $this->route_params['id'];

        $chart2Payment = [];
        $chart2Count = [];

        $chart3Slot = [];
        $chart3Count = [];

        $chart4Facility = [];
        $chart4Count = [];

        $chart2 = SpArenaManagerModel::managerReshapeChart2($dateValue, $id);
        $chart3 = SpArenaManagerModel::managerReshapeChart3($dateValue, $id);
        $chart4 = SpArenaManagerModel::managerReshapeChart4($dateValue, $id);

        $i=0;
        $j=0;
        $k=0;

        for ($i; $i< count($chart2); $i++) {
            $chart2Payment[$i] = $chart2[$i]->payment_method;
            $chart2Count[$i] = $chart2[$i]->No_Of_Bookings;
        }
        for ($j; $j< count($chart3); $j++) {
            $chart3Slot[$j] = $chart3[$j]->start_time;
            $chart3Count[$j] = $chart3[$j]->No_Of_Bookings;
        }
        for ($k; $k< count($chart4); $k++) {
            $chart4Facility[$k] = $chart4[$k]->facility_name;
            $chart4Count[$k] = $chart4[$k]->No_Of_Bookings;
        }

        $payment_method = implode(",", $chart2Payment);
        $count2 = implode(",", $chart2Count);

        $start_time = implode(",", $chart3Slot);
        $count3 = implode(",", $chart3Count);
        
        $facility_name = implode(",", $chart4Facility);
        $count4 = implode(",", $chart4Count);

        echo $payment_method."_".$count2."$".$start_time."_".$count3."$".$facility_name."_".$count4;
    }
    // End of reshaping charts

    // Start of Update Image1 in Edit arena Profile
    public function editimageoneAction()
    {
        $current_user = Auth::getUser();
        $id = $current_user->user_id;
        SpArenaManagerModel::changeImageone($id, $_FILES['image_1']);
        $this->redirect('/Sparenamanager/managereditarenaprofile/#image_uploader');
    }

    // Start of Update Image1 in Edit arena Profile
    public function editimagetwoAction()
    {
        $current_user = Auth::getUser();
        $id = $current_user->user_id;
        SpArenaManagerModel::changeImage2($id, $_FILES['image_2']);
        $this->redirect('/Sparenamanager/managereditarenaprofile/#image_uploader');
    }

    // Start of Update Image1 in Edit arena Profile
    public function editimagethreeAction()
    {
        $current_user = Auth::getUser();
        $id = $current_user->user_id;
        SpArenaManagerModel::changeImage3($id, $_FILES['image_3']);
        $this->redirect('/Sparenamanager/managereditarenaprofile/#image_uploader');
    }

    // Start of Update Image1 in Edit arena Profile
    public function editimagefourAction()
    {
        $current_user = Auth::getUser();
        $id = $current_user->user_id;
        SpArenaManagerModel::changeImage4($id, $_FILES['image_4']);
        $this->redirect('/Sparenamanager/managereditarenaprofile/#image_uploader');
    }

    // Start of Update Image1 in Edit arena Profile
    public function editimagefiveAction()
    {
        $current_user = Auth::getUser();
        $id = $current_user->user_id;
        SpArenaManagerModel::changeImage5($id, $_FILES['image_5']);
        $this->redirect('/Sparenamanager/managereditarenaprofile/#image_uploader');
    }
}
