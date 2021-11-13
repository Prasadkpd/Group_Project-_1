<?php

namespace App\Models;

use Core\Model;
use PDO;
use PDOException;
use App\Auth;

class NotificationModel extends \Core\Model
{
    // Array of Error messages
    public $errors = [];

    //Start of Class constructor  
    public function __construct($data = [])
    {
        // Change the format of the key value pairs sent 
        // from the controller use in the model
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }
    //End of Class constructor  
    //Start of 
    public static function notificationGetManagerIds($invoice_id)
    {
        $sql = 'SELECT `manager`.`user_id` 
                FROM `manager` 
                INNER JOIN `booking` ON `manager`.`sports_arena_id`= `booking`.`sports_arena_id`
                WHERE `booking`.`invoice_id`=:invoice_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':invoice_id', $invoice_id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $mid = $result["user_id"];

        return $mid;
    }

    public static function notificationGetAdminStaffIds($invoice_id)
    {
        $sql = 'SELECT `administration_staff`.`user_id` 
                FROM `administration_staff` 
                INNER JOIN `booking` ON `administration_staff`.`sports_arena_id`=`booking`.`sports_arena_id`
                WHERE `booking`.`invoice_id`=:invoice_id';


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':invoice_id', $invoice_id, PDO::PARAM_INT);

        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $asid = $result["user_id"];

        return $asid;
    }

    public static function notificationGetBookingStaffIds($invoice_id)
    {
        $sql = 'SELECT `booking_handling_staff`.`user_id` 
                FROM `booking_handling_staff` 
                INNER JOIN `booking` ON `booking_handling_staff`.`sports_arena_id`=`booking`.`sports_arena_id`
                WHERE `booking`.`invoice_id`=:invoice_id';


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':invoice_id', $invoice_id, PDO::PARAM_INT);

        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $bhid = $result["user_id"];

        return $bhid;
    }

    public static function cancelNotificationGetCustomerIds($booking_id)
    {
        
        $sql = 'SELECT user.user_id, user.first_name, user.last_name FROM user
                INNER JOIN booking ON user.user_id=booking.customer_user_id
                WHERE user.type="customer" AND booking.booking_id=:booking_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':booking_id', $booking_id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result;
    }

    public static function cancelNotificationGetManagerIds($booking_id)
    {
        
        $sql = 'SELECT manager.user_id
                FROM manager
                INNER JOIN booking ON manager.sports_arena_id= booking.sports_arena_id
                WHERE booking.booking_id=:booking_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':booking_id', $booking_id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $mid = $result["user_id"];

        return $mid;
    }

    public static function cancelNotificationGetAdminStaffIds($booking_id)
    {
        $sql = 'SELECT `administration_staff`.`user_id` 
                FROM `administration_staff` 
                INNER JOIN `booking` ON `administration_staff`.`sports_arena_id`=`booking`.`sports_arena_id`
                WHERE `booking`.`booking_id`=:booking_id';


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':booking_id', $booking_id, PDO::PARAM_INT);

        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $asid = $result["user_id"];

        return $asid;
    }

    public static function cancelNotificationGetBookingStaffIds($booking_id)
    {
        $sql = 'SELECT `booking_handling_staff`.`user_id` 
                FROM `booking_handling_staff` 
                INNER JOIN `booking` ON `booking_handling_staff`.`sports_arena_id`=`booking`.`sports_arena_id`
                WHERE `booking`.`booking_id`=:booking_id';


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':booking_id', $booking_id, PDO::PARAM_INT);

        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $bhid = $result["user_id"];

        return $bhid;
    }


    // =====================================
    // SEND BOOKING CONFIRMATION NOTIFICATIONS
    public static function addNotificationBookingSuccess($current_user, $invoice_id)
    {
        // Defining arena staff ids
        $manager_id = self::notificationGetManagerIds($invoice_id);
        $adminstaff_id = self::notificationGetAdminStaffIds($invoice_id);
        $bookhandlestaff_id = self::notificationGetBookingStaffIds($invoice_id);

        $subject = array("customer" => "Booking Confirmation Message", "sports_arena" => "Facility Booking Message");
        $p_level = "low";

        $db = static::getDB();

        // Dividing subject to variables
        $custsubj = $subject["customer"];
        $sparsubj = $subject["sports_arena"];

        $data_query = "SELECT
             `booking`.`booking_date`,
             `facility`.`facility_name`,
             `sports_arena_profile`.`sa_name`,
             `sports_arena_profile`.`google_map_link`,
             `time_slot`.`start_time`,
             `time_slot`.`end_time`
        FROM `booking` 
        INNER JOIN `facility` ON `facility`.facility_id = booking.facility_id
        INNER JOIN `sports_arena_profile` ON `sports_arena_profile`.sports_arena_id = `booking`.sports_arena_id
        INNER JOIN `booking_timeslot` ON `booking_timeslot`.`booking_id`= `booking`.`booking_id`
        INNER JOIN `time_slot` ON `time_slot`.`time_slot_id`=`booking_timeslot`.`timeslot_id`
        WHERE `booking`.`invoice_id` = :invoice_id ";

        $data_stmt = $db->prepare($data_query);
        $data_stmt->bindValue(':invoice_id', $invoice_id, PDO::PARAM_INT);
        $data_stmt->execute();

        // **********************************
        // CUSTOMER NOTIFICATION REQUIREMENTS

        $data = $data_stmt->fetch(PDO::FETCH_ASSOC);

        // Select facility name 
        $facname = $data["facility_name"];

        // Select sports arena name and map link
        $saname = $data["sa_name"];
        $maplink = $data["google_map_link"];

        // Select time slot duration
        $stime = $data["start_time"];
        $etime = $data["end_time"];

        // **************************************
        // SPORTS ARENA NOTIFICATION REQUIREMENTS

        // Initialize customer id, first name and last name
        $customer_id = $current_user->user_id;
        $fname = $current_user->first_name;
        $lname = $current_user->last_name;

        // Select booking date
        $bdate = $data["booking_date"];


        // Initialize descriptions
        $custdesc = "You have successfully made a booking with Sportizza to " . $facname . " of " . $saname . " on " . $bdate . " from " . $stime . " to " . $etime . ". To find out the location, click " . $maplink;
        $spardesc = $fname . " " . $lname . " has booked " . $facname . " on " . $bdate . " from " . $stime . " to " . $etime . ".";

        // **************************************
        // INSERT QUERIES TO USERS


        $sql5 = 'INSERT INTO `notification`(`user_id`, `subject`, `priority`, `description`) VALUES (:uid,:subject,:p_level,:desc)';
        $stmt5 = $db->prepare($sql5);

        // for customer
        $stmt5->execute(['uid' => $customer_id, 'subject' => $custsubj, 'p_level' => $p_level, 'desc' => $custdesc]);

        // for manager
        $stmt5->execute(['uid' => $manager_id, 'subject' => $sparsubj, 'p_level' => $p_level, 'desc' => $spardesc]);


        // for administartion staff
        $stmt5->execute(['uid' => $adminstaff_id, 'subject' => $sparsubj, 'p_level' => $p_level, 'desc' => $spardesc]);


        // for booking handling staff
        return ($stmt5->execute(['uid' => $bookhandlestaff_id, 'subject' => $sparsubj, 'p_level' => $p_level, 'desc' => $spardesc]));
    }
    // END OF SEND BOOKING CONFIRMATION NOTIFICATIONS
    // =====================================

    // =====================================
    // SEND CANCEL BOOKING NOTIFICATIONS
    public static function cancelNotificationBookingSuccess($current_user, $booking_id)
    {
        // Defining arena staff ids
        $manager_id = self::cancelNotificationGetManagerIds($booking_id);
        $adminstaff_id = self::cancelNotificationGetAdminStaffIds($booking_id);
        $bookhandlestaff_id = self::cancelNotificationGetBookingStaffIds($booking_id);

        $subject = array("customer" => "Booking Cancellation Message", "sports_arena" => "Facility Booking Cancellation Message");
        $p_level = "high";

        $db = static::getDB();

        // Dividing subject to variables
        $custsubj = $subject["customer"];
        $sparsubj = $subject["sports_arena"];

        $data_query = "SELECT
             `booking`.`booking_date`,
             `facility`.`facility_name`,
             `sports_arena_profile`.`sa_name`,
             `booking`.`payment_method`,
             `time_slot`.`start_time`,
             `time_slot`.`end_time`
        FROM `booking` 
        INNER JOIN `facility` ON `facility`.facility_id = booking.facility_id
        INNER JOIN `sports_arena_profile` ON `sports_arena_profile`.sports_arena_id = `booking`.sports_arena_id
        INNER JOIN `booking_timeslot` ON `booking_timeslot`.`booking_id`= `booking`.`booking_id`
        INNER JOIN `time_slot` ON `time_slot`.`time_slot_id`=`booking_timeslot`.`timeslot_id`
        WHERE `booking`.`booking_id` = :booking_id ";

        $data_stmt = $db->prepare($data_query);
        $data_stmt->bindValue(':booking_id', $booking_id, PDO::PARAM_INT);
        $data_stmt->execute();

        // **********************************
        // CUSTOMER NOTIFICATION REQUIREMENTS

        $data = $data_stmt->fetch(PDO::FETCH_ASSOC);

        // Select facility name 
        $facname = $data["facility_name"];

        // Select sports arena name and payment method
        $saname = $data["sa_name"];
        $paymethod = $data["payment_method"];

        // Select time slot duration
        $stime = $data["start_time"];
        $etime = $data["end_time"];

        // **************************************
        // SPORTS ARENA NOTIFICATION REQUIREMENTS

        // Initialize customer id, first name and last name
        $customer_id = $current_user->user_id;
        $fname = $current_user->first_name;
        $lname = $current_user->last_name;
        

        // Select booking date
        $bdate = $data["booking_date"];


        // Initialize descriptions
        $custdesc = "You have successfully cancelled your booking with Sportizza to " . $facname . " of " . $saname . " on " . $bdate . " scheduled from " . $stime . " to " . $etime . ".";
        $spardesc = $fname . " " . $lname . " has cancelled his booking to " . $facname . " on " . $bdate . " scheduled from " . $stime . " to " . $etime . ".";

        if ($paymethod == "card") {
            $custdesc .= "Please click this link for request refund";
            $link = "http://localhost/customer/refund/".$booking_id;
        }
        else{
            $link = "";
        }

        // **************************************
        // INSERT QUERIES TO USERS
        
   
        $sql5 = 'INSERT INTO notification(user_id,subject, priority, description,link) VALUES (:uid,:subject,:p_level,:desc,:link)';
        $stmt5 = $db->prepare($sql5);

        // for customer
        $stmt5->execute([':uid'=> $customer_id, ':subject'=> $custsubj, ':p_level'=> $p_level, ':desc'=> $custdesc,':link'=>$link]);

        // for manager
        $stmt5->execute(['uid' => $manager_id, 'subject' => $sparsubj, 'p_level' => $p_level, 'desc' => $spardesc,':link'=>""]);


        // for administartion staff
        $stmt5->execute(['uid' => $adminstaff_id, 'subject' => $sparsubj, 'p_level' => $p_level, 'desc' => $spardesc,':link'=>""]);


        // for booking handling staff
        return ($stmt5->execute(['uid' => $bookhandlestaff_id, 'subject' => $sparsubj, 'p_level' => $p_level, 'desc' => $spardesc,':link'=>""]));
    }
    // END OF CANCEL BOOKING SEND NOTIFICATIONS
    // =====================================

    // =====================================
    // SEND CASH BOOKING PAYMENT NOTIFICATIONS
    public static function managerNotificationBookingSuccess($current_user, $booking_id)
    {
        // Defining arena staff ids
        $customer = self::cancelNotificationGetCustomerIds($booking_id);
        $first_name = $customer["first_name"];
        $last_name = $customer["last_name"];

        $subject = array("customer" => "Payment Confirmation Message", "sports_arena" => "Cash Payment Confirmation Message");
        $p_level = "high";

        $db = static::getDB();

        // Dividing subject to variables
        $custsubj = $subject["customer"];
        $sparsubj = $subject["sports_arena"];

        $data_query = "SELECT
             `booking`.`booking_date`,
             `facility`.`facility_name`,
             `sports_arena_profile`.`sa_name`,
             `time_slot`.`start_time`,
             `time_slot`.`end_time`
        FROM `booking` 
        INNER JOIN `facility` ON `facility`.facility_id = booking.facility_id
        INNER JOIN `sports_arena_profile` ON `sports_arena_profile`.sports_arena_id = `booking`.sports_arena_id
        INNER JOIN `booking_timeslot` ON `booking_timeslot`.`booking_id`= `booking`.`booking_id`
        INNER JOIN `time_slot` ON `time_slot`.`time_slot_id`=`booking_timeslot`.`timeslot_id`
        WHERE `booking`.`booking_id` = :booking_id ";

        $data_stmt = $db->prepare($data_query);
        $data_stmt->bindValue(':booking_id', $booking_id, PDO::PARAM_INT);
        $data_stmt->execute();

        // **********************************
        // CUSTOMER NOTIFICATION REQUIREMENTS

        $data = $data_stmt->fetch(PDO::FETCH_ASSOC);

        // Select facility name 
        $facname = $data["facility_name"];

        // Select sports arena name
        $saname = $data["sa_name"];

        // Select time slot duration
        $stime = $data["start_time"];
        $etime = $data["end_time"];

        // **************************************
        // SPORTS ARENA NOTIFICATION REQUIREMENTS

        // Initialize customer id, first name and last name
        $staff_id = $current_user->user_id;
        // $fname = $current_user->first_name;
        // $lname = $current_user->last_name;

        // Select booking date
        $bdate = $data["booking_date"];


        // Initialize descriptions
        $custdesc = "Your payment in cash for the booking to " . $facname . " of " . $saname . " on " . $bdate . " scheduled from " . $stime . " to " . $etime . " has been confirmed by " . $saname . ". Thank you for choosing Sportizza. Please do visit us again!";
        $spardesc = "You have approved the payment in cash for the booking to " . $facname . " on " . $bdate . " scheduled from " . $stime . " to " . $etime . " by " . $first_name . " " . $last_name . ".";

        // if($paymethod=="card"){
        //     $custdesc .= "Refund details will be sent to you later.";
        // }

        // **************************************
        // INSERT QUERIES TO USERS


        $sql5 = 'INSERT INTO `notification`(`user_id`, `subject`, `priority`, `description`) VALUES (:uid,:subject,:p_level,:desc)';
        $stmt5 = $db->prepare($sql5);

        // for customer
        $stmt5->execute(['uid' => $customer["user_id"], 'subject' => $custsubj, 'p_level' => $p_level, 'desc' => $custdesc]);

        // for staff
        return ($stmt5->execute(['uid' => $staff_id, 'subject' => $sparsubj, 'p_level' => $p_level, 'desc' => $spardesc]));


        // // for administartion staff
        // $stmt5->execute(['uid'=> $adminstaff_id, 'subject'=> $sparsubj, 'p_level'=> $p_level, 'desc'=>$spardesc]);


        // // for booking handling staff
        // return ($stmt5->execute(['uid'=> $bookhandlestaff_id, 'subject'=> $sparsubj, 'p_level'=> $p_level, 'desc'=>$spardesc]));

    }
    // END OF CASH BOOKING PAYMENT NOTIFICATIONS
    // =====================================

    //Start of administration staff booking cancellation notification
    public static function customerEmergBookingCancelNotification($current_user, $booking_id)
    {
        try {
            $db = static::getDB();
            $customer = self::cancelNotificationGetCustomerIds($booking_id);
            $customer_id = $customer['user_id'];
            $manager_id = self::cancelNotificationGetManagerIds($booking_id);
            $adminstaff_id = self::cancelNotificationGetAdminStaffIds($booking_id);
            $bookhandlestaff_id = self::cancelNotificationGetBookingStaffIds($booking_id);

            $custsubj = "Emergency booking cancellation";
            $sparsubj = "Booking cancellation by sports arena";

            $db->beginTransaction();
            $data_query = "SELECT
             `booking`.`booking_date`,
             `facility`.`facility_name`,
             `sports_arena_profile`.`sa_name`,
             `time_slot`.`start_time`,
             `time_slot`.`end_time`,
             `booking_cancellation`.`reason`,
             `booking`.`payment_method`,
             `booking`.`payment_status`
        FROM `booking` 
        INNER JOIN `facility` ON `facility`.facility_id = booking.facility_id
        INNER JOIN `sports_arena_profile` ON `sports_arena_profile`.sports_arena_id = `booking`.sports_arena_id
        INNER JOIN `booking_timeslot` ON `booking_timeslot`.`booking_id`= `booking`.`booking_id`
        INNER JOIN `time_slot` ON `time_slot`.`time_slot_id`=`booking_timeslot`.`timeslot_id`
        INNER JOIN `booking_cancellation` ON `booking_cancellation`.`booking_id`= `booking`.`booking_id`
        WHERE `booking`.`booking_id` = :booking_id ";

            $data_stmt = $db->prepare($data_query);
            $data_stmt->bindValue(':booking_id', $booking_id, PDO::PARAM_INT);
            $data_stmt->execute();

            // CUSTOMER NOTIFICATION REQUIREMENTS
            $data = $data_stmt->fetch(PDO::FETCH_ASSOC);

            // Select facility name 
            $facname = $data["facility_name"];

            // Select sports arena name
            $saname = $data["sa_name"];
            $reason = $data["reason"];
            // Select time slot duration
            $stime = $data["start_time"];
            $etime = $data["end_time"];

            $payment_method = $data["payment_method"];
            $payment_status = $data["payment_status"];
            // Select reason for cancellation
            $p_level = "high";

            // **************************************
            // SPORTS ARENA NOTIFICATION REQUIREMENTS

            // Initialize first name and last name of the staff member
            $fname = $current_user->first_name;
            $lname = $current_user->last_name;

            // Select booking date
            $bdate = $data["booking_date"];

            if ($payment_method == 'cash' && $payment_status == 'unpaid') {
                // Initialize descriptions
                $custdesc = " " . $saname . " had cancel your booking your booking " . $booking_id . "made for" . $facname . " on " . $bdate . "scheduled from " . $stime . " to " . $etime . " . Reason for cancellation: " . $reason . " .";
            } else {
                // Initialize descriptions
                $custdesc = " " . $saname . " had cancel your booking your booking " . $booking_id . "made for" . $facname . " on " . $bdate . "scheduled from " . $stime . " to " . $etime . " . Reason for cancellation: " . $reason . " . Please apply for refund form to collect your refund. Note that we'll be making a bank transfer";
            }

            $spardesc = "" . $fname . " " . $lname . " has cancelled the booking " . $booking_id . " made for " . $facname . " on " . $bdate . " scheduled from " . $stime . " to " . $etime . " . Reason for cancellation: " . $reason . " .";

            $sql = 'INSERT INTO `notification`(`user_id`, `subject`, `priority`, `description`) VALUES (:uid,:subject,:p_level,:desc)';
            $stmt = $db->prepare($sql);

            print_r(['uid' => $customer_id, 'subject' => $custsubj, 'p_level' => $p_level, 'desc' => $custdesc]);
            // for customer
            $stmt->execute(['uid' => $customer_id, 'subject' => $custsubj, 'p_level' => $p_level, 'desc' => $custdesc]);

            // for manager
            $stmt->execute(['uid' => $manager_id, 'subject' => $sparsubj, 'p_level' => $p_level, 'desc' => $spardesc]);

            // for administartion staff
            $stmt->execute(['uid' => $adminstaff_id, 'subject' => $sparsubj, 'p_level' => $p_level, 'desc' => $spardesc]);

            // for booking handling staff
            $stmt->execute(['uid' => $bookhandlestaff_id, 'subject' => $sparsubj, 'p_level' => $p_level, 'desc' => $spardesc]);

            // Make the changes to the database permanent
            $db->commit();
        } catch (PDOException $e) {
            $db->rollback();
            throw $e;
        }
    }
}
