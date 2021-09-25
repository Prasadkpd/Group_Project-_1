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
        $bookings= SpAdministrationStaffModel::saAdminViewBookings($id);
        $cancelBookings= SpAdministrationStaffModel::saAdminCancelBookings($id);
        $bookingPayments= SpAdministrationStaffModel::saAdminBookingPayment($id);
        // var_dump($bookings);
        //direct to the customer page
        View::renderTemplate('AdministrationStaff/aStaffManageBookingsView.html',['bookings'=>$bookings,
        'cancelBookings'=>$cancelBookings,'bookingPayments'=>$bookingPayments]);


        
    }
    public function managebookingsAction()
    {
        $current_user= Auth::getUser();
        $id=$current_user->user_id;
        $bookings= SpAdministrationStaffModel::saAdminViewBookings($id);
        $cancelBookings= SpAdministrationStaffModel::saAdminCancelBookings($id);
        $bookingPayments= SpAdministrationStaffModel::saAdminBookingPayment($id);
        // var_dump($bookings);
        //direct to the customer page
        View::renderTemplate('AdministrationStaff/aStaffManageBookingsView.html',['bookings'=>$bookings,
        'cancelBookings'=>$cancelBookings,'bookingPayments'=>$bookingPayments]);
       
    }

    public  function saadminnotificationAction()
    {
        $current_user= Auth::getUser();
        $id=$current_user->user_id;
        $notifications= SpAdministrationStaffModel::saAdminNotification($id);
        View::renderTemplate('AdministrationStaff/aStaffNotificationView.html',['notifications'=>$notifications]);
    }

    public  function managetimeslotAction()
    {
        $current_user= Auth::getUser();
        $id=$current_user->user_id;
        $viewTimeSlots= SpAdministrationStaffModel::saAdminViewTimeSlots($id);
        $deleteTimeSlots= SpAdministrationStaffModel::saAdminDeleteTimeSlots($id);
        $viewFacilities= SpAdministrationStaffModel::saAdminViewFacility($id);
        View::renderTemplate('AdministrationStaff/aStaffManageTimeslotsView.html',['timeSlots'=>$viewTimeSlots,
        'deleteTimeSlots'=>$deleteTimeSlots,'viewFacilities'=>$viewFacilities]);
    }

    public function createtimeslotAction()
    {
        $current_user= Auth::getUser();
        $id=$current_user->user_id;

        $user=SpAdministrationStaffModel::saAdminAddTimeSlots($_POST['stime'],$_POST['etime'],$_POST['amount'],$_POST['fid'],$id);

        $this->redirect('/Spadministrationstaff/managetimeslot');
    }

    public function managefacilityAction()
    {

        $current_user= Auth::getUser();
        $id=$current_user->user_id;
        $viewFacilities= SpAdministrationStaffModel::saAdminViewFacility($id);
        $deleteFacilities= SpAdministrationStaffModel::saAdminDeleteFacility($id);
        $updateFacilities= SpAdministrationStaffModel::saAdminUpdateFacility($id);
        View::renderTemplate('AdministrationStaff/aStaffManageFacilityView.html',['viewFacilities'=>$viewFacilities,
        'deleteFacilities'=>$deleteFacilities,'updateFacilities'=>$updateFacilities]);
    }



    
}