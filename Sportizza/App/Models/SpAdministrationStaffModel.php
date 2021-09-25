<?php

namespace App\Models;
use Core\Model;
use PDO;
use PDOException;
use App\Auth;

class SpAdministrationStaffModel extends \Core\Model
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

    public static function saAdminViewBookings($id){
        
        $sql = 'SELECT booking.booking_id,booking.price_per_booking,booking.booked_date,
                booking.payment_method,booking.payment_status,time_slot.start_time,time_slot.end_time
                ,user.primary_contact FROM  booking
                INNER JOIN booking_timeslot ON booking.booking_id = booking_timeslot.booking_id
                INNER JOIN time_slot ON booking_timeslot.timeslot_id=time_slot.time_slot_id
                INNER JOIN user ON user.user_id=booking.customer_user_id
                INNER JOIN administration_staff ON booking.sports_arena_id =administration_staff.sports_arena_id
                 WHERE booking.security_status="active" AND administration_staff.user_id=:id';
        



        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
        // var_dump($result);
        return $result;
    }

    public static function saAdminCancelBookings($id){
        
        $sql = 'SELECT booking.booking_id,booking.price_per_booking,booking.booked_date,
                booking.payment_method,booking.payment_status,time_slot.start_time,time_slot.end_time
                ,user.primary_contact FROM  booking
                INNER JOIN booking_timeslot ON booking.booking_id = booking_timeslot.booking_id
                INNER JOIN time_slot ON booking_timeslot.timeslot_id=time_slot.time_slot_id
                INNER JOIN user ON user.user_id=booking.customer_user_id
                INNER JOIN administration_staff ON booking.sports_arena_id =administration_staff.sports_arena_id
                 WHERE booking.security_status="active"AND administration_staff.user_id=:id';
        



        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
        // var_dump($result);
        return $result;
    }

    public static function saAdminBookingPayment($id){
        
        $sql = 'SELECT booking.booking_id,booking.price_per_booking,booking.booked_date,
                booking.payment_method,booking.payment_status,time_slot.start_time,time_slot.end_time
                ,user.primary_contact FROM  booking
                INNER JOIN booking_timeslot ON booking.booking_id = booking_timeslot.booking_id
                INNER JOIN time_slot ON booking_timeslot.timeslot_id=time_slot.time_slot_id
                INNER JOIN user ON user.user_id=booking.customer_user_id
                INNER JOIN administration_staff ON booking.sports_arena_id =administration_staff.sports_arena_id
                 WHERE (booking.security_status="active" AND booking.payment_method="cash") AND
                  booking.payment_status="unpaid" AND administration_staff.user_id=:id';
        



        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
        // var_dump($result);
        return $result;
    }

    public static function saAdminNotification($id){
        
        $sql = 'SELECT subject,description, DATE(date) as date , TIME(date) as time 
        FROM notification WHERE user_id=:id';


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
        // var_dump($result);
        return $result;
    }

    public static function saAdminViewTimeSlots($id){
        
        $sql = 'SELECT *  FROM time_slot INNER JOIN administration_staff ON time_slot.manager_user_id=administration_staff.manager_user_id
                 WHERE  administration_staff.user_id=:id';


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
        // var_dump($result);
        return $result;
    }
    
    public static function saAdminAddTimeSlots($stime,$etime,$amount,$fid,$id){
        
        $db = static::getDB();
        
        $sql1 = 'SELECT `manager_user_id`,`manager_sports_arena_id` FROM `administration_staff` WHERE `user_id`=:id';
        $stmt1 = $db->prepare($sql1);
        $stmt1->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt1->execute();
    
        $result1 = $stmt1->fetch(PDO::FETCH_ASSOC);
        //Accessing the associative array
        $mid = $result1["manager_user_id"];
        $said = $result1["manager_sports_arena_id"];    
    
        $sql2 = 'INSERT INTO `time_slot`(`start_time`,`end_time`,`price`,`facility_id`,`manager_user_id`,`manager_sports_arena_id`)
                VALUES (:stime, :etime, :amount, :fid, :mid, :said)';
    
        $stmt2 = $db->prepare($sql2);
        $stmt2->bindValue(':stime', $stime, PDO::PARAM_STR);
        $stmt2->bindValue(':etime', $etime, PDO::PARAM_STR);
        $stmt2->bindValue(':amount', $amount, PDO::PARAM_STR);
        $stmt2->bindValue(':fid', $fid, PDO::PARAM_INT);
        $stmt2->bindValue(':mid', $mid, PDO::PARAM_INT);
        $stmt2->bindValue(':said', $said, PDO::PARAM_INT);
    
        // $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
    
        // $result = $stmt->fetchAll();
        // var_dump($result);
    
        return ($stmt2->execute());
    }

    public static function saAdminDeleteTimeSlots($id){
        
        $sql = 'SELECT *  FROM time_slot INNER JOIN administration_staff ON time_slot.manager_user_id=administration_staff.manager_user_id
                 WHERE  administration_staff.user_id=:id';


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
        // var_dump($result);
        return $result;
    }

    public static function saAdminViewFacility($id){
        
        // $sql = 'SELECT *  FROM facility WHERE manager_user_id=:id';

        $sql = 'SELECT *  FROM facility INNER JOIN administration_staff ON facility.manager_user_id=administration_staff.manager_user_id
                 WHERE  administration_staff.user_id=:id';


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
        // var_dump($result);
        return $result;
    }


    public static function saAdminDeleteFacility($id){
        
        $sql = 'SELECT *  FROM facility INNER JOIN administration_staff ON facility.manager_user_id=administration_staff.manager_user_id
                 WHERE  administration_staff.user_id=:id';


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
        // var_dump($result);
        return $result;
    }

    public static function saAdminUpdateFacility($id){
        
        $sql = 'SELECT *  FROM facility INNER JOIN administration_staff ON facility.manager_user_id=administration_staff.manager_user_id
                 WHERE  administration_staff.user_id=:id';


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
        // var_dump($result);
        return $result;
    }

    
    



}
