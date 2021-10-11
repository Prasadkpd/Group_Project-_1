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
        // var_dump($bookings);
        //direct to the customer page
        View::renderTemplate('AdministrationStaff/aStaffProfileView.html');


        
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
        $selectFacility= SpAdministrationStaffModel::saAdminGetFacilityName($id);
        
        View::renderTemplate('AdministrationStaff/aStaffManageTimeslotsView.html',['timeSlots'=>$viewTimeSlots,'deleteTimeSlots'=>$deleteTimeSlots,'selectFacility'=>$selectFacility]);
    }

    public function createtimeslotAction()
    {
        $current_user= Auth::getUser();
        $id=$current_user->user_id;

        $user=SpAdministrationStaffModel::saAdminAddTimeSlots($_POST['stime'],$_POST['etime'],$_POST['amount'],$_POST['fid'],$id);

        $this->redirect('/Spadministrationstaff/managetimeslot');
    }

    public function deletetimeslotAction()
    {
        $user=SpAdministrationStaffModel::saAdminDeleteTimeSlots($id);
    }

    public function managefacilityAction()
    {

        $current_user= Auth::getUser();
        $id=$current_user->user_id;
        $viewFacilities= SpAdministrationStaffModel::saAdminViewFacility($id);
        $deleteFacilities=SpAdministrationStaffModel::saAdminDeleteFacility($id);
        $updateFacilities = SpAdministrationStaffModel::saAdminUpdateFacility($id);

        View::renderTemplate('AdministrationStaff/aStaffManageFacilityView.html',['viewFacilities'=>$viewFacilities,'deleteFacilities'=>$deleteFacilities,'updateFacilities' => $updateFacilities]);
    }

    public function createfacilityAction()
    {
        $current_user= Auth::getUser();
        $id=$current_user->user_id;
        $psw=$current_user->password;

        $user=SpAdministrationStaffModel::saAdminAddFacility($_POST['fname'],$_POST['psw'],$id,$psw);

        if($user){
            $this->redirect('/Spadministrationstaff/managetimeslot');
        } else {
            $this->redirect('/Spadministrationstaff/saadminnotification');
        }

    }

    public function deletefacilityAction()
    {
        $user=SpAdministrationStaffModel::saAdminDeleteFacility($id);
    }
    public  function managereditarenaprofileAction()
    {
        $current_user= Auth::getUser();
        // $id=$current_user->user_id;
       
    }
    public  function saAdmineditarenaprofileAction()
    {
        $current_user= Auth::getUser();
        // $id=$current_user->user_id;
        View::renderTemplate('AdministrationStaff/aStaffEditArenaProfile.html');
    }
 
 
    
}