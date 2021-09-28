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


    // =====================================
    // SEND NOTIFICATIONS
    public static function addNotificationBookingSuccess($current_user,$mid,$asid,$bhid,$subject,$p_level,$invoice_id)
    {
        $db = static::getDB();

        // Dividing subject to variables
        $custsubj = $subject["customer"];
        $sparsubj = $subject["sports_arena"];

        // **********************************
        // CUSTOMER NOTIFICATION REQUIREMENTS
        
        // Select facility name
        $sql1 = 'SELECT `facility_name` 
                FROM `facility` 
                INNER JOIN `booking` ON `facility`.`facility_id`=`booking`.`facility_id` 
                WHERE `booking`.`invoice_id`=:invoice_id';

        $stmt1 = $db->prepare($sql1);
        $stmt1->bindValue(':invoice_id', $invoice_id, PDO::PARAM_INT);
        $stmt1->execute();

        $result1 = $stmt1->fetch(PDO::FETCH_ASSOC);
        $facname = $result1["facility_name"];

        // Select sports arena name and map link
        $sql2 = 'SELECT `sports_arena_profile`.`sa_name`,`sports_arena_profile`.`google_map_link`
                FROM `sports_arena_profile` 
                INNER JOIN `booking` ON `sports_arena_profile`.`sports_arena_id`=`booking`.`sports_arena_id` 
                WHERE `booking`.`invoice_id`=:invoice_id';

        $stmt2 = $db->prepare($sql2);
        $stmt2->bindValue(':invoice_id', $invoice_id, PDO::PARAM_INT);
        $stmt2->execute();

        $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
        $saname = $result2["sa_name"];
        $maplink = $result2["google_map_link"];

        // Select time slot duration
        $sql3 = 'SELECT `time_slot`.`start_time`,`time_slot`.`end_time`
                FROM `time_slot` 
                INNER JOIN `booking_timeslot` ON `time_slot`.`time_slot_id`=`booking_timeslot`.`timeslot_id`
                INNER JOIN `booking` ON `booking_timeslot`.`booking_id`=`booking`.`booking_id` 
                WHERE `booking`.`invoice_id`=:invoice_id';

        $stmt3 = $db->prepare($sql3);
        $stmt3->bindValue(':invoice_id', $invoice_id, PDO::PARAM_INT);
        $stmt3->execute();

        $result3 = $stmt3->fetch(PDO::FETCH_ASSOC);
        $stime = $result3["start_time"];
        $etime = $result3["end_time"];

        // **************************************
        // SPORTS ARENA NOTIFICATION REQUIREMENTS

        // Initialize customer id, first name and last name
        $cid = $current_user->user_id;
        $fname = $current_user->first_name;
        $lname = $current_user->last_name;

        // Select booking date
        $sql4 = 'SELECT `booking`.`booking_date`
        FROM `booking` 
        WHERE `booking`.`invoice_id`=:invoice_id';

        $stmt4 = $db->prepare($sql4);
        $stmt4->bindValue(':invoice_id', $invoice_id, PDO::PARAM_INT);
        $stmt4->execute();

        $result4 = $stmt4->fetch(PDO::FETCH_ASSOC);
        $bdate = $result4["booking_date"];

        
        // Initialize descriptions
        $custdesc = "You have successfully made a booking with Sportizza to ".$facname." of ".$saname." from ".$stime." to ".$etime.". To find out the location, click ".$maplink;
        $spardesc = $fname." ".$lname." has booked ".$facname." on ".$bdate." from ".$stime." to ".$etime.".";
        
        // **************************************
        // INSERT QUERIES TO USERS
        
        // for customer
        $sql5 = 'INSERT INTO `notification`(`user_id`, `subject`, `priority`, `description`) 
                VALUES (:cid,:custsubj,:p_level,:custdesc)';

        $stmt5 = $db->prepare($sql5);
        $stmt5->bindValue(':cid', $cid, PDO::PARAM_INT);
        $stmt5->bindValue(':custsubj', $custsubj, PDO::PARAM_STR);
        $stmt5->bindValue(':p_level', $p_level, PDO::PARAM_STR);
        $stmt5->bindValue(':custdesc', $custdesc, PDO::PARAM_STR);
        $stmt5->execute();

        // for manager
        $sql6 = 'INSERT INTO `notification`(`user_id`, `subject`, `priority`, `description`) 
                VALUES (:mid,:sparsubj,:p_level,:spardesc)';

        $stmt6 = $db->prepare($sql6);
        $stmt6->bindValue(':mid', $mid, PDO::PARAM_INT);
        $stmt6->bindValue(':sparsubj', $sparsubj, PDO::PARAM_STR);
        $stmt6->bindValue(':p_level', $p_level, PDO::PARAM_STR);
        $stmt6->bindValue(':spardesc', $spardesc, PDO::PARAM_STR);
        $stmt6->execute();

        // for administartion staff
        $sql7 = 'INSERT INTO `notification`(`user_id`, `subject`, `priority`, `description`) 
                VALUES (:asid,:sparsubj,:p_level,:spardesc)';

        $stmt7 = $db->prepare($sql7);
        $stmt7->bindValue(':asid', $asid, PDO::PARAM_INT);
        $stmt7->bindValue(':sparsubj', $sparsubj, PDO::PARAM_STR);
        $stmt7->bindValue(':p_level', $p_level, PDO::PARAM_STR);
        $stmt7->bindValue(':spardesc', $spardesc, PDO::PARAM_STR);
        $stmt7->execute();

        // for booking handling staff
        $sql8 = 'INSERT INTO `notification`(`user_id`, `subject`, `priority`, `description`) 
                VALUES (:bhid,:sparsubj,:p_level,:spardesc)';

        $stmt8 = $db->prepare($sql8);
        $stmt8->bindValue(':bhid', $bhid, PDO::PARAM_INT);
        $stmt8->bindValue(':sparsubj', $sparsubj, PDO::PARAM_STR);
        $stmt8->bindValue(':p_level', $p_level, PDO::PARAM_STR);
        $stmt8->bindValue(':spardesc', $spardesc, PDO::PARAM_STR);

        return ($stmt8->execute());
    }
    // END OF SEND NOTIFICATIONS
    // =====================================
}









?>