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
                INNER JOIN user ON user.user_id = manager.user_id
                WHERE `booking`.`invoice_id`=:invoice_id AND user.security_status="active"';

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
                INNER JOIN user ON user.user_id = administration_staff.user_id
                WHERE `booking`.`invoice_id`=:invoice_id AND user.security_status="active"';


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':invoice_id', $invoice_id, PDO::PARAM_INT);

        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $asid = $result["user_id"];
            return $asid;
        } else {
            return;
        }
    }

    public static function notificationGetBookingStaffIds($invoice_id)
    {
        $sql = 'SELECT `booking_handling_staff`.`user_id` 
                FROM `booking_handling_staff` 
                INNER JOIN `booking` ON `booking_handling_staff`.`sports_arena_id`=`booking`.`sports_arena_id`
                INNER JOIN user ON user.user_id = booking_handling_staff.user_id
                WHERE `booking`.`invoice_id`=:invoice_id AND user.security_status="active"';


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':invoice_id', $invoice_id, PDO::PARAM_INT);

        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $bhid = $result["user_id"];
            return $bhid;
        } else {
            return;
        }
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

    public static function timeslotcancelNotificationGetCustomerIds($timeslot_id)
    {
        $sql = 'SELECT user.user_id, user.first_name, user.last_name FROM user
                INNER JOIN booking ON user.user_id=booking.customer_user_id
                INNER JOIN booking_timeslot ON booking.booking_id =booking_timeslot.booking_id
                WHERE user.type="customer" AND booking_timeslot.timeslot_id=:timeslot_id 
                AND booking_timeslot.security_status="active" AND booking.security_status="active"';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':timeslot_id', $timeslot_id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        var_dump($result);
        return $result;
    }

    public static function cancelNotificationGetManagerIds($booking_id)
    {
        $sql = 'SELECT manager.user_id
                FROM manager
                INNER JOIN booking ON manager.sports_arena_id= booking.sports_arena_id
                INNER JOIN user ON user.user_id = manager.user_id
                WHERE booking.booking_id=:booking_id AND user.security_status="active"';

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
                INNER JOIN user ON user.user_id = administration_staff.user_id
                WHERE `booking`.`booking_id`=:booking_id AND user.security_status="active"';


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':booking_id', $booking_id, PDO::PARAM_INT);

        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $asid = $result["user_id"];
            return $asid;
        } else {
            return;
        }
    }

    public static function cancelNotificationGetBookingStaffIds($booking_id)
    {
        $sql = 'SELECT `booking_handling_staff`.`user_id` 
                FROM `booking_handling_staff` 
                INNER JOIN `booking` ON `booking_handling_staff`.`sports_arena_id`=`booking`.`sports_arena_id`
                INNER JOIN user ON user.user_id = booking_handling_staff.user_id
                WHERE `booking`.`booking_id`=:booking_id AND user.security_status="active"';


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':booking_id', $booking_id, PDO::PARAM_INT);

        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $bhid = $result["user_id"];
            return $bhid;
        } else {
            return;
        }
    }


    public static function AddtimeslotNotificationGetManagerIds($timeslot_id)
    {
        $sql = 'SELECT manager.user_id
                FROM manager
                INNER JOIN time_slot ON manager.sports_arena_id= time_slot.manager_sports_arena_id
                INNER JOIN user ON user.user_id = manager.user_id
                WHERE time_slot.time_slot_id=:timeslot_id AND user.security_status="active"';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':timeslot_id', $timeslot_id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $mid = $result["user_id"];

        return $mid;
    }

    public static function AddtimeslotNotificationGetAdminStaffIds($timeslot_id)
    {
        $sql = 'SELECT `administration_staff`.`user_id` 
                FROM `administration_staff` 
                INNER JOIN time_slot ON `administration_staff`.`sports_arena_id`=time_slot.`manager_sports_arena_id`
                INNER JOIN user ON user.user_id = administration_staff.user_id
                WHERE `time_slot`.time_slot_id=:timeslot_id AND user.security_status="active"';


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':timeslot_id', $timeslot_id, PDO::PARAM_INT);

        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $asid = $result["user_id"];
            return $asid;
        } else {
            return;
        }
    }

    public static function AddtimeslotNotificationGetBookingStaffIds($timeslot_id)
    {
        $sql = 'SELECT `booking_handling_staff`.`user_id` 
                FROM `booking_handling_staff` 
                INNER JOIN `time_slot` ON `booking_handling_staff`.`sports_arena_id`=`time_slot`.`manager_sports_arena_id`
                INNER JOIN user ON user.user_id = booking_handling_staff.user_id
                WHERE `time_slot`.time_slot_id=:timeslot_id AND user.security_status="active"';


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':timeslot_id', $timeslot_id, PDO::PARAM_INT);

        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $bhid = $result["user_id"];
            return $bhid;
        } else {
            return;
        }
    }



    public static function AddFacilityNotificationGetManagerIds($facility_id)
    {
        $sql = 'SELECT manager.user_id
                FROM manager
                INNER JOIN facility ON manager.sports_arena_id= facility.sports_arena_id
                INNER JOIN user ON user.user_id = manager.user_id
                WHERE facility.facility_id=:facility_id AND user.security_status="active"';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':facility_id', $facility_id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $mid = $result["user_id"];
        return $mid;
    }

    public static function AddFacilityNotificationGetAdminStaffIds($facility_id)
    {
        $sql = 'SELECT `administration_staff`.`user_id` 
                FROM `administration_staff` 
                INNER JOIN facility ON `administration_staff`.`sports_arena_id`=facility.`sports_arena_id`
                INNER JOIN user ON user.user_id = administration_staff.user_id
                WHERE `facility`.facility_id=:facility_id AND user.security_status="active"';


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':facility_id', $facility_id, PDO::PARAM_INT);

        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $asid = $result["user_id"];
            return $asid;
        } else {
            return;
        }
    }

    public static function AddFacilityNotificationGetBookingStaffIds($facility_id)
    {
        $sql = 'SELECT `booking_handling_staff`.`user_id` 
                FROM `booking_handling_staff` 
                INNER JOIN `facility` ON `booking_handling_staff`.`sports_arena_id`=`facility`.`sports_arena_id`
                INNER JOIN user ON user.user_id = booking_handling_staff.user_id
                WHERE `facility`.facility_id=:facility_id AND user.security_status="active"';


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':facility_id', $facility_id, PDO::PARAM_INT);

        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            $bhid = $result["user_id"];
            return $bhid;
        } else {
            return;
        }
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
        $spardesc = $fname . " " . $lname . "has booked" . $facname . " on " . $bdate . " from " . $stime . " to " . $etime . ".";

        // **************************************
        // INSERT QUERIES TO USERS


        $sql5 = 'INSERT INTO `notification`(`user_id`, `subject`, `priority`, `description`) VALUES (:uid,:subject,:p_level,:desc)';
        $stmt5 = $db->prepare($sql5);

        // for customer
        $stmt5->execute(['uid' => $customer_id, 'subject' => $custsubj, 'p_level' => $p_level, 'desc' => $custdesc]);

        // for manager
        $stmt5->execute(['uid' => $manager_id, 'subject' => $sparsubj, 'p_level' => $p_level, 'desc' => $spardesc]);


        // for administartion staff
        if ($adminstaff_id) {
            $stmt5->execute(['uid' => $adminstaff_id, 'subject' => $sparsubj, 'p_level' => $p_level, 'desc' => $spardesc]);
        }

        // for booking handling staff
        if ($bookhandlestaff_id) {
            ($stmt5->execute(['uid' => $bookhandlestaff_id, 'subject' => $sparsubj, 'p_level' => $p_level, 'desc' => $spardesc]));
        }
        return true;
    }
    // END OF SEND BOOKING CONFIRMATION NOTIFICATIONS
    // =====================================




    public static function saAdminAddbookingPaymentSuccessNotification($current_user, $first_name, $last_name, $payment_id)
    {
        $db = static::getDB();

        $sql = 'SELECT invoice.invoice_id FROM invoice
        WHERE invoice.payment_id =:payment_id AND security_status="active"';

        $stmt = $db->prepare($sql);

        //Binding the customer id and Converting retrieved data from database into PDOs
        $stmt->bindValue(':payment_id', $payment_id, PDO::PARAM_INT);
        // $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
        $len = count($result);



        // var_dump($result);
        for ($x = 0; $x < $len; $x++) {
            $invoice_id = $result[$x][0];

            // Defining arena staff ids
            $manager_id = self::notificationGetManagerIds($invoice_id);
            $adminstaff_id = self::notificationGetAdminStaffIds($invoice_id);
            $bookhandlestaff_id = self::notificationGetBookingStaffIds($invoice_id);

            $subject = array("sports_arena" => "Facility Booking for a visitor");
            $p_level = "low";

            // Dividing subject to variables
            $sparsubj = $subject["sports_arena"];

            $data_query = "SELECT
             `booking`.`booking_date`,
             `facility`.`facility_name`,
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

            // Select time slot duration
            $stime = $data["start_time"];
            $etime = $data["end_time"];

            // **************************************
            // SPORTS ARENA NOTIFICATION REQUIREMENTS

            // Initialize customer id, first name and last name
            // $customer_id = $current_user->user_id;
            $fname = $current_user->first_name;
            $lname = $current_user->last_name;

            // Select booking date
            $bdate = $data["booking_date"];


            // Initialize descriptions

            $spardesc = "Staff member " . $fname . " " . $lname . " has booked " . $facname . " on " . $bdate . " from " . $stime . " to " . $etime . " for the visitor " .  $first_name . " " . "$last_name" . ".";

            // **************************************
            // INSERT QUERIES TO USERS


            $sql5 = 'INSERT INTO `notification`(`user_id`, `subject`, `priority`, `description`) VALUES (:uid,:subject,:p_level,:desc)';
            $stmt5 = $db->prepare($sql5);

            // for customer


            // for manager
            $stmt5->execute(['uid' => $manager_id, 'subject' => $sparsubj, 'p_level' => $p_level, 'desc' => $spardesc]);


            // for administartion staff
            if ($adminstaff_id) {
                $stmt5->execute(['uid' => $adminstaff_id, 'subject' => $sparsubj, 'p_level' => $p_level, 'desc' => $spardesc]);
            }


            // for booking handling staff
            if ($bookhandlestaff_id) {
                $stmt5->execute(['uid' => $bookhandlestaff_id, 'subject' => $sparsubj, 'p_level' => $p_level, 'desc' => $spardesc]);
            }
        }
        return true;
    }



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
            $link = "http://localhost/customer/refund/" . $booking_id;
        } else {
            $link = "";
        }

        // **************************************
        // INSERT QUERIES TO USERS


        $sql5 = 'INSERT INTO notification(user_id,subject, priority, description,link) VALUES (:uid,:subject,:p_level,:desc,:link)';
        $stmt5 = $db->prepare($sql5);

        // for customer
        $stmt5->execute([':uid' => $customer_id, ':subject' => $custsubj, ':p_level' => $p_level, ':desc' => $custdesc, ':link' => $link]);

        // for manager
        $stmt5->execute(['uid' => $manager_id, 'subject' => $sparsubj, 'p_level' => $p_level, 'desc' => $spardesc, ':link' => ""]);


        // for administartion staff
        $stmt5->execute(['uid' => $adminstaff_id, 'subject' => $sparsubj, 'p_level' => $p_level, 'desc' => $spardesc, ':link' => ""]);


        // for booking handling staff
        return ($stmt5->execute(['uid' => $bookhandlestaff_id, 'subject' => $sparsubj, 'p_level' => $p_level, 'desc' => $spardesc, ':link' => ""]));
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
            $db->beginTransaction();
            
            $customer = self::cancelNotificationGetCustomerIds($booking_id);
            $customer_id = $customer['user_id'];
            
            $manager_id = self::cancelNotificationGetManagerIds($booking_id);
            $adminstaff_id = self::cancelNotificationGetAdminStaffIds($booking_id);
            $bookhandlestaff_id = self::cancelNotificationGetBookingStaffIds($booking_id);

            $custsubj = "Emergency booking cancellation";
            $sparsubj = "Booking cancellation by sports arena";

          
            
            $data_query = "SELECT
             `booking`.`booking_date`,
             `facility`.`facility_name`,
             `sports_arena_profile`.`sa_name`,
             `time_slot`.`start_time`,
             `time_slot`.`end_time`,
             `booking_cancellation`.`reason`,
             `booking`.`payment_method`,
             `booking`.`payment_status`,
             user.account_status,user.primary_contact
        FROM `booking` 
        INNER JOIN user ON user.user_id=booking.customer_user_id
        INNER JOIN `facility` ON `facility`.facility_id = booking.facility_id
        INNER JOIN `sports_arena_profile` ON `sports_arena_profile`.sports_arena_id = `booking`.sports_arena_id
        INNER JOIN `booking_timeslot` ON `booking_timeslot`.`booking_id`= `booking`.`booking_id`
        INNER JOIN `time_slot` ON `time_slot`.`time_slot_id`=`booking_timeslot`.`timeslot_id`
        INNER JOIN `booking_cancellation` ON `booking_cancellation`.`booking_id`= `booking`.`booking_id`
        WHERE `booking`.`booking_id` = :booking_id AND booking.customer_user_id=:customer_id";

            $data_stmt = $db->prepare($data_query);
            $data_stmt->bindValue(':booking_id', $booking_id, PDO::PARAM_INT);
            $data_stmt->bindValue(':customer_id', $customer_id, PDO::PARAM_INT);
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

            $visitor=$data["account_status"];
            $primary_contact=$data["primary_contact"];

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

                $link = "";
            } else {
                // Initialize descriptions
                $custdesc = " " . $saname . " had cancel your booking " . $booking_id . "made for" . $facname . " on " . $bdate . "scheduled from " . $stime . " to " . $etime . " . Reason for cancellation: " . $reason . " . Please apply for refund form to collect your refund. Note that we'll be making a bank transfer";

                $link = "http://localhost/customer/refund/" . $booking_id;
            }

            $spardesc = "" . $fname . " " . $lname . " has cancelled the booking " . $booking_id . " made for " . $facname . " on " . $bdate . " scheduled from " . $stime . " to " . $etime . " . Reason for cancellation: " . $reason . " .";
            $link = "";


            $sql = 'INSERT INTO `notification`(`user_id`, `subject`, `priority`, `description`,`link`) VALUES (:uid,:subject,:p_level,:desc, :link)';
            $stmt = $db->prepare($sql);

            // print_r(['uid' => $customer_id, 'subject' => $custsubj, 'p_level' => $p_level, 'desc' => $custdesc]);
            // for customer
            $stmt->execute(['uid' => $customer_id, 'subject' => $custsubj, 'p_level' => $p_level, 'desc' => $custdesc, 'link' => $link]);

            // for manager
            $stmt->execute(['uid' => $manager_id, 'subject' => $sparsubj, 'p_level' => $p_level, 'desc' => $spardesc, 'link' => $link]);

            // for administartion staff
            $stmt->execute(['uid' => $adminstaff_id, 'subject' => $sparsubj, 'p_level' => $p_level, 'desc' => $spardesc, 'link' => $link]);

            // for booking handling staff
            $stmt->execute(['uid' => $bookhandlestaff_id, 'subject' => $sparsubj, 'p_level' => $p_level, 'desc' => $spardesc, 'link' => $link]);
            $db->commit();
        
            if ($visitor=="visitor") {

            //our mobile number
                $user = "94765282976";
                //our account password
                $password = 4772;
                //Random OTP code
                $otp = mt_rand(100000, 999999);

                // stores the otp code and mobile number into session
                $_SESSION['otp'] = $otp;
                $_SESSION['mobile_number'] = $primary_contact;

                //Message to be sent
                $text = urlencode("" . $saname ." had cancel your booking " . $booking_id . " made for " . $facname . " on " . $bdate . " scheduled from " . $stime . " to " . $etime . " . Reason for cancellation: " . $reason . " . Please collect refund form the sports arena");
                // Replacing the initial 0 with 94
                $to = substr_replace($primary_contact, '94', 0, 0);
                //Base URL
                $baseurl = "http://www.textit.biz/sendmsg";
                // regex to create the url
                $url = "$baseurl/?id=$user&pw=$password&to=$to&text=$text";

                $ret = file($url);
                $res = explode(":", $ret[0]);

                if (trim($res[0]) == "OK") {
                    echo "Message Sent - ID : " . $res[1];
                } else {
                    echo "Sent Failed - Error : " . $res[1];
                }
            }
            // Make the changes to the database permanent
           
            return true;
        } catch (PDOException $e) {
            $db->rollback();
            throw $e;
        }
    }

    public static function saAdminAddfacilitySuccessNotification($current_user, $facility_name, $facility_id)
    {
        try {
            $db = static::getDB();
            $db->beginTransaction();
            
            $manager_id = self::AddfacilityNotificationGetManagerIds($facility_id);
            $adminstaff_id = self::AddfacilityNotificationGetAdminStaffIds($facility_id);
            $bookhandlestaff_id = self::AddfacilityNotificationGetBookingStaffIds($facility_id);

        


            // Select facility name
      
            $fname = $current_user->first_name;
            $lname = $current_user->last_name;
            $facname = $facility_name;

            // Select reason for cancellation
            $p_level = "high";

            $sparsubj = "Facility added by sports arena";
            $spardesc = "Staff member " . $fname . " " . $lname . " has added a facility named " . $facname . " to the sports arena.";

            $sql = 'INSERT INTO `notification`(`user_id`, `subject`, `priority`, `description`) VALUES (:uid,:subject,:p_level,:desc)';
            $stmt = $db->prepare($sql);

            // for manager
            if ($manager_id) {
                $stmt->execute(['uid' => $manager_id, 'subject' => $sparsubj, 'p_level' => $p_level, 'desc' => $spardesc]);
            }

            // for administartion staff
            if ($adminstaff_id) {
                $stmt->execute(['uid' => $adminstaff_id, 'subject' => $sparsubj, 'p_level' => $p_level, 'desc' => $spardesc]);
            }

            // for booking handling staff
            if ($bookhandlestaff_id) {
                $stmt->execute(['uid' => $bookhandlestaff_id, 'subject' => $sparsubj, 'p_level' => $p_level, 'desc' => $spardesc]);
            }
            $db->commit();
            return true;
        } catch (PDOException $e) {
            $db->rollback();
            throw $e;
        }
    }


    public static function saAdminUpdatefacilitySuccessNotification($current_user, $old_facility_name, $facility_name, $facility_id)
    {
        $db = static::getDB();

        $manager_id = self::AddfacilityNotificationGetManagerIds($facility_id);
        $adminstaff_id = self::AddfacilityNotificationGetAdminStaffIds($facility_id);
        $bookhandlestaff_id = self::AddfacilityNotificationGetBookingStaffIds($facility_id);

        
        $fname = $current_user->first_name;
        $lname = $current_user->last_name;
      

        // Select reason for cancellation
        $p_level = "high";

        $sparsubj = "Facility updated by sports arena";
        $spardesc = "Staff member " . $fname . " " . $lname . " has updated facility named " . $old_facility_name . " to " . $facility_name . ".";

        $sql = 'INSERT INTO `notification`(`user_id`, `subject`, `priority`, `description`) VALUES (:uid,:subject,:p_level,:desc)';
        $stmt = $db->prepare($sql);

        // for manager
        $stmt->execute(['uid' => $manager_id, 'subject' => $sparsubj, 'p_level' => $p_level, 'desc' => $spardesc]);

        // for administartion staff
        $stmt->execute(['uid' => $adminstaff_id, 'subject' => $sparsubj, 'p_level' => $p_level, 'desc' => $spardesc]);

        // for booking handling staff
        return ($stmt->execute(['uid' => $bookhandlestaff_id, 'subject' => $sparsubj, 'p_level' => $p_level, 'desc' => $spardesc]));
    }


    public static function saAdminAddtimeslotSuccessNotification($current_user, $time_slot_id)
    {
        $db = static::getDB();

        $manager_id = self::AddtimeslotNotificationGetManagerIds($time_slot_id);
        $adminstaff_id = self::AddtimeslotNotificationGetAdminStaffIds($time_slot_id);
        $bookhandlestaff_id = self::AddtimeslotNotificationGetBookingStaffIds($time_slot_id);

        $sql = 'SELECT time_slot.start_time, time_slot.end_time, time_slot.price, facility.facility_name 
        FROM time_slot
        INNER JOIN facility ON time_slot.facility_id = facility.facility_id
        WHERE time_slot.time_slot_id = :time_slot_id AND time_slot.security_status="active"';

        $stmt = $db->prepare($sql);

        //Binding the customer id and Converting retrieved data from database into PDOs
        $stmt->bindValue(':time_slot_id', $time_slot_id, PDO::PARAM_INT);
        // $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        // CUSTOMER NOTIFICATION REQUIREMENTS
        $data = $stmt->fetch(PDO::FETCH_ASSOC);

        // Select facility name
        $facname = $data["facility_name"];

        // Select time slot duration
        $stime = $data["start_time"];
        $etime = $data["end_time"];

        $price = $data["price"];
        $fname = $current_user->first_name;
        $lname = $current_user->last_name;


        // Select reason for cancellation
        $p_level = "high";

        $sparsubj = "Timeslot added by sports arena";
        $spardesc = "Staff member " . $fname . " " . $lname . " has added a timeslot from " . $stime . " to " . $etime . " on facility " . $facname . " with price of LKR " . $price . ".";

        $sql = 'INSERT INTO `notification`(`user_id`, `subject`, `priority`, `description`) VALUES (:uid,:subject,:p_level,:desc)';
        $stmt = $db->prepare($sql);

        // for manager
        $stmt->execute(['uid' => $manager_id, 'subject' => $sparsubj, 'p_level' => $p_level, 'desc' => $spardesc]);

        // for administartion staff
        $stmt->execute(['uid' => $adminstaff_id, 'subject' => $sparsubj, 'p_level' => $p_level, 'desc' => $spardesc]);

        // for booking handling staff
        return ($stmt->execute(['uid' => $bookhandlestaff_id, 'subject' => $sparsubj, 'p_level' => $p_level, 'desc' => $spardesc]));
    }


    //Start of administration staff booking cancellation notification
    public static function arenaDeleteTimeslotNotification($current_user, $timeslot_id)
    {
        try {
            $db = static::getDB();
            $manager_id = self::AddtimeslotNotificationGetManagerIds($timeslot_id);
            $adminstaff_id = self::AddtimeslotNotificationGetAdminStaffIds($timeslot_id);
            $bookhandlestaff_id = self::AddtimeslotNotificationGetBookingStaffIds($timeslot_id);

            $sparsubj = "Removing timeslot and Cancel bookings";

            $db->beginTransaction();
            $data_query = "SELECT
             `facility`.`facility_name`,
             `time_slot`.`start_time`,
             `time_slot`.`end_time`
        FROM `time_slot` 
        INNER JOIN `facility` ON `time_slot`.facility_id = facility.facility_id
        WHERE `time_slot`.`time_slot_id` = :timeslot_id ";

            $data_stmt = $db->prepare($data_query);
            $data_stmt->bindValue(':timeslot_id', $timeslot_id, PDO::PARAM_INT);
            $data_stmt->execute();

            // CUSTOMER NOTIFICATION REQUIREMENTS
            $data = $data_stmt->fetch(PDO::FETCH_ASSOC);

            // Select facility name
            $facname = $data["facility_name"];

            // Select time slot duration
            $stime = $data["start_time"];
            $etime = $data["end_time"];


            // Select reason for cancellation
            $p_level = "high";

            // **************************************
            // SPORTS ARENA NOTIFICATION REQUIREMENTS

            // Initialize first name and last name of the staff member
            $fname = $current_user->first_name;
            $lname = $current_user->last_name;



            $spardesc = "Staff member " . $fname . " " . $lname . " has removed the timeslot starting from " . $stime . " to " . $etime . " on the facility " . $facname . ". All the future bookings made on this timeslot got cancelled and this timeslot is no longer visible to the customers.";

            $sql = 'INSERT INTO `notification`(`user_id`, `subject`, `priority`, `description`) VALUES (:uid,:subject,:p_level,:desc)';
            $stmt = $db->prepare($sql);


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






    //Start of administration staff booking cancellation notification
    public static function customerBookingCancellationDeleteTimeslotNotification($timeslot_id)
    {
        try {
            $db = static::getDB();
            $customer = self::timeslotcancelNotificationGetCustomerIds($timeslot_id);
            
            var_dump($customer);
            $customer_id = $customer['user_id'];
            
            $custsubj = "Booking cancellation due to timeslot unavailability";

            $db->beginTransaction();
            $data_query = "SELECT
             `booking`.`booking_date`,
             `facility`.`facility_name`,
             `sports_arena_profile`.`sa_name`,
             `time_slot`.`start_time`,
             `time_slot`.`end_time`,
             booking.booking_id,
             `booking`.`payment_method`,
             `booking`.`payment_status`, 
              user.primary_contact,
              user.account_status
        FROM `booking` 
        INNER JOIN user ON user.user_id =booking.customer_user_id
        INNER JOIN `facility` ON `facility`.facility_id = booking.facility_id
        INNER JOIN `sports_arena_profile` ON `sports_arena_profile`.sports_arena_id = `booking`.sports_arena_id
        INNER JOIN `booking_timeslot` ON `booking_timeslot`.`booking_id`= `booking`.`booking_id`
        INNER JOIN `time_slot` ON `time_slot`.`time_slot_id`=`booking_timeslot`.`timeslot_id`
        WHERE `booking_timeslot`.`timeslot_id` = :timeslot_id ";

            $data_stmt = $db->prepare($data_query);
            $data_stmt->bindValue(':timeslot_id', $timeslot_id, PDO::PARAM_INT);
            $data_stmt->execute();

            // CUSTOMER NOTIFICATION REQUIREMENTS
            $data = $data_stmt->fetch(PDO::FETCH_ASSOC);

            // Select facility name
            $facname = $data["facility_name"];
            $booking_id = $data["booking_id"];
            // Select sports arena name
            $saname = $data["sa_name"];

            // Select time slot duration
            $stime = $data["start_time"];
            $etime = $data["end_time"];

            $payment_method = $data["payment_method"];
            $payment_status = $data["payment_status"];
            // Select reason for cancellation
            $p_level = "high";

            $visitor=$data['account_status'];
            $primary_contact=$data['primary_contact'];
            $reason="This timeslot is no longer available in our sports arena.";
            // Select booking date
            $bdate = $data["booking_date"];

            if ($payment_method == 'cash' && $payment_status == 'unpaid') {
                // Initialize descriptions
                $custdesc = " " . $saname . " had cancelled your booking " . $booking_id . " made for " . $facname . " on " . $bdate . " scheduled from " . $stime . " to " . $etime . " as this timeslot is no longer available.";
            } else {
                // Initialize descriptions
                $custdesc = " " . $saname . " had cancelled your booking " . $booking_id . " made for " . $facname . " on " . $bdate . " scheduled from " . $stime . " to " . $etime . " as this timeslot is no longer available. Please apply for refund form to collect your refund. Note that we'll be making a bank transfer.";
            }


            $sql = 'INSERT INTO `notification`(`user_id`, `subject`, `priority`, `description`) VALUES (:uid,:subject,:p_level,:desc)';
            $stmt = $db->prepare($sql);


            $stmt->execute(['uid' => $customer_id, 'subject' => $custsubj, 'p_level' => $p_level, 'desc' => $custdesc]);

            // Make the changes to the database permanent
            $db->commit();

            if ($visitor=="visitor") {

                //our mobile number
                $user = "94765282976";
                //our account password
                $password = 4772;
                //Random OTP code
                $otp = mt_rand(100000, 999999);
    
                // stores the otp code and mobile number into session
                $_SESSION['otp'] = $otp;
                $_SESSION['mobile_number'] = $primary_contact;
    
                //Message to be sent
                $text = urlencode("" . $saname ." had cancel your booking " . $booking_id . " made for " . $facname . " on " . $bdate . " scheduled from " . $stime . " to " . $etime . " . Reason for cancellation: " . $reason . " . Please collect refund form the sports arena");
                // Replacing the initial 0 with 94
                $to = substr_replace($primary_contact, '94', 0, 0);
                //Base URL
                $baseurl = "http://www.textit.biz/sendmsg";
                // regex to create the url
                $url = "$baseurl/?id=$user&pw=$password&to=$to&text=$text";
    
                $ret = file($url);
                $res = explode(":", $ret[0]);
    
                if (trim($res[0]) == "OK") {
                    echo "Message Sent - ID : " . $res[1];
                } else {
                    echo "Sent Failed - Error : " . $res[1];
                }
            }
        } catch (PDOException $e) {
            $db->rollback();
            throw $e;
        }
    }

    //Start of administration staff booking cancellation notification
    public static function arenaDeleteFacilityNotification($current_user, $facility_id)
    {
        try {
            $db = static::getDB();
            $db->beginTransaction();
            $manager_id = self::AddfacilityNotificationGetManagerIds($facility_id);
            $adminstaff_id = self::AddfacilityNotificationGetAdminStaffIds($facility_id);
            $bookhandlestaff_id = self::AddfacilityNotificationGetBookingStaffIds($facility_id);
 
            $sparsubj = "Removing facility and Cancel bookings";
 
             
            $data_query = "SELECT
              `facility`.`facility_name`
                FROM `facility` 
                WHERE `facility`.`facility_id` = :facility_id ";
 
            $data_stmt = $db->prepare($data_query);
            $data_stmt->bindValue(':facility_id', $facility_id, PDO::PARAM_INT);
            $data_stmt->execute();
 
            // CUSTOMER NOTIFICATION REQUIREMENTS
            $data = $data_stmt->fetch(PDO::FETCH_ASSOC);
 
            // Select facility name
            $facname = $data["facility_name"];
 
            // Select reason for cancellation
            $p_level = "high";
 
 
            // Initialize first name and last name of the staff member
            $fname = $current_user->first_name;
            $lname = $current_user->last_name;
 
 
 
            $spardesc = "Staff member " . $fname . " " . $lname . " has removed the facility " . $facname . " from the sports arena.  All the future bookings made on this facility got cancelled. This facility and its timeslots are no longer visible to the customers.";
 
            $sql = 'INSERT INTO `notification`(`user_id`, `subject`, `priority`, `description`) VALUES (:uid,:subject,:p_level,:desc)';
            $stmt = $db->prepare($sql);
 
 
            // for manager
            $stmt->execute(['uid' => $manager_id, 'subject' => $sparsubj, 'p_level' => $p_level, 'desc' => $spardesc]);
 
            // for administartion staff
            $stmt->execute(['uid' => $adminstaff_id, 'subject' => $sparsubj, 'p_level' => $p_level, 'desc' => $spardesc]);
 
            // for booking handling staff
            $stmt->execute(['uid' => $bookhandlestaff_id, 'subject' => $sparsubj, 'p_level' => $p_level, 'desc' => $spardesc]);
 
            // Make the changes to the database permanent
            $db->commit();
            return true;
        } catch (PDOException $e) {
            $db->rollback();
            throw $e;
        }
    }

    //Start of administration staff booking cancellation notification
    public static function customerBookingCancellationDeleteFacilityNotification($timeslot_id)
    {
        try {
            $db = static::getDB();

            $customer = self::timeslotcancelNotificationGetCustomerIds($timeslot_id);
            var_dump($customer);
            $customer_id = $customer['user_id'];
 
            $custsubj = "Booking cancellation due to facility unavailability";
 
            $db->beginTransaction();
            $data_query = "SELECT
              `booking`.`booking_date`,
              `facility`.`facility_name`,
              `sports_arena_profile`.`sa_name`,
              `time_slot`.`start_time`,
              `time_slot`.`end_time`,
              booking.booking_id,
              `booking`.`payment_method`,
              `booking`.`payment_status`, 
              user.primary_contact,
              user.account_status
         FROM `booking` 
         INNER JOIN user ON user.user_id =booking.customer_user_id
         INNER JOIN `facility` ON `facility`.facility_id = booking.facility_id
         INNER JOIN `sports_arena_profile` ON `sports_arena_profile`.sports_arena_id = `booking`.sports_arena_id
         INNER JOIN `booking_timeslot` ON `booking_timeslot`.`booking_id`= `booking`.`booking_id`
         INNER JOIN `time_slot` ON `time_slot`.`time_slot_id`=`booking_timeslot`.`timeslot_id`
         WHERE `booking_timeslot`.`timeslot_id` = :timeslot_id ";
 
            $data_stmt = $db->prepare($data_query);
            $data_stmt->bindValue(':timeslot_id', $timeslot_id, PDO::PARAM_INT);
            $data_stmt->execute();
 
            // CUSTOMER NOTIFICATION REQUIREMENTS
            $data = $data_stmt->fetch(PDO::FETCH_ASSOC);
 
            // Select facility name
            $facname = $data["facility_name"];
            $booking_id = $data["booking_id"];
            // Select sports arena name
            $saname = $data["sa_name"];
 
            // Select time slot duration
            $stime = $data["start_time"];
            $etime = $data["end_time"];
 
            $payment_method = $data["payment_method"];
            $payment_status = $data["payment_status"];
            // Select reason for cancellation
            $p_level = "high";

            $visitor=$data['account_status'];
            $primary_contact=$data['primary_contact'];
 
 
            // Select booking date
            $bdate = $data["booking_date"];
 
            if ($payment_method == 'cash' && $payment_status == 'unpaid') {
                // Initialize descriptions
                $custdesc = " " . $saname . " had cancelled your booking " . $booking_id . " made for " . $facname . " on " . $bdate . " scheduled from " . $stime . " to " . $etime . " as this facility is no longer available.";
            } else {
                // Initialize descriptions
                $custdesc = " " . $saname . " had cancelled your booking " . $booking_id . " made for " . $facname . " on " . $bdate . " scheduled from " . $stime . " to " . $etime . " as this facility is no longer available. Please apply for refund form to collect your refund. Note that we'll be making a bank transfer.";
            }
 
 
            $sql = 'INSERT INTO `notification`(`user_id`, `subject`, `priority`, `description`) VALUES (:uid,:subject,:p_level,:desc)';
            $stmt = $db->prepare($sql);
 
 
            $stmt->execute(['uid' => $customer_id, 'subject' => $custsubj, 'p_level' => $p_level, 'desc' => $custdesc]);
 
            // Make the changes to the database permanent
            $db->commit();
            if ($visitor=="visitor") {

                //our mobile number
                $user = "94765282976";
                //our account password
                $password = 4772;
                //Random OTP code
                $otp = mt_rand(100000, 999999);
    
                // stores the otp code and mobile number into session
                $_SESSION['otp'] = $otp;
                $_SESSION['mobile_number'] = $primary_contact;
                $reason="This facility is no longer available in our sports arena";
                //Message to be sent
                $text = urlencode("" . $saname ." had cancel your booking " . $booking_id . " made for " . $facname . " on " . $bdate . " scheduled from " . $stime . " to " . $etime . " . Reason for cancellation: " . $reason . " . Please collect refund form the sports arena");
                // Replacing the initial 0 with 94
                $to = substr_replace($primary_contact, '94', 0, 0);
                //Base URL
                $baseurl = "http://www.textit.biz/sendmsg";
                // regex to create the url
                $url = "$baseurl/?id=$user&pw=$password&to=$to&text=$text";
    
                $ret = file($url);
                $res = explode(":", $ret[0]);
    
                if (trim($res[0]) == "OK") {
                    echo "Message Sent - ID : " . $res[1];
                } else {
                    echo "Sent Failed - Error : " . $res[1];
                }
            }
        } catch (PDOException $e) {
            $db->rollback();
            throw $e;
        }
    }

    public static function managerAddStaffSuccessManagerNotification($manager_id, $added_user_id)
    {
        try {
            $db = static::getDB();
            $db->beginTransaction();
            $sql1 = 'SELECT user.first_name, user.last_name, user.type FROM user WHERE user_id=:user_id';
            $stmt1 = $db->prepare($sql1);
            $stmt1->bindValue(':user_id', $added_user_id, PDO::PARAM_INT);
            $stmt1->execute();
            $result1 = $stmt1->fetch(PDO::FETCH_ASSOC);
            $first_name = $result1['first_name'];
            $last_name = $result1['last_name'];
            $user_type = $result1['type'];
            
            $mana_notification_subj = "Staff Member Added Successfully";
            $mana_notification_desc = " " . $first_name . " ". $last_name . " is successfully added to your sports arena as a " . $user_type . " member.";
            $user_notificatin_subj = "Your Account Get Activated";
            $user_notificatin_desc = "Your account get activated in Sportizza as a ". $user_type ." member.";
           
            $sql = 'INSERT INTO notification(user_id, subject, priority, description) VALUES (:uid,:subject,:p_level,:desc)';
            $stmt = $db->prepare($sql);
            $stmt->execute(['uid' => $manager_id, 'subject' => $mana_notification_subj, 'p_level' => "high", 'desc' => $mana_notification_desc]);
            $stmt->execute(['uid' => $added_user_id, 'subject' => $user_notificatin_subj, 'p_level' => "high", 'desc' => $user_notificatin_desc]);
            $db->commit();
        } catch (PDOException $e) {
            $db->rollback();
            throw $e;
        }
    }

    public static function managerAddStaffMobileSuccessNotification($first_name, $user_name, $user_password, $contact)
    {
        //our mobile number
        $user = "94765282976";
        //our account password
        $password = 4772;
            
        $_SESSION['mobile_number'] = $contact;
        //Message to be sent
        $text = urlencode("Hi, " . $first_name ." you have successfully added as a staff member of Sports Arena. <br> Please use Following data for Sign in.<br> Username:". $user_name ."<br>Password:" . $user_password ." ");
        // Replacing the initial 0 with 94
        $to = substr_replace($contact, '94', 0, 0);
        //Base URL
        $baseurl = "http://www.textit.biz/sendmsg";
        // regex to create the url
        $url = "$baseurl/?id=$user&pw=$password&to=$to&text=$text";
    
        $ret = file($url);
        $res = explode(":", $ret[0]);
    
        if (trim($res[0]) == "OK") {
            echo "Message Sent - ID : " . $res[1];
        } else {
            echo "Sent Failed - Error : " . $res[1];
        }
    }
}
