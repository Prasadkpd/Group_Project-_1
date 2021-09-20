<?php


namespace App\Controllers;


use Core\View;
use App\Auth;
use App\Models\SpAdministrationStaffModel;

class Spadministrationstaff extends \Core\Controller
{

    protected function before()
    {
        if(Auth::getUser()->type=='AdministrationStaff'){
            
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
        $bookings= SpAdministrationStaffModel::managerViewBookings($id);
        $cancelBookings= SpAdministrationStaffModel::managerCancelBookings($id);
        $bookingPayments= SpAdministrationStaffModel::managerBookingPayment($id);
        // var_dump($bookings);
        //direct to the customer page
        View::renderTemplate('Manager/mStaffManageBookingsView.html',['bookings'=>$bookings,
        'cancelBookings'=>$cancelBookings,'bookingPayments'=>$bookingPayments]);


        
    }
    public function managebookingsAction()
    {
        $current_user= Auth::getUser();
        $id=$current_user->user_id;
        $bookings= SpAdministrationStaffModel::managerViewBookings($id);
        $cancelBookings= SpAdministrationStaffModel::managerCancelBookings($id);
        $bookingPayments= SpAdministrationStaffModel::managerBookingPayment($id);
        // var_dump($bookings);
        //direct to the customer page
        View::renderTemplate('Manager/mStaffManageBookingsView.html',['bookings'=>$bookings,
        'cancelBookings'=>$cancelBookings,'bookingPayments'=>$bookingPayments]);
       
    }

    public  function managernotificationAction()
    {
        $current_user= Auth::getUser();
        $id=$current_user->user_id;
        $notifications= SpAdministrationStaffModel::managerNotification($id);
        View::renderTemplate('Manager/mStaffNotificationView.html',['notifications'=>$notifications]);
    }

    public  function managetimeslotAction()
    {
        $current_user= Auth::getUser();
        $id=$current_user->user_id;
        $viewTimeSlots= SpAdministrationStaffModel::managerViewTimeSlots($id);
        $deleteTimeSlots= SpAdministrationStaffModel::managerDeleteTimeSlots($id);
        View::renderTemplate('Manager/mStaffManageTimeslotsView.html',['timeSlots'=>$viewTimeSlots,
        'deleteTimeSlots'=>$deleteTimeSlots]);
    }

    public function managefacilityAction()
    {

        $current_user= Auth::getUser();
        $id=$current_user->user_id;
        $viewFacilities= SpAdministrationStaffModel::managerViewFacility($id);
        $deleteFacilities= SpAdministrationStaffModel::managerDeleteFacility($id);
        $updateFacilities= SpAdministrationStaffModel::managerUpdateFacility($id);
        View::renderTemplate('Manager/mStaffManageFacilityView.html',['viewFacilities'=>$viewFacilities,
        'deleteFacilities'=>$deleteFacilities,'updateFacilities'=>$updateFacilities]);
    }

    public function manageusersAction()
    {

        $current_user= Auth::getUser();
        $id=$current_user->user_id;
        $viewStaff= SpAdministrationStaffModel::managerViewStaff($id);
        $removeStaff= SpAdministrationStaffModel::managerRemoveStaff($id);
    
        View::renderTemplate('Manager/mStaffManageUsersView.html',['viewStaff'=>$viewStaff,
        'removeStaff'=>$removeStaff]);
        
        
    }

    public function manageanalyticsAction()
    {
        View::renderTemplate('SpArenaManager/manage-users.html');
    }
 





    
}