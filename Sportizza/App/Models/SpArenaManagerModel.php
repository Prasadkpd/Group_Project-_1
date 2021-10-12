<?php

namespace App\Models;
use Core\Model;
use PDO;
use PDOException;
use App\Auth;

class SpArenaManagerModel extends \Core\Model
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

    public static function managerViewBookings($id){
        //correct
        $sql = 'SELECT booking.booking_id,booking.price_per_booking,
        DATE(booking.booking_date) AS booking_date,
                booking.payment_method,booking.payment_status,
                TIME_FORMAT(time_slot.start_time, "%H" ":" "%i") AS start_time, 
                TIME_FORMAT(time_slot.end_time, "%H" ":" "%i") AS end_time,
                user.primary_contact FROM  booking
                INNER JOIN booking_timeslot ON booking.booking_id = booking_timeslot.booking_id
                INNER JOIN time_slot ON booking_timeslot.timeslot_id=time_slot.time_slot_id
                INNER JOIN user ON user.user_id=booking.customer_user_id
                INNER JOIN manager ON booking.sports_arena_id =manager.sports_arena_id
                 WHERE booking.security_status="active" AND manager.user_id=:id
                 ORDER BY booking.booking_date DESC';
        



        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
        // var_dump($result);
        return $result;
    }

    public static function managerCancelBookings($id){
        
        $sql = 'SELECT booking.booking_id,booking.price_per_booking,
       DATE(booking.booked_date) AS booked_date,
       DATE(booking.booking_date) AS booking_date,
                booking.payment_method,booking.payment_status,
                TIME_FORMAT(time_slot.start_time, "%H" ":" "%i") AS start_time, 
                TIME_FORMAT(time_slot.end_time, "%H" ":" "%i") AS end_time,
                user.primary_contact FROM  booking
                INNER JOIN booking_timeslot ON booking.booking_id = booking_timeslot.booking_id
                INNER JOIN time_slot ON booking_timeslot.timeslot_id=time_slot.time_slot_id
                INNER JOIN user ON user.user_id=booking.customer_user_id
                INNER JOIN manager ON booking.sports_arena_id =manager.sports_arena_id
                 WHERE booking.security_status="active"AND manager.user_id=:id
                 AND CURDATE() <= booking_date
                 ORDER BY booking.booking_date DESC
                 ';
        



        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
        // var_dump($result);
        return $result;
    }

    public static function managerBookingPayment($id){
        
        $sql = 'SELECT booking.booking_id,booking.price_per_booking,
                DATE(booking.booking_date) AS booking_date,
                booking.payment_method,booking.payment_status,
                TIME_FORMAT(time_slot.start_time, "%H" ":" "%i") AS start_time,
                TIME_FORMAT(time_slot.end_time, "%H" ":" "%i") AS end_time,
                time_slot.price FROM  booking
                INNER JOIN booking_timeslot ON booking.booking_id = booking_timeslot.booking_id
                INNER JOIN time_slot ON booking_timeslot.timeslot_id=time_slot.time_slot_id
                INNER JOIN user ON user.user_id=booking.customer_user_id
                INNER JOIN manager ON booking.sports_arena_id =manager.sports_arena_id
                 WHERE (booking.security_status="active" AND booking.payment_method="cash") AND
                  booking.payment_status="unpaid" AND manager.user_id=:id';
        



        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
        // var_dump($result);
        return $result;
    }

    public static function updateBookingPayment($booking_id){

        $sql = 'UPDATE `booking` SET `payment_status`="paid" WHERE `booking_id`=:booking_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':booking_id', $booking_id, PDO::PARAM_INT);
        return ($stmt->execute());
    }

    public static function managerNotification($id){
        
        $sql = 'SELECT subject,description, DATE(date) as date , 
        TIME_FORMAT( TIME(date) ,"%H" ":" "%i") as time 
        FROM notification WHERE user_id=:id
        AND notification.security_status="active"
        ORDER BY date DESC,time DESC';


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
        // var_dump($result);
        return $result;
    }

    public static function managerViewTimeSlots($id){
        
        $sql = 'SELECT time_slot.time_slot_id, 
         TIME_FORMAT(time_slot.start_time, "%H" ":" "%i") AS startTime, 
        TIME_FORMAT(time_slot.end_time, "%H" ":" "%i") AS endTime,
        time_slot.price, facility.facility_name 
        FROM time_slot 
        INNER JOIN facility ON time_slot.facility_id = facility.facility_id
        WHERE time_slot.manager_user_id=:id
        ORDER BY  startTime ASC ';


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
        // var_dump($result);
        return $result;
    }

    public static function managerDeleteTimeSlots($id){
        
        $sql = 'SELECT time_slot.time_slot_id, 
        TIME_FORMAT(time_slot.start_time, "%H" ":" "%i") AS startTime, 
        TIME_FORMAT(time_slot.end_time, "%H" ":" "%i") AS endTime,
        time_slot.price, facility.facility_name 
        FROM time_slot 
        INNER JOIN facility ON time_slot.facility_id = facility.facility_id
        WHERE time_slot.manager_user_id=:id
        ORDER BY  startTime ASC';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
        // var_dump($result);
        return $result;
    }

    public static function managerViewFacility($id){
        
        $sql = 'SELECT *  FROM facility WHERE manager_user_id=:id';


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
        // var_dump($result);
        return $result;
    }

    public static function managerDeleteFacility($id){
        
        $sql = 'SELECT *  FROM facility WHERE manager_user_id=:id';


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
        // var_dump($result);
        return $result;
    }

    public static function managerUpdateFacility($id){
        
        $sql = 'SELECT *  FROM facility WHERE manager_user_id=:id';


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
        // var_dump($result);
        return $result;
    }


    public static function managerViewStaff($id){
        
        $sql = 'SELECT user.first_name, user.last_name ,user.username,user.primary_contact,user.type
        FROM administration_staff
        INNER JOIN booking_handling_staff ON
        administration_staff.manager_user_id =booking_handling_staff.manager_user_id
        INNER JOIN user    ON administration_staff.user_id=user.user_id OR booking_handling_staff.user_id=user.user_id
         WHERE administration_staff.manager_user_id=:id OR  booking_handling_staff.manager_user_id=:id
         GROUP BY user.user_id';


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
        // var_dump($result);
        return $result;
    }

    public static function managerRemoveStaff($id){
        
        $sql = 'SELECT user.first_name, user.last_name ,user.username,user.primary_contact,user.type 
        FROM administration_staff
        INNER JOIN booking_handling_staff ON
        administration_staff.manager_user_id =booking_handling_staff.manager_user_id
        INNER JOIN user    ON administration_staff.user_id=user.user_id OR booking_handling_staff.user_id=user.user_id
         WHERE administration_staff.manager_user_id=:id OR  booking_handling_staff.manager_user_id=:id
         GROUP BY user.user_id';


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
        // var_dump($result);
        return $result;
    }

    public static function managerChart1($id){
        
        $sql = 'SELECT booking.booking_date, COUNT(DISTINCT booking.booking_id) AS No_Of_Bookings
                FROM booking
                INNER JOIN manager ON booking.sports_arena_id=manager.sports_arena_id
                WHERE manager.user_id=:id
                GROUP BY booking.booking_date DESC LIMIT 7';

        // $sql = 'SELECT booking.booking_date, COUNT(DISTINCT booking.booking_id) AS No_Of_Bookings
        // FROM booking
        // INNER JOIN manager ON booking.sports_arena_id=manager.sports_arena_id
        // WHERE manager.user_id = "100000029"
        // GROUP BY booking.booking_date ';


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
        //  var_dump($result);
        return $result;
    }

    public static function managerChart2($id){
        
        $sql = 'SELECT booking.payment_method, COUNT(DISTINCT booking.booking_id) AS No_Of_Bookings
                FROM booking
                INNER JOIN manager ON booking.sports_arena_id=manager.sports_arena_id
                WHERE manager.user_id=:id
                GROUP BY booking.payment_method ';

        // $sql = 'SELECT booking.payment_method, COUNT(DISTINCT booking.booking_id) AS No_Of_Bookings
        // FROM booking
        // INNER JOIN manager ON booking.sports_arena_id=manager.sports_arena_id
        // WHERE manager.user_id = "100000029"
        // GROUP BY booking.payment_method ';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
        //  var_dump($result);
        return $result;
    }

    public static function managerChart3($id){
        
        $sql = 'SELECT time_slot.start_time, COUNT(DISTINCT booking.booking_id) AS No_Of_Bookings
        FROM booking 
        INNER JOIN manager ON booking.sports_arena_id=manager.sports_arena_id 
        INNER JOIN booking_timeslot ON booking.booking_id=booking_timeslot.booking_id 
        INNER JOIN time_slot ON booking_timeslot.timeslot_id=time_slot.time_slot_id 
        WHERE manager.user_id=:id AND MONTH(booking.booking_date) = MONTH(CURRENT_DATE()) 
        GROUP BY time_slot.start_time ORDER BY time_slot.start_time ASC ';


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
        //  var_dump($result);
        return $result;
    }

    public static function managerChart4($id){
        
        $sql = 'SELECT facility.facility_name, COUNT(DISTINCT booking.booking_id) AS No_Of_Bookings
                FROM booking 
                INNER JOIN manager ON booking.sports_arena_id=manager.sports_arena_id 
                INNER JOIN facility ON booking.facility_id=facility.facility_id 
                WHERE manager.user_id=:id AND MONTH(booking.booking_date) = MONTH(CURRENT_DATE()) 
                GROUP BY facility.facility_name ';


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
        //  var_dump($result);
        return $result;
    }
    


    public static function managerAddTimeSlots($user_id,$start_time,$end_time,$price,$facility){
        
        //have to add condition for check timeslot is available


        $db = static::getDB();

        // select query for select sports arena from  user id
        $sql = 'SELECT sports_arena_id FROM manager
                WHERE manager.user_id=:user_id';


        
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $arena_id = $stmt->fetchAll();
        //  var_dump($result);
        // return $result;


        // insert query for add time slots
        $sql = 'INSERT INTO `time_slot`(`start_time`,`end_time`,`price`,`facility_id`,
                `manager_user_id`,`manager_sports_arena_id`)
                VALUES (:start_time,:end_time,:price,:facility,:user_id,:arena_id)';


            $stmt = $db->prepare($sql);
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindValue(':start_time', $start_time, PDO::PARAM_STR);
            $stmt->bindValue(':end_time', $end_time, PDO::PARAM_STR);
            $stmt->bindValue(':price', $price, PDO::PARAM_STR);
            $stmt->bindValue(':facility', $facility, PDO::PARAM_STR);
            $stmt->bindValue(':arena_id', $arena_id, PDO::PARAM_INT);
        
            return ($stmt->execute());
    }






    public static function managerAddFacility($user_id,$facility){
        
        


        $db = static::getDB();

        // select query for select sports arena from  user id
        $sql = 'SELECT sports_arena_id FROM manager
                WHERE manager.user_id=:user_id';


        
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $arena_id = $stmt->fetchAll();
        //  var_dump($result);
        // return $result;


        // insert query for add time slots
        $sql = 'INSERT INTO `facility`(`facility_name`,`sports_arena_id`,`manager_user_id`,`manager_sports_arena_id`)
                VALUES (:facility,:arena_id,:user_id,:arena_id)';


            $stmt = $db->prepare($sql);
            $stmt->bindValue(':facility', $facility, PDO::PARAM_STR);
            $stmt->bindValue(':arena_id', $arena_id, PDO::PARAM_INT);
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            return ($stmt->execute());
    }


}
