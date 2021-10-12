<?php


namespace App\Controllers;


use Core\View;
use App\Auth;
use App\Models\SpArenaManagerModel;
use App\Models\NotificationModel;

class Sparenamanager extends \Core\Controller
{

    protected function before()
    {
        if(Auth::getUser()->type=='Manager'){
            
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
        View::renderTemplate('Manager/mStaffProfileView.html');

    }

    public function managerprofileAction()
    {

        $current_user= Auth::getUser();
        $id=$current_user->user_id;
        $bookings= SpArenaManagerModel::managerViewBookings($id);
        $cancelBookings= SpArenaManagerModel::managerCancelBookings($id);
        $bookingPayments= SpArenaManagerModel::managerBookingPayment($id);
        // var_dump($bookings);
        //direct to the customer page
        View::renderTemplate('Manager/mStaffProfileView.html',['bookings'=>$bookings,
        'cancelBookings'=>$cancelBookings,'bookingPayments'=>$bookingPayments]);

    }
    public function managebookingsAction()
    {
        $current_user= Auth::getUser();
        $id=$current_user->user_id;
        $bookings= SpArenaManagerModel::managerViewBookings($id);
        $cancelBookings= SpArenaManagerModel::managerCancelBookings($id);
        $bookingPayments= SpArenaManagerModel::managerBookingPayment($id);
        // var_dump($bookings);
        //direct to the customer page
        View::renderTemplate('Manager/mStaffManageBookingsView.html',['bookings'=>$bookings,
        'cancelBookings'=>$cancelBookings,'bookingPayments'=>$bookingPayments]);
       
    }

    public function getpaymentAction()
    {
        $current_user= Auth::getUser();
        $booking_id=$this->route_params['id'];
        $cash_update=SpArenaManagerModel::updateBookingPayment($booking_id);
        $notify_check=NotificationModel::managerNotificationBookingSuccess($current_user,$booking_id);

        if($cash_update) {
            if($notify_check) {
                $this->redirect('/Sparenamanager/managernotification');
            }
        }

        // if($current_user->type=="manager"){
        //     $notify_check=NotificationModel::cancelNotificationGetAdminStaffIds($booking_id);
        //     $notify_check=NotificationModel::cancelNotificationGetBookingStaffIds($booking_id);
        // } elseif($current_user->type=="administration_staff"){
        //     $notify_check=NotificationModel::cancelNotificationGetManagerIds($booking_id);
        //     $notify_check=NotificationModel::cancelNotificationGetBookingStaffIds($booking_id);
        // } elseif($current_user->type=="booking_handling_staff"){
        //     $notify_check=NotificationModel::cancelNotificationGetManagerIds($booking_id);
        //     $notify_check=NotificationModel::cancelNotificationGetAdminStaffIds($booking_id);
        // }
    }

    public  function managernotificationAction()
    {
        $current_user= Auth::getUser();
        $id=$current_user->user_id;
        $notifications= SpArenaManagerModel::managerNotification($id);
        View::renderTemplate('Manager/mStaffNotificationView.html',['notifications'=>$notifications]);
    }

    public  function managetimeslotAction()
    {
        $current_user= Auth::getUser();
        $id=$current_user->user_id;
        $viewTimeSlots= SpArenaManagerModel::managerViewTimeSlots($id);
        $deleteTimeSlots= SpArenaManagerModel::managerDeleteTimeSlots($id);
        $selectFacility= SpArenaManagerModel::managerGetFacilityName($id);
        View::renderTemplate('Manager/mStaffManageTimeslotsView.html',['timeSlots'=>$viewTimeSlots,
        'deleteTimeSlots'=>$deleteTimeSlots,'selectFacility'=>$selectFacility]);
    }

    public function managefacilityAction()
    {

        $current_user= Auth::getUser();
        $id=$current_user->user_id;
        $viewFacilities= SpArenaManagerModel::managerViewFacility($id);
        $deleteFacilities= SpArenaManagerModel::managerDeleteFacility($id);
        $updateFacilities= SpArenaManagerModel::managerUpdateFacility($id);
        View::renderTemplate('Manager/mStaffManageFacilityView.html',['viewFacilities'=>$viewFacilities,
        'deleteFacilities'=>$deleteFacilities,'updateFacilities'=>$updateFacilities]);
    }

    public function manageusersAction()
    {

        $current_user= Auth::getUser();
        $id=$current_user->user_id;
        $viewStaff= SpArenaManagerModel::managerViewStaff($id);
        $removeStaff= SpArenaManagerModel::managerRemoveStaff($id);
    
        View::renderTemplate('Manager/mStaffManageUsersView.html',['viewStaff'=>$viewStaff,
        'removeStaff'=>$removeStaff]);
        
        
    }

    public function manageanalyticsAction()
    {
        $current_user= Auth::getUser();
        $id=$current_user->user_id;
        $chart1=SpArenaManagerModel::managerChart1($id);
        $chart2=SpArenaManagerModel::managerChart2($id);
        $chart3=SpArenaManagerModel::managerChart3($id);
        $chart4=SpArenaManagerModel::managerChart4($id);
        //direct to the manager page
        View::renderTemplate('Manager/mStaffAnalyticsView.html',
        ['chart1'=>$chart1, 'chart2'=>$chart2, 'chart3'=>$chart3, 'chart4'=>$chart4]);
    }

    public  function managereditarenaprofileAction()
    {
        $current_user= Auth::getUser();
        // $id=$current_user->user_id;
        View::renderTemplate('Manager/mStaffEditArenaProfile.html');
    }
    public  function cartAction()
    {
        View::renderTemplate('Manager/mStaffCartNewView.html');
    }
 





    
}