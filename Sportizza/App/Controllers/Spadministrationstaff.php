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
        } 
        //Return to error page
        else {
            View::renderTemplate('500.html');
            return false;
        }
    }
    //End of blocking a user after login

    /*--------------------------------------------------------------------------------------------------*/

    //Start of Administration Staff's arena profile view
    public function indexAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();
        $id = $current_user->user_id;

        //Assigning the variables to the view
        $arena_details = SpAdministrationStaffModel::arenaProfileView($id);
        $arena_details['google_map_link'] = preg_replace('/\%\d\w/', ' , ', substr($arena_details['google_map_link'], 48));

        //Rendering the manager home view(sports arena profile)
        View::renderTemplate('AdministrationStaff/aStaffProfileView.html', ['arena_details' => $arena_details]);
    }
    //End of Landing page of administration staff

    //Start of Initially loading Edit Arena profile of saAdmin
    public function saAdmineditarenaprofileAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();
        $id = $current_user->user_id;

        //Obtaining the arena details to display the current values
        $arena_details = SpAdministrationStaffModel::arenaProfileView($id);

        //Rendering the manager's edit profile arena view
        View::renderTemplate('AdministrationStaff/aStaffEditArenaProfile.html', ['arena_details' => $arena_details]);
    }
    //End of Initially loading Edit Arena profile of saAdmin

    //Start of updating the arena profile by saAdmin
    public function editarenaprofileAction()
    {
        //Assigning the variables 
        $arena_id = $this->route_params['id'];
        //Passing the updated arena details to the model
        $success = SpAdministrationStaffModel::editArenaProfile(
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
        //If the update is successful, redirect to the arena profile view
        if ($success) {
            $this->redirect("/Spadministrationstaff");
        }
    }
    //End of updating the arena profile by saAdmin

    // Start of Update Image1 in Edit arena Profile
    public function editimageoneAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();
        $id = $current_user->user_id;

        //Passing the uploaded image to the model
        $success = SpAdministrationStaffModel::changeImageone($id, $_FILES['image_1']);

        //If the update is successful, redirect to the arena profile view
        if ($success) {
            $this->redirect('/Spadministrationstaff/saAdmineditarenaprofile/#image_uploader');
        }
    }
    // End of Update Image1 in Edit arena Profile

    // Start of Update Image2 in Edit arena Profile
    public function editimagetwoAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();
        $id = $current_user->user_id;

        //Passing the uploaded image to the model
        $success = SpAdministrationStaffModel::changeImage2($id, $_FILES['image_2']);

        //If the update is successful, redirect to the arena profile view
        if ($success) {
            $this->redirect('/Spadministrationstaff/saAdmineditarenaprofile/#image_uploader');
        }
    }
    // End of Update Image2 in Edit arena Profile

    // Start of Update Image3 in Edit arena Profile
    public function editimagethreeAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();
        $id = $current_user->user_id;

        //Passing the uploaded image to the model
        $success = SpAdministrationStaffModel::changeImage3($id, $_FILES['image_3']);

        //If the update is successful, redirect to the arena profile view
        if ($success) {
            $this->redirect('/Spadministrationstaff/saAdmineditarenaprofile/#image_uploader');
        }
    }
    // End of Update Image3 in Edit arena Profile

    // Start of Update Image1 in Edit arena Profile
    public function editimagefourAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();
        $id = $current_user->user_id;

        //Passing the uploaded image to the model
        $success = SpAdministrationStaffModel::changeImage4($id, $_FILES['image_4']);

        //If the update is successful, redirect to the arena profile view
        if ($success) {
            $this->redirect('/Spadministrationstaff/saAdmineditarenaprofile/#image_uploader');
        }
    }
    // End of Update Image4 in Edit arena Profile

    // Start of Update Image5 in Edit arena Profile
    public function editimagefiveAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();
        $id = $current_user->user_id;

        //Passing the uploaded image to the model
        $success = SpAdministrationStaffModel::changeImage5($id, $_FILES['image_5']);

        //If the update is successful, redirect to the arena profile view
        if ($success) {
            $this->redirect('/Spadministrationstaffr/saAdmineditarenaprofile/#image_uploader');
        }
    }
    // End of Update Image5 in Edit arena Profile

    //End of Administration Staff's arena profile view

    /*--------------------------------------------------------------------------------------------------*/

    //Start of Administration Staff's manage bookings view
    //Start of Initial Manage bookings view of administration staff
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

        //Rendering the administration staff's manage booking view
        View::renderTemplate('AdministrationStaff/aStaffManageBookingsView.html', [
            'bookings' => $bookings, 'timeSlots' => $add_bookings,
            'cancelBookings' => $cancelBookings, 'bookingPayments' => $bookingPayments
        ]);
    }
    //End of Initial Manage bookings of administration staff


    //Start of adding bookings by saAdmin's search action
    public function searchtimeslotdateAction()
    {
        //Get the current user's details with session using Auth'
        $current_user = Auth::getUser();
        $saadmin_id = $current_user->user_id;

        //Assigning the relevant variables
        $combined = $this->route_params['arg'];
        $date = str_replace("_", "-", $combined);

        //Assigning the sports arenas timeslots
        $timeSlots = SpAdministrationStaffModel::saAdminSearchTimeSlotsDate($saadmin_id, $date);
        echo $timeSlots;
    }
    //End of booking page of customer


    //Start of adding timeslots to visitor by removing from the add bookings view by SaAdmin
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

        //If succesful, return true for ajax function
        if ($addCart) {
            echo true;
        }
    }
    //End of adding timeslots to customer by removing from the add bookings view  by SaAdmin

    //Start of emergency booking cancellation from arena(cancel bookings view) by saAdmin
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
            $success = NotificationModel::customerEmergBookingCancelNotification($current_user, $booking_id);

            //If notification is successful
            if ($success) {
                $this->redirect('/Spadministrationstaff/saadminnotification');
            }
        }
    }
    //End of emergency booking cancellation from arena(cancel bookings view) by saAdmin

    //Start of getting cash payments from customers(Booking payment view) by saAdmin
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
            $success = NotificationModel::managerNotificationBookingSuccess($current_user, $booking_id);

            //If notification is successful
            if ($success) {
                $this->redirect('/Spadministrationstaff/saadminnotification');
            }
        }
    }
    //End of getting cash payments from customers(Booking payment view) by saAdmin
    //End of Administration Staff's manage bookings view

    /*--------------------------------------------------------------------------------------------------*/

    //Start of Administration Staff's notification view
    public function saadminnotificationAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();
        $id = $current_user->user_id;

        //Assigning the notifications related to user
        $notifications = SpAdministrationStaffModel::saAdminNotification($id);

        //If notifications are found
        if ($notifications) {

            //Rendering the administration staff's notification view
            View::renderTemplate(
                'AdministrationStaff/aStaffNotificationView.html',
                ['notifications' => $notifications]
            );
        }
    }
    //End of Notification of administration staff

    /*--------------------------------------------------------------------------------------------------*/

    //Start of Administration Staff's manage timeslot view
    //Start of Manage Timeslot initial loading of administration staff view
    public function managetimeslotAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();
        $id = $current_user->user_id;

        //Assigning the sports arena's timeslots to view
        $viewTimeSlots = SpAdministrationStaffModel::saAdminViewTimeSlots($id);

        //Assigning the sports arena's timeslots to add (facility) view
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
    //End of Manage Timeslot initial loading of administration staff view

    //Start of Adding Timeslot by administration staff
    //Start of Validating the timeslot to be added before adding using ajax
    public function validatetimeslotsAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();
        $id = $current_user->user_id;

        //Assigning the variables
        $combined = $this->route_params['id'];
        $iTime = substr($combined, 0, 4);
        $sTime = substr_replace($iTime, ":", 2, 0);
        $duration = substr($combined, 4, 1);
        $fac = substr($combined, 5, 9);
        $price = substr($combined, 14);

        //Assigning the variables to the model
        $timeslot_check = SpAdministrationStaffModel::CheckExistingTimeslots($id, $sTime, $duration, $price, $fac);

        //If timeslot is found
        if (!$timeslot_check) {
            echo true;
        }
    }
    //End of Validating the timeslot to be added before adding using ajax

    //Start of Add Timeslot form of administration staff
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

        //If time slot is successfully added
        if ($time_slot_id) {
            $success = NotificationModel::saAdminAddtimeslotSuccessNotification($current_user, $time_slot_id);

            //If notification is successful
            if ($success) {
                $this->redirect('/Spadministrationstaff/saadminnotification');
            }
        }
    }
    //End of Add Timeslot form of administration staff
    //End of Adding Timeslot by administration staff

    //Start of Delete Timeslot of administration staff
    public function deletetimeslotAction()
    {
        //Get the current user's details with session using Auth'
        $current_user = Auth::getUser();

        //Assigning the variables
        $timeslot_id = $this->route_params['id'];

        //Delete the timeslot from the sports arena(include the notification as well)
        $success = SpAdministrationStaffModel::saAdminDeleteTimeSlots($current_user, $timeslot_id);

        //If time slot is successfully deleted
        if ($success) {
            $this->redirect('/spadministrationstaff/saadminnotification');
        }
    }
    //End of Delete Timeslot of administration staff
    //End of Administration Staff's manage timeslot view

    /*--------------------------------------------------------------------------------------------------*/

    //Start of Administration Staff's manage facility view

    //Start of Manage Facility initial view of administration staff view
    public function managefacilityAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();
        $id = $current_user->user_id;

        //Assigning the sports arena's facilities to view
        $viewFacilities = SpAdministrationStaffModel::saAdminViewFacility($id);

        //If facilities are found
        if ($viewFacilities) {

            //Rendering the administration staff's timeslot view
            View::renderTemplate(
                'AdministrationStaff/aStaffManageFacilityView.html',
                [
                    'viewFacilities' => $viewFacilities
                ]
            );
        }
    }
    //End of Manage Facility initial view of administration staff view

    //Start of validate Facility name of administration staff using ajax
    public function validatefacilitynameAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();
        $id = $current_user->user_id;

        //Assigning the variables
        $combined = $this->route_params['arg'];
        $facility_name = str_replace("_", " ", $combined);

        //Call the function in model and echo the resturn result
        $result = SpAdministrationStaffModel::findFacilityByName($id, $facility_name);

        //If results are not found
        if (!$result) {
            echo true;
        }
    }
    //End of validate Facility name of administration staff using ajax

    //Start of Adding Facility of administration staff
    public function createfacilityAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();
        $id = $current_user->user_id;
        $username = $current_user->username;
        $success = LoginModel::authenticate(
            $username,
            $_POST['Userpassword']
        );

        //If user is logged in and its password matches
        if ($success) {

            //Assigning the facility ID after adding a facility
            $facility_id = SpAdministrationStaffModel::saAdminAddFacility($id, $_POST['fname']);

            //If facility is successfully added
            if ($facility_id) {

                //Send the notification to the sports arena's staff
                $executed = NotificationModel::saAdminAddFacilitySuccessNotification($current_user, $_POST['fname'], $facility_id);
                
                //If notification is successful
                if ($executed) {
                    $this->redirect('/Spadministrationstaff/managefacility');
                }
            }
        }
    }
    //End of Adding Facility of administration staff

    //Start of Delete Facility of administration staff
    public function deletefacilityAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();

        //Assigning the variables
        $facility_id = $this->route_params['id'];

        //Delete the facility from the sports arena
        $success = SpAdministrationStaffModel::saAdminDeleteFacility($current_user, $facility_id);

        //If deletion is successful
        if ($success) {

            //Sending deletion notification to the sports arena's staff
            $executed = NotificationModel::arenaDeleteFacilityNotification($current_user, $facility_id);

            //If notification is successful
            if ($executed) {
                $this->redirect('/spadministrationstaff/saadminnotification');
            }
        }
    }
    //End of Delete Facility of administration staff

    //Start of validating facility name for Update Facility name of administration staff
    public function validateAndUpdatefacilitynameAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();
        $id = $current_user->user_id;

        //Assigning the variables
        $combined = $this->route_params['arg'];
        $combined = explode("__", $combined);
        $facility_id = $combined[1];
        $facility_name = str_replace("_", " ", $combined[0]);

        //Call the function in model and echo the resturn result
        $result = SpAdministrationStaffModel::findFacilityExcludeByName($id, $facility_id, $facility_name);
        echo $result;
    }
    //End of validating facility name for Update Facility name of administration staff

    //Start of Update Facility of administration staff
    public function updatefacilityAction()
    {
        //Get the current user's details with session using Auth'
        $current_user = Auth::getUser();

        //Assigning the variables
        $facility_id = $this->route_params['id'];

        //Update the facility name of the sports arena(include the notification as well)
        $success = SpAdministrationStaffModel::saAdminUpdateFacility($current_user, $facility_id, $_POST['Facility_name']);

        //If the update is successful
        if ($success) {
            $this->redirect('/spadministrationstaff/managefacility');
        }
    }
    //End of Update Facility of administration staff

    //End of Administration Staff's manage facility view

    /*--------------------------------------------------------------------------------------------------*/

    //Start of Administration Staff's cart view
    //Start of Cart page's initial view of saAdmin 
    public  function cartAction()
    {
        //Get the current user's details with session using Auth
        $current_user = Auth::getUser();
        $user_id = $current_user->user_id;

        //Passing cart items if items are added to the cart
        $cart = SpAdministrationStaffModel::saAdminCartView($user_id);

        //If cart items are found
        if ($cart) {
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
            View::renderTemplate('AdministrationStaff/aStaffCartNewView.html', [
                'cart' => $cart,
                'allSum' => $allSum, 'cardSum' => $cardSum, 'cashSum' => $cashSum
            ]);
        }
    }
    //End of Cart page's initial view of saAdmin 

    //Start of checking out in cart view
    public function saAdminBookingsuccessnotificationAction()
    {
        //Get the current user's details with session using Auth'
        $current_user = Auth::getUser();
        $saAdmin_id = $current_user->user_id;

        //Assigning the variables
        $first_name = $_POST['first_name'];
        $last_name = $_POST['last_name'];
        $primary_contact = $_POST['phone'];

        //Get payment for that bookings
        $payment_id = SpAdministrationStaffModel::saAdminAddbookingPaymentSuccess($saAdmin_id, $first_name, $last_name, $primary_contact);

        //If the cash payment is successful
        if ($payment_id) {

            //Send payment successfull notification
            $success = NotificationModel::saAdminAddbookingPaymentSuccessNotification($current_user, $first_name, $last_name, $primary_contact, $payment_id);

            //If notification is successful
            if ($success) {
                $this->redirect('/spadministrationstaff/managebookings');
            }
        }
    }
    //End of checking out in cart view

    // Start of removing a booking from visitor's cart 
    public function clearbookingAction()
    {
        //Assigning the relevant variables
        $booking_id = $this->route_params['id'];

        $clearedSlot = SpAdministrationStaffModel::saAdminClearBooking($booking_id);

        if ($clearedSlot) {
            echo true;
        }
    }
    // End of removing a booking from visitor's cart 

    //End of Administration Staff's cart view
    /*--------------------------------------------------------------------------------------------------*/
}
