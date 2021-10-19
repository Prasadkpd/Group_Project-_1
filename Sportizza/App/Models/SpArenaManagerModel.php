<?php

namespace App\Models;

use Core\Model;
use PDO;
use PDOException;
use App\Auth;

class SpArenaManagerModel extends \Core\Model
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

    //Start of displaying sports arena bookings
    public static function managerViewBookings($id)
    {
        //Retrieving sports arena bookings
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

        //Converting retrieved data from database into PDOs
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
        return $result;
    }
    //End of displaying sports arena bookings

    //Start of displaying sports arena's cancel bookings
    public static function managerCancelBookings($id)
    {
        //Retrieving sports arena bookings
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
                 ORDER BY booking.booking_date DESC';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        //Converting retrieved data from database into PDOs
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
        return $result;
    }
    //End of displaying sports arena's cancel bookings

    //Start of displaying sports arena's booking payment
    public static function managerBookingPayment($id)
    {
        //Retrieving bookings from the database
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

        //Converting retrieved data from database into PDOs
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
        return $result;
    }
    //End of displaying sports arena's booking payment

    //Start of displaying sports arena's updating bookings
    public static function updateBookingPayment($booking_id)
    {
        //Updating status of the bookings in the database
        $sql = 'UPDATE `booking` SET `payment_status`="paid" WHERE `booking_id`=:booking_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':booking_id', $booking_id, PDO::PARAM_INT);   
        return ($stmt->execute());
    }
    //End of displaying sports arena's updating bookings

    //Start of displaying notifications for manager
    public static function managerNotification($id)
    {
        //Retrieving manager's notifications from the database
        $sql = 'SELECT subject,description, DATE(date) as date , 
        TIME_FORMAT( TIME(date) ,"%H" ":" "%i") as time 
        FROM notification WHERE user_id=:id
        AND notification.security_status="active"
        ORDER BY date DESC,time DESC';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        //Converting retrieved data from database into PDOs
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
        return $result;
    }
    //End of displaying notifications for manager

    //Start of displaying sports arenas facilities for manager
    public static function managerGetFacilityName($id)
    {
        //Retrieving manager's facility from the database
        $sql = 'SELECT facility.facility_id, facility.facility_name
        FROM facility
        INNER JOIN manager ON facility.manager_sports_arena_id=manager.sports_arena_id
        WHERE manager.user_id=:id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        //Converting retrieved data from database into PDOs
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
        return $result;
    }
    //End of displaying sports arenas facilities for manager

    //Start of displaying sports arenas timeslots for manager
    public static function managerViewTimeSlots($id)
    {
        //Retrieving manager's timeslots to view from the database
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

        //Converting retrieved data from database into PDOs
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
        return $result;
    }
    //End of displaying sports arenas timeslots for manager

    //Start of displaying sports arenas deleting the timeslots for manager
    public static function managerDeleteTimeSlots($id)
    {

         //Retrieving manager's timeslots to view for delete from the database
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

        //Converting retrieved data from database into PDOs
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
        return $result;
    }
    //End of displaying sports arenas deleting the timeslots for manager

    //Start of displaying sports arenas facilities for manager
    public static function managerViewFacility($id)
    {

         //Retrieving manager's facility from the database
        $sql = 'SELECT *  FROM facility WHERE manager_user_id=:id';


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        //Converting retrieved data from database into PDOs
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
        return $result;
    }
//End of displaying sports arenas facilities for manager

//Start of displaying sports arenas facilities delete for manager
    public static function managerDeleteFacility($id)
    {
         //Retrieving manager's facility to view for delete from the database
        $sql = 'SELECT *  FROM facility WHERE manager_user_id=:id';


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        //Converting retrieved data from database into PDOs
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
        return $result;
    }
    //End of displaying sports arenas facilities delete for manager
    
    //Start of displaying sports arenas facilities update for manager
    public static function managerUpdateFacility($id)
    {
  //Retrieving manager's facility to view for update from the database
        $sql = 'SELECT *  FROM facility WHERE manager_user_id=:id';


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        //Converting retrieved data from database into PDOs
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
        return $result;
    }
//End of displaying sports arenas facilities update for manager

//Start of displaying sports arenas view staff for manager
    public static function managerViewStaff($id)
    {
 //Retrieving arenas staff to view from the database
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

        //Converting retrieved data from database into PDOs
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
        return $result;
    }
    //End of displaying sports arenas view staff for manager

    //Start of displaying sports arenas remove staff view for manager
    public static function managerRemoveStaff($id)
    {
 //Retrieving arenas staff to view for removing from the database
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

        //Converting retrieved data from database into PDOs
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
        return $result;
    }
       //End of displaying sports arenas remove staff view for manager

          //Start of displaying sports arenas chart 1 for manager
    public static function managerChart1($id)
    {
//Retrieving data about bookings from the database
        $sql = 'SELECT booking.booking_date, COUNT(DISTINCT booking.booking_id) AS No_Of_Bookings
                FROM booking
                INNER JOIN manager ON booking.sports_arena_id=manager.sports_arena_id
                WHERE manager.user_id=:id
                GROUP BY booking.booking_date 
                ORDER BY booking.booking_date DESC LIMIT 7';

        // $sql = 'SELECT booking.booking_date, COUNT(DISTINCT booking.booking_id) AS No_Of_Bookings
        // FROM booking
        // INNER JOIN manager ON booking.sports_arena_id=manager.sports_arena_id
        // WHERE manager.user_id = "100000029"
        // GROUP BY booking.booking_date ';


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        //Converting retrieved data from database into PDOs
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
        return $result;
    }
       //End of displaying sports arenas chart 1 for manager

          //Start of displaying sports arenas chart 2 for manager
    public static function managerChart2($id)
    {
        //Retrieving data about payment method from the database

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

        //Converting retrieved data from database into PDOs
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
        return $result;
    }
    //End of displaying sports arenas chart 2 for manager

     //Start of displaying sports arenas chart 3 for manager
    public static function managerChart3($id)
    {
//Retrieving data about timeslots from the database
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

        //Converting retrieved data from database into PDOs
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
        return $result;
    }
    //End of displaying sports arenas chart 3 for manager

     //Start of displaying sports arenas chart 4 for manager
    public static function managerChart4($id)
    {
//Retrieving data about bookings per facility from the database
        $sql = 'SELECT facility.facility_name, COUNT(DISTINCT booking.booking_id) AS No_Of_Bookings
                FROM booking 
                INNER JOIN manager ON booking.sports_arena_id=manager.sports_arena_id 
                INNER JOIN facility ON booking.facility_id=facility.facility_id 
                WHERE manager.user_id=:id AND MONTH(booking.booking_date) = MONTH(CURRENT_DATE()) 
                GROUP BY facility.facility_name ';


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        //Converting retrieved data from database into PDOs
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
        return $result;
    }
//End of displaying sports arenas chart 4 for manager

//Start of adding timeslot to a sports arena for manager
    public static function managerAddTimeSlots($user_id, $start_time, $duration, $price, $facility)
    {

        //have to add condition for check timeslot is available

        $hours=(int)substr($start_time, 0,2);
        $minutes=(int)substr($start_time, 3,5);
        
        $end_time=$hours+$duration;
        $end_time=(string)($end_time.":".$minutes);

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


        //query for check timeslot is availability
        // $sql = 'SELECT * FROM  time_slot
        //         WHERE (manager_sports_arena_id=:arena_id AND facility_id=:facility) 
        //         AND (start_time=:start_time OR end_time=:end_time)';
        $sql = 'SELECT * FROM  time_slot
                WHERE (manager_sports_arena_id=:arena_id AND facility_id=:facility) 
                AND (start_time BETWEEN :start_time AND :end_time)';


        
        $stmt = $db->prepare($sql);
        
        $stmt->bindValue(':facility', $facility, PDO::PARAM_STR);
        $stmt->bindValue(':start_time', $start_time, PDO::PARAM_STR);
        $stmt->bindValue(':end_time', $end_time, PDO::PARAM_STR);
        $stmt->bindValue(':arena_id', $arena_id[0]->sports_arena_id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $timeSlots = $stmt->fetchAll();
            var_dump($timeSlots);


        // insert query for add time slots

        if(empty($timeSlots)){
            $sql = 'INSERT INTO `time_slot`(`start_time`,`end_time`,`price`,`facility_id`,
                `manager_user_id`,`manager_sports_arena_id`)
                VALUES (:start_time,:end_time,:price,:facility,:user_id,:arena_id)';


            $stmt = $db->prepare($sql);
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindValue(':start_time', $start_time, PDO::PARAM_STR);
            $stmt->bindValue(':end_time', $end_time, PDO::PARAM_STR);
            $stmt->bindValue(':price', $price, PDO::PARAM_STR);
            $stmt->bindValue(':facility', $facility, PDO::PARAM_STR);
            $stmt->bindValue(':arena_id', $arena_id[0]->sports_arena_id, PDO::PARAM_INT);
        
            return ($stmt->execute());
        }
        
        return ($stmt->execute());
    }
    //End of adding timeslot to a sports arena for manager

    public static function managerCheckExistingTimeslots($user_id,$start_time,$duration,$price,$facility){
        $hours=(int)substr($start_time, 0,2);
        $minutes=(int)substr($start_time, 3,5);
        
        $end_time=$hours+$duration;
        $end_time=(string)($end_time.":".$minutes);

        $db = static::getDB();

        

        //have to add condition for check timeslot is available


        

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

        //query for check timeslot is availability
        $sql = 'SELECT * FROM  time_slot
                WHERE (manager_sports_arena_id=:arena_id AND facility_id=:facility) 
                AND (start_time BETWEEN :start_time AND :end_time)';


        
        $stmt = $db->prepare($sql);
        
        $stmt->bindValue(':facility', $facility, PDO::PARAM_STR);
        $stmt->bindValue(':start_time', $start_time, PDO::PARAM_STR);
        $stmt->bindValue(':end_time', $end_time, PDO::PARAM_STR);
        $stmt->bindValue(':arena_id', $arena_id[0]->sports_arena_id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $timeSlots = $stmt->fetchAll();

        if(empty($timeSlots)){
            return true;
        } else {
            return false;
        }
    }





//Start of adding facility to a sports arena for manager
    public static function managerAddFacility($user_id, $facility)
    {

        $db = static::getDB();

        // select query for select sports arena from  user id
        $sql = 'SELECT sports_arena_id FROM manager
                WHERE manager.user_id=:user_id';



        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        
        // Assign retrieved value to variable
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        //Accessing the associative array
        $arena_id = $result["sports_arena_id"];

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
    //End of adding facility to a sports arena for manager
}
