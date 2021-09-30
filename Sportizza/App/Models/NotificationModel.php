<?php

namespace App\Models;
use Core\Model;
use PDO;
use PDOException;
use App\Auth;

class NotificationModel extends \Core\Model
{
    /**
     * Error messages
     *
     * @var array
     */
    public $errors = [];

    /**
     * Class constructor
     *
     * @param array $data  Initial property values (optional)
     *
     * @return void
     */
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }

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


    public static function cancelNotificationGetManagerIds($booking_id)
    {
        
        $sql = 'SELECT `manager`.`user_id` 
                FROM `manager` 
                INNER JOIN `booking` ON `manager`.`sports_arena_id`= `booking`.`sports_arena_id`
                WHERE `booking`.`booking_id`=:booking_id';

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
    public static function addNotificationBookingSuccess($current_user,$invoice_id)
    {
         // Defining arena staff ids
        $manager_id = self::notificationGetManagerIds($invoice_id);
        $adminstaff_id= self::notificationGetAdminStaffIds($invoice_id);
        $bookhandlestaff_id= self::notificationGetBookingStaffIds($invoice_id);
        
        $subject = array("customer"=>"Booking Confirmation Message","sports_arena"=>"Facility Booking Message");
        $p_level="low";

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
        $custdesc = "You have successfully made a booking with Sportizza to ".$facname." of ".$saname." on ".$bdate." from ".$stime." to ".$etime.". To find out the location, click ".$maplink;
        $spardesc = $fname." ".$lname." has booked ".$facname." on ".$bdate." from ".$stime." to ".$etime.".";
        
        // **************************************
        // INSERT QUERIES TO USERS
        
   
        $sql5 = 'INSERT INTO `notification`(`user_id`, `subject`, `priority`, `description`) VALUES (:uid,:subject,:p_level,:desc)';
        $stmt5 = $db->prepare($sql5);
        
        // for customer
        $stmt5->execute(['uid'=> $customer_id, 'subject'=> $custsubj, 'p_level'=> $p_level, 'desc'=> $custdesc]);

        // for manager
        $stmt5->execute(['uid'=> $manager_id, 'subject'=> $sparsubj, 'p_level'=> $p_level, 'desc'=>$spardesc]);


        // for administartion staff
        $stmt5->execute(['uid'=> $adminstaff_id, 'subject'=> $sparsubj, 'p_level'=> $p_level, 'desc'=>$spardesc]);


        // for booking handling staff
        return ($stmt5->execute(['uid'=> $bookhandlestaff_id, 'subject'=> $sparsubj, 'p_level'=> $p_level, 'desc'=>$spardesc]));

    }
    // END OF SEND BOOKING CONFIRMATION NOTIFICATIONS
    // =====================================

    // =====================================
    // SEND CANCEL BOOKING NOTIFICATIONS
    public static function cancelNotificationBookingSuccess($current_user,$booking_id)
    {
         // Defining arena staff ids
        $manager_id = self::cancelNotificationGetManagerIds($booking_id);
        $adminstaff_id= self::cancelNotificationGetAdminStaffIds($booking_id);
        $bookhandlestaff_id= self::cancelNotificationGetBookingStaffIds($booking_id);
        
        $subject = array("customer"=>"Booking Cancellation Message","sports_arena"=>"Facility Booking Cancellation Message");
        $p_level="high";

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
        $custdesc = "You have successfully cancelled your booking with Sportizza to ".$facname." of ".$saname." on ".$bdate." scheduled from ".$stime." to ".$etime.".";
        $spardesc = $fname." ".$lname." has cancelled his booking to ".$facname." on ".$bdate." scheduled from ".$stime." to ".$etime.".";
        
        if($paymethod=="card"){
            $custdesc .= "Refund details will be sent to you later.";
        }

        // **************************************
        // INSERT QUERIES TO USERS
        
   
        $sql5 = 'INSERT INTO `notification`(`user_id`, `subject`, `priority`, `description`) VALUES (:uid,:subject,:p_level,:desc)';
        $stmt5 = $db->prepare($sql5);
        
        // for customer
        $stmt5->execute(['uid'=> $customer_id, 'subject'=> $custsubj, 'p_level'=> $p_level, 'desc'=> $custdesc]);

        // for manager
        $stmt5->execute(['uid'=> $manager_id, 'subject'=> $sparsubj, 'p_level'=> $p_level, 'desc'=>$spardesc]);


        // for administartion staff
        $stmt5->execute(['uid'=> $adminstaff_id, 'subject'=> $sparsubj, 'p_level'=> $p_level, 'desc'=>$spardesc]);


        // for booking handling staff
        return ($stmt5->execute(['uid'=> $bookhandlestaff_id, 'subject'=> $sparsubj, 'p_level'=> $p_level, 'desc'=>$spardesc]));

    }
    // END OF CANCEL BOOKING SEND NOTIFICATIONS
    // =====================================
}









?>