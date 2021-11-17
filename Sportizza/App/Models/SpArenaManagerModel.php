<?php

namespace App\Models;

use Core\Image;
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

    //Start of displaying sports arena profile
    public static function arenaProfileView($id)
    {
        $sql1 = 'SELECT sports_arena_id FROM manager WHERE user_id =:user_id';
        $db = static::getDB();
        $stmt1 = $db->prepare($sql1);
        $stmt1->bindValue(':user_id', $id, PDO::PARAM_INT);
        $stmt1->execute();
        $result1 = $stmt1->fetch(PDO::FETCH_ASSOC);
        $arena_id = $result1['sports_arena_id'];

        $sql2 = 'SELECT sports_arena_profile.sports_arena_id,sports_arena_profile.sa_name, sports_arena_profile.location, sports_arena_profile.google_map_link, 
       sports_arena_profile.profile_photo, sports_arena_profile.description, sports_arena_profile.category, 
       sports_arena_profile.payment_method,sports_arena_profile.other_facilities, sports_arena_profile.contact_no,
       sports_arena_profile_photo.photo1_name, sports_arena_profile_photo.photo2_name, sports_arena_profile_photo.photo3_name,
       sports_arena_profile_photo.photo4_name,sports_arena_profile_photo.photo5_name
        FROM sports_arena_profile INNER JOIN sports_arena_profile_photo ON 
            sports_arena_profile.s_a_profile_id = sports_arena_profile_photo.sa_profile_id 
        WHERE sports_arena_profile.sports_arena_id=:arena_id';
        $stmt2 = $db->prepare($sql2);
        $stmt2->bindValue(':arena_id', $arena_id, PDO::PARAM_INT);
        $stmt2->execute();
        $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
        return $result2;
    }

    //End of displaying sports arena profile
    public static function editArenaProfile($arena_id, $name, $location, $contact, $category, $map_link, $description, $other_facility, $payment)
    {
        $sql1 = "UPDATE sports_arena SET sa_name=:sa_name WHERE sports_arena_id=:arena_id";
        $db = static::getDB();
        $stmt1 = $db->prepare($sql1);
        $stmt1->bindValue(':arena_id', $arena_id, PDO::PARAM_INT);
        $stmt1->bindValue(':sa_name', $name, PDO::PARAM_STR);
        $stmt1->execute();

        $sql2 = "UPDATE sports_arena_profile SET sa_name=:sa_name, location=:location, google_map_link=:google_map_link, description=:description, category=:category, payment_method=:payment_method, other_facilities=:other_facilities, contact_no=:contact WHERE sports_arena_id=:arena_id";
        $stmt2 = $db->prepare($sql2);
        $stmt2->bindValue(':arena_id', $arena_id, PDO::PARAM_INT);
        $stmt2->bindValue(':sa_name', $name, PDO::PARAM_STR);
        $stmt2->bindValue(':location', $location, PDO::PARAM_STR);
        $stmt2->bindValue(':google_map_link', $map_link, PDO::PARAM_STR);
        $stmt2->bindValue(':description', $description, PDO::PARAM_STR);
        $stmt2->bindValue(':category', $category, PDO::PARAM_STR);
        $stmt2->bindValue(':payment_method', $payment, PDO::PARAM_STR);
        $stmt2->bindValue(':other_facilities', $other_facility, PDO::PARAM_STR);
        $stmt2->bindValue(':contact', $contact, PDO::PARAM_STR);
        return ($stmt2->execute());
    }

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
        $sql = 'SELECT booking.booking_id ,booking.price_per_booking,
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

    //Start of remove bookings
    public static function removeBooking($booking_id)
    {
        $sql = 'UPDATE booking SET security_status = "inactive" WHERE booking_id=:booking_id';
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':booking_id', $booking_id, PDO::PARAM_INT);
        return $stmt->execute();
    }
    //End of remove bookings

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
        WHERE security_status="active" AND manager.user_id=:id';

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

    public static function updateFacility($facility_Id, $facility_Name)
    {
        $sql = 'UPDATE facility SET facility_name=:facility_Name WHERE facility_id=:facility_Id';
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':facility_Name', $facility_Name, PDO::PARAM_STR);
        $stmt->bindValue(':facility_Id', $facility_Id, PDO::PARAM_INT);

        return $stmt->execute();
    }

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
        WHERE time_slot.security_status="active" AND time_slot.manager_user_id=:id
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
    public static function managerViewDeleteTimeSlots($id)
    {

        //Retrieving manager's timeslots to view for delete from the database
        $sql = 'SELECT time_slot.time_slot_id, 
        TIME_FORMAT(time_slot.start_time, "%H" ":" "%i") AS startTime, 
        TIME_FORMAT(time_slot.end_time, "%H" ":" "%i") AS endTime,
        time_slot.price, facility.facility_name 
        FROM time_slot 
        INNER JOIN facility ON time_slot.facility_id = facility.facility_id
        WHERE time_slot.security_status="active" AND time_slot.manager_user_id=:id
        ORDER BY  startTime ASC';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        //Converting retrieved data from database into PDOs
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        return $stmt->fetchAll();
    }

    //End of displaying sports arenas deleting the timeslots for manager

    public static function removeTimeSlot($timeSlot_Id): bool
    {

        $sql = 'UPDATE time_slot SET security_status="inactive" WHERE time_slot_id=:time_slot_id';
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':time_slot_id', $timeSlot_Id, PDO::PARAM_INT);
        return $stmt->execute();
    }


    //Start of displaying sports arenas facilities for manager
    public static function managerViewFacility($id)
    {

        //Retrieving manager's facility from the database
        $sql = 'SELECT *  FROM facility WHERE security_status = "active" AND manager_user_id=:id';


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        //Converting retrieved data from database into PDOs
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        return $stmt->fetchAll();
    }
//End of displaying sports arenas facilities for manager

//Start of displaying sports arenas facilities delete for manager
    public static function managerViewDeleteFacility($id)
    {
        //Retrieving manager's facility to view for delete from the database
        $sql = 'SELECT *  FROM facility WHERE security_status = "active" AND manager_user_id=:id';


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
    public static function removeFacility($facility_id)
    {
        $sql1 = 'UPDATE facility SET security_status="inactive" WHERE facility_id=:facility_id';
        $db = static::getDB();
        $stmt1 = $db->prepare($sql1);
        $stmt1->bindValue(':facility_id', $facility_id, PDO::PARAM_INT);
        $stmt1->execute();

        $sql2 = 'UPDATE time_slot SET security_status="inactive" WHERE facility_id=:facility_id';
        $stmt2 = $db->prepare($sql2);
        $stmt2->bindValue(':facility_id', $facility_id, PDO::PARAM_INT);
        return $stmt2->execute();
    }

    //Start of displaying sports arenas facilities update for manager
    public static function managerViewUpdateFacility($id)
    {
        //Retrieving manager's facility to view for update from the database
        $sql = 'SELECT *  FROM facility WHERE security_status = "active" AND manager_user_id=:id';


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
        $sql = 'SELECT user.user_id, user.first_name, user.last_name ,user.username,user.primary_contact,user.type
        FROM administration_staff
        INNER JOIN booking_handling_staff ON
        administration_staff.manager_user_id =booking_handling_staff.manager_user_id
        INNER JOIN user    ON administration_staff.user_id=user.user_id OR booking_handling_staff.user_id=user.user_id
         WHERE user.security_status="active" AND (administration_staff.manager_user_id=:id OR  booking_handling_staff.manager_user_id=:id)
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

    public static function removestaff($user_id)
    {
        $sql = 'UPDATE user SET security_status="inactive", account_status="inactive"  WHERE user_id=:user_id';
        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();
    }


    //Start of displaying sports arenas remove staff view for manager
    public static function managerRemoveStaff($id)
    {
        //Retrieving arenas staff to view for removing from the database
        $sql = 'SELECT user.user_id, user.first_name, user.last_name ,user.username,user.primary_contact,user.type 
        FROM administration_staff
        INNER JOIN booking_handling_staff ON
        administration_staff.manager_user_id =booking_handling_staff.manager_user_id
        INNER JOIN user    ON administration_staff.user_id=user.user_id OR booking_handling_staff.user_id=user.user_id
         WHERE user.security_status="active" AND (administration_staff.manager_user_id=:id OR  booking_handling_staff.manager_user_id=:id)
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
        $sql = 'SELECT CASE EXTRACT(MONTH FROM booking.booking_date)
                   WHEN "1" THEN CONCAT("Jan ",RIGHT(YEAR(booking.booking_date),2))
                    WHEN "2" THEN CONCAT("Feb ",RIGHT(YEAR(booking.booking_date),2))
                    WHEN "3" THEN CONCAT("Mar ",RIGHT(YEAR(booking.booking_date),2))
                    WHEN "4" THEN CONCAT("Apr ",RIGHT(YEAR(booking.booking_date),2))
                    WHEN "5" THEN CONCAT("May ",RIGHT(YEAR(booking.booking_date),2))
                    WHEN "6" THEN CONCAT("Jun ",RIGHT(YEAR(booking.booking_date),2))
                    WHEN "7" THEN CONCAT("Jul ",RIGHT(YEAR(booking.booking_date),2))
                    WHEN "8" THEN CONCAT("Aug ",RIGHT(YEAR(booking.booking_date),2))
                    WHEN "9" THEN CONCAT("Sep ",RIGHT(YEAR(booking.booking_date),2))
                    WHEN "10" THEN CONCAT("Oct ",RIGHT(YEAR(booking.booking_date),2))
                    WHEN "11" THEN CONCAT("Nov ",RIGHT(YEAR(booking.booking_date),2))
                    WHEN "12" THEN CONCAT("Dec ",RIGHT(YEAR(booking.booking_date),2))
                    ELSE "Not Valid"
                END AS Time_Booked, COUNT(DISTINCT booking.booking_id) AS No_Of_Bookings
                FROM booking
                INNER JOIN manager ON booking.sports_arena_id=manager.sports_arena_id
                WHERE booking.security_status="active" AND manager.user_id=:id
                GROUP BY Time_Booked 
                ORDER BY booking.booking_date DESC LIMIT 12 ';

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
        //Retrieving of chart data from the database
        $sql = 'SELECT EXTRACT(MONTH FROM booking.booking_date) AS BookingMonth,EXTRACT(YEAR FROM booking.booking_date) AS BookingYear FROM booking
                WHERE security_status="active"
                ORDER BY booking.booking_date DESC LIMIT 1 ';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        //Converting retrieved data from database into PDOs
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result1 = $stmt->fetch(PDO::FETCH_ASSOC);
        $lastMonth = $result1["BookingMonth"];
        $lastYear = $result1["BookingYear"];

        $days_in_month = cal_days_in_month(CAL_GREGORIAN, $lastMonth, $lastYear);
        $current_date = $lastYear."-".$lastMonth."-".$days_in_month;

        switch ($days_in_month) {
            case 30:
                $monthsadded = "+2 days -12 months";
                break;
            case 29:
                $monthsadded = "+3 days -12 months";
                break;
            case 28:
                $monthsadded = "+4 days -12 months";
                break;
            case 31:
                $monthsadded = "-12 months";
                break;
        }
        
        $date = date("Y-m-d", strtotime($monthsadded,strtotime($current_date)));

        $newYear = date("Y",strtotime($date));
        $newDay = date("d",strtotime($date));

        if($newYear<$lastYear){
            $monthsadded = "-1 day";
            $date = date("Y-m-d", strtotime($monthsadded,strtotime($date)));
        }

        //Retrieving data about payment method from the database

        $sql = 'SELECT booking.payment_method, COUNT(DISTINCT booking.booking_id) AS No_Of_Bookings
                FROM booking
                INNER JOIN manager ON booking.sports_arena_id=manager.sports_arena_id
                WHERE booking.security_status="active" AND manager.user_id=:id AND booking.booking_date BETWEEN :previousDate AND :currentDate
                GROUP BY booking.payment_method ';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        //Binding input data into database query variables
        $stmt->bindValue(':previousDate', $date, PDO::PARAM_STR);
        $stmt->bindValue(':currentDate', $current_date, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        //Converting retrieved data from database into PDOs
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result2 = $stmt->fetchAll();
        return $result2;
    }
    //End of displaying sports arenas chart 2 for manager

    //Start of displaying sports arenas chart 3 for manager
    public static function managerChart3($id)
    {
        //Retrieving of chart data from the database
        $sql = 'SELECT EXTRACT(MONTH FROM booking.booking_date) AS BookingMonth,EXTRACT(YEAR FROM booking.booking_date) AS BookingYear FROM booking
                WHERE security_status="active"
                ORDER BY booking.booking_date DESC LIMIT 1 ';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        //Converting retrieved data from database into PDOs
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result1 = $stmt->fetch(PDO::FETCH_ASSOC);
        $lastMonth = $result1["BookingMonth"];
        $lastYear = $result1["BookingYear"];

        $days_in_month = cal_days_in_month(CAL_GREGORIAN, $lastMonth, $lastYear);
        $current_date = $lastYear."-".$lastMonth."-".$days_in_month;

        switch ($days_in_month) {
            case 30:
                $monthsadded = "+2 days -12 months";
                break;
            case 29:
                $monthsadded = "+3 days -12 months";
                break;
            case 28:
                $monthsadded = "+4 days -12 months";
                break;
            case 31:
                $monthsadded = "-12 months";
                break;
        }
        
        $date = date("Y-m-d", strtotime($monthsadded,strtotime($current_date)));

        $newYear = date("Y",strtotime($date));
        $newDay = date("d",strtotime($date));

        if($newYear<$lastYear){
            $monthsadded = "-1 day";
            $date = date("Y-m-d", strtotime($monthsadded,strtotime($date)));
        }

        //Retrieving data about timeslots from the database
        $sql = 'SELECT time_slot.start_time, COUNT(DISTINCT booking.booking_id) AS No_Of_Bookings
                FROM booking 
                INNER JOIN manager ON booking.sports_arena_id=manager.sports_arena_id 
                INNER JOIN booking_timeslot ON booking.booking_id=booking_timeslot.booking_id 
                INNER JOIN time_slot ON booking_timeslot.timeslot_id=time_slot.time_slot_id 
                WHERE booking.security_status="active" AND manager.user_id=:id AND booking.booking_date BETWEEN :previousDate AND :currentDate 
                GROUP BY time_slot.start_time 
                ORDER BY time_slot.start_time ASC ';


        $db = static::getDB();
        $stmt = $db->prepare($sql);

        //Binding input data into database query variables
        $stmt->bindValue(':previousDate', $date, PDO::PARAM_STR);
        $stmt->bindValue(':currentDate', $current_date, PDO::PARAM_STR);
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
        //Retrieving of chart data from the database
        $sql = 'SELECT EXTRACT(MONTH FROM booking.booking_date) AS BookingMonth,EXTRACT(YEAR FROM booking.booking_date) AS BookingYear FROM booking
                WHERE security_status="active"
                ORDER BY booking.booking_date DESC LIMIT 1 ';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        //Converting retrieved data from database into PDOs
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result1 = $stmt->fetch(PDO::FETCH_ASSOC);
        $lastMonth = $result1["BookingMonth"];
        $lastYear = $result1["BookingYear"];

        $days_in_month = cal_days_in_month(CAL_GREGORIAN, $lastMonth, $lastYear);
        $current_date = $lastYear."-".$lastMonth."-".$days_in_month;

        switch ($days_in_month) {
            case 30:
                $monthsadded = "+2 days -12 months";
                break;
            case 29:
                $monthsadded = "+3 days -12 months";
                break;
            case 28:
                $monthsadded = "+4 days -12 months";
                break;
            case 31:
                $monthsadded = "-12 months";
                break;
        }
        
        $date = date("Y-m-d", strtotime($monthsadded,strtotime($current_date)));

        $newYear = date("Y",strtotime($date));
        $newDay = date("d",strtotime($date));

        if($newYear<$lastYear){
            $monthsadded = "-1 day";
            $date = date("Y-m-d", strtotime($monthsadded,strtotime($date)));
        }

        
        //Retrieving data about bookings per facility from the database
        $sql = 'SELECT facility.facility_name, COUNT(DISTINCT booking.booking_id) AS No_Of_Bookings
                FROM booking 
                INNER JOIN manager ON booking.sports_arena_id=manager.sports_arena_id 
                INNER JOIN facility ON booking.facility_id=facility.facility_id 
                WHERE booking.security_status="active" AND manager.user_id=:id AND booking.booking_date BETWEEN :previousDate AND :currentDate 
                GROUP BY facility.facility_name ';


        $db = static::getDB();
        $stmt = $db->prepare($sql);

        //Binding input data into database query variables
        $stmt->bindValue(':previousDate', $date, PDO::PARAM_STR);
        $stmt->bindValue(':currentDate', $current_date, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        //Converting retrieved data from database into PDOs
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
        return $result;
    }
    //End of displaying sports arenas chart 4 for manager



    //Start of Reshaping Charts
    // Chart 02
    public static function managerReshapeChart2($dateValue,$id)
    {
        //Retrieving of chart data from the database
        $sql = 'SELECT EXTRACT(MONTH FROM booking.booking_date) AS BookingMonth,EXTRACT(YEAR FROM booking.booking_date) AS BookingYear FROM booking
                WHERE security_status="active"
                ORDER BY booking.booking_date DESC LIMIT 1 ';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        //Converting retrieved data from database into PDOs
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result1 = $stmt->fetch(PDO::FETCH_ASSOC);
        $lastMonth = $result1["BookingMonth"];
        $lastYear = $result1["BookingYear"];

        $days_in_month = cal_days_in_month(CAL_GREGORIAN, $lastMonth, $lastYear);
        $current_date = $lastYear."-".$lastMonth."-".$days_in_month;

        switch ($days_in_month) {
            case 30:
                $monthsadded = "+2 days -".$dateValue." months";
                break;
            case 29:
                $monthsadded = "+3 days -".$dateValue." months";
                break;
            case 28:
                $monthsadded = "+4 days -".$dateValue." months";
                break;
            case 31:
                $monthsadded = "-".$dateValue." months";
                break;
        }
        
        $date = date("Y-m-d", strtotime($monthsadded,strtotime($current_date)));

        $newYear = date("Y",strtotime($date));
        $newDay = date("d",strtotime($date));

        if($newYear<$lastYear){
            $monthsadded = "-1 day";
            $date = date("Y-m-d", strtotime($monthsadded,strtotime($date)));
        }

        $sql = 'SELECT booking.payment_method, COUNT(DISTINCT booking.booking_id) AS No_Of_Bookings
                FROM booking
                INNER JOIN manager ON booking.sports_arena_id=manager.sports_arena_id
                WHERE booking.security_status="active" AND manager.user_id=:id AND booking.booking_date BETWEEN :previousDate AND :currentDate
                GROUP BY booking.payment_method ';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        //Binding input data into database query variables
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':previousDate', $date, PDO::PARAM_STR);
        $stmt->bindValue(':currentDate', $current_date, PDO::PARAM_STR);

        //Converting retrieved data from database into PDOs
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result2 = $stmt->fetchAll();
        return $result2;
    }

    // Chart 03
    public static function managerReshapeChart3($dateValue,$id)
    {
        //Retrieving of chart data from the database
        $sql = 'SELECT EXTRACT(MONTH FROM booking.booking_date) AS BookingMonth,EXTRACT(YEAR FROM booking.booking_date) AS BookingYear FROM booking
                WHERE security_status="active"
                ORDER BY booking.booking_date DESC LIMIT 1 ';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        //Converting retrieved data from database into PDOs
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result1 = $stmt->fetch(PDO::FETCH_ASSOC);
        $lastMonth = $result1["BookingMonth"];
        $lastYear = $result1["BookingYear"];

        $days_in_month = cal_days_in_month(CAL_GREGORIAN, $lastMonth, $lastYear);
        $current_date = $lastYear."-".$lastMonth."-".$days_in_month;

        switch ($days_in_month) {
            case 30:
                $monthsadded = "+2 days -".$dateValue." months";
                break;
            case 29:
                $monthsadded = "+3 days -".$dateValue." months";
                break;
            case 28:
                $monthsadded = "+4 days -".$dateValue." months";
                break;
            case 31:
                $monthsadded = "-".$dateValue." months";
                break;
        }
        
        $date = date("Y-m-d", strtotime($monthsadded,strtotime($current_date)));

        $newYear = date("Y",strtotime($date));
        $newDay = date("d",strtotime($date));

        if($newYear<$lastYear){
            $monthsadded = "-1 day";
            $date = date("Y-m-d", strtotime($monthsadded,strtotime($date)));
        }

        $sql = 'SELECT time_slot.start_time, COUNT(DISTINCT booking.booking_id) AS No_Of_Bookings
                FROM booking 
                INNER JOIN manager ON booking.sports_arena_id=manager.sports_arena_id 
                INNER JOIN booking_timeslot ON booking.booking_id=booking_timeslot.booking_id 
                INNER JOIN time_slot ON booking_timeslot.timeslot_id=time_slot.time_slot_id 
                WHERE booking.security_status="active" AND manager.user_id=:id AND booking.booking_date BETWEEN :previousDate AND :currentDate 
                GROUP BY time_slot.start_time 
                ORDER BY time_slot.start_time ASC ';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        //Binding input data into database query variables
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':previousDate', $date, PDO::PARAM_STR);
        $stmt->bindValue(':currentDate', $current_date, PDO::PARAM_STR);

        //Converting retrieved data from database into PDOs
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result2 = $stmt->fetchAll();
        return $result2;
    }

    // Chart 04
    public static function managerReshapeChart4($dateValue,$id)
    {
        //Retrieving of chart data from the database
        $sql = 'SELECT EXTRACT(MONTH FROM booking.booking_date) AS BookingMonth,EXTRACT(YEAR FROM booking.booking_date) AS BookingYear FROM booking
                WHERE security_status="active"
                ORDER BY booking.booking_date DESC LIMIT 1 ';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        //Converting retrieved data from database into PDOs
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result1 = $stmt->fetch(PDO::FETCH_ASSOC);
        $lastMonth = $result1["BookingMonth"];
        $lastYear = $result1["BookingYear"];

        $days_in_month = cal_days_in_month(CAL_GREGORIAN, $lastMonth, $lastYear);
        $current_date = $lastYear."-".$lastMonth."-".$days_in_month;

        switch ($days_in_month) {
            case 30:
                $monthsadded = "+2 days -".$dateValue." months";
                break;
            case 29:
                $monthsadded = "+3 days -".$dateValue." months";
                break;
            case 28:
                $monthsadded = "+4 days -".$dateValue." months";
                break;
            case 31:
                $monthsadded = "-".$dateValue." months";
                break;
        }
        
        $date = date("Y-m-d", strtotime($monthsadded,strtotime($current_date)));

        $newYear = date("Y",strtotime($date));
        $newDay = date("d",strtotime($date));

        if($newYear<$lastYear){
            $monthsadded = "-1 day";
            $date = date("Y-m-d", strtotime($monthsadded,strtotime($date)));
        }

        $sql = 'SELECT facility.facility_name, COUNT(DISTINCT booking.booking_id) AS No_Of_Bookings
                FROM booking 
                INNER JOIN manager ON booking.sports_arena_id=manager.sports_arena_id 
                INNER JOIN facility ON booking.facility_id=facility.facility_id 
                WHERE booking.security_status="active" AND manager.user_id=:id AND booking.booking_date BETWEEN :previousDate AND :currentDate 
                GROUP BY facility.facility_name ';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        //Binding input data into database query variables
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':previousDate', $date, PDO::PARAM_STR);
        $stmt->bindValue(':currentDate', $current_date, PDO::PARAM_STR);

        //Converting retrieved data from database into PDOs
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result2 = $stmt->fetchAll();
        return $result2;
    }
    //End of Reshaping Charts


//Start of adding timeslot to a sports arena for manager
    public static function managerAddTimeSlots($user_id, $start_time, $duration, $price, $facility)
    {

        //have to add condition for check timeslot is available

        $hours = (int)substr($start_time, 0, 2);
        $minutes = (int)substr($start_time, 3, 5);

        $end_time = $hours + $duration;
        $end_time = (string)($end_time . ":" . $minutes);

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
        $sql = 'SELECT * FROM  time_slot
                WHERE (manager_sports_arena_id=:arena_id AND facility_id=:facility) 
                AND (TIME(start_time)=TIME(:start_time) OR TIME(end_time)=TIME(:end_time))';
        // $sql = 'SELECT * FROM  time_slot
        //         WHERE (manager_sports_arena_id=:arena_id AND facility_id=:facility) 
        //         AND (start_time BETWEEN :start_time AND :end_time)';


        $stmt = $db->prepare($sql);

        $stmt->bindValue(':facility', $facility, PDO::PARAM_STR);
        $stmt->bindValue(':start_time', $start_time, PDO::PARAM_STR);
        $stmt->bindValue(':end_time', $end_time, PDO::PARAM_STR);
        $stmt->bindValue(':arena_id', $arena_id[0]->sports_arena_id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $timeSlots = $stmt->fetchAll();
        // var_dump($timeSlots);


        // insert query for add time slots

        if (empty($timeSlots)) {
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

        // Changing start_time variable to hh:mm:ss format
        $start_time=(string)($start_time.":00");

        $hours=(int)substr($start_time, 0,2);
        $minutes=(int)substr($start_time, 3,2);
        
        $end_time=$hours+$duration;
        // $end_time=(string)($end_time.":".$minutes);

        // If end_time is less than 10am, add a zero before the hh:mm:ss time format. Else just change it to hh:mm:ss
        if($end_time<10){
            $end_time=(string)("0".$end_time.":".$minutes.":00");
        }else{
            $end_time=(string)($end_time.":".$minutes.":00");
        }
        
        $db = static::getDB();

        // select query for select sports arena from  user id
        $sql = 'SELECT sports_arena_id FROM manager
                WHERE manager.user_id=:user_id';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $arena_id = $result["sports_arena_id"];

        $sql = 'SELECT * FROM  time_slot
                WHERE (manager_sports_arena_id=:arena_id AND facility_id=:facility)
                ORDER BY end_time ASC';
  
        $stmt = $db->prepare($sql);

        $stmt->bindValue(':facility', $facility, PDO::PARAM_STR);
        $stmt->bindValue(':arena_id', $arena_id, PDO::PARAM_INT);
        
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        // Assigning each database row to a variable
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // If input start time is between database timeslot range excluding end time and If input end time is between database timeslot range excluding start time
            // strtotime is used to convert string to time. So times can be compared
            if ((strtotime($row["end_time"]) > strtotime($start_time) && strtotime($row["start_time"]) <= strtotime($start_time)) || (strtotime($row["end_time"]) >= strtotime($end_time) && strtotime($row["start_time"]) < strtotime($end_time)))
            {
                return false;
            }
        }
        // Timeslot can be inserted
        return true;
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
    public static function findFacilityByName($id, $fname)
    {

        $sql = 'SELECT facility_name  FROM facility
                WHERE LOWER(facility.facility_name) = LOWER(:fname) AND facility.manager_user_id=:manager_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':fname', $fname, PDO::PARAM_STR);
        $stmt->bindValue(':manager_id', $id, PDO::PARAM_INT);

        //Converting retrieved data from database into PDOs
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result2 = $stmt->fetch(PDO::FETCH_ASSOC);
        $facility_name = $result2['facility_name'];
        //Assigning the fetched PDOs to result

        if (empty($facility_name)) {
            return true;
        } else {
            return false;
        }
    }
    public static function findMobileNo($mobileNo)
    {

        $sql = 'SELECT primary_contact FROM user WHERE primary_contact = (:primary_contact)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':primary_contact', $mobileNo, PDO::PARAM_STR);

        //Converting retrieved data from database into PDOs

        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $primary_contact = $result['primary_contact'];
        //Assigning the fetched PDOs to result
        var_dump($result);
        if (empty($primary_contact)) {
            return true;
        } else {
            return false;
        }
    }

    public static function findUserName($userName)
    {

        $sql = 'SELECT username FROM user WHERE username = (:username)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':username', $userName, PDO::PARAM_STR);

        //Converting retrieved data from database into PDOs

        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $user_name = $result['username'];
        //Assigning the fetched PDOs to result
        if (empty($user_name)) {
            return true;
        } else {
            return false;
        }
    }

    public static function changeImageone($id, $image_1)
    {
        $sql1 = 'SELECT sports_arena_profile_photo.sa_profile_id FROM sports_arena_profile_photo INNER JOIN 
        sports_arena_profile ON sports_arena_profile_photo.sa_profile_id=sports_arena_profile.s_a_profile_id INNER JOIN 
    sports_arena ON sports_arena_profile.sports_arena_id =sports_arena.sports_arena_id INNER JOIN manager 
        ON sports_arena.sports_arena_id = manager.sports_arena_id WHERE user_id=:user_id';
        $db = static::getDB();
        $stm1 = $db->prepare($sql1);
        $stm1->bindValue(':user_id',$id, PDO::PARAM_INT);
        $stm1->execute();
        $result1 = $stm1->fetch(PDO::FETCH_ASSOC);
        $arena_profile_id = $result1['sa_profile_id'];
        $image_1 = new Image("image_1");
        $photo1_name = $image_1->getURL();
        echo ($arena_profile_id);
        echo ($photo1_name);
        $sql2 = 'UPDATE sports_arena_profile_photo SET photo1_name=:photo1 WHERE sa_profile_id=:arena_profile_id';
        $stm2 = $db->prepare($sql2);
        $stm2->bindValue(':photo1', $photo1_name, PDO::PARAM_STR);
        $stm2->bindValue(':arena_profile_id', $arena_profile_id, PDO::PARAM_INT);
        return $stm2->execute();
    }

    public static function changeImage2($id, $image_2)
    {
        $sql1 = 'SELECT sports_arena_profile_photo.sa_profile_id FROM sports_arena_profile_photo INNER JOIN 
        sports_arena_profile ON sports_arena_profile_photo.sa_profile_id=sports_arena_profile.s_a_profile_id INNER JOIN 
    sports_arena ON sports_arena_profile.sports_arena_id =sports_arena.sports_arena_id INNER JOIN manager 
        ON sports_arena.sports_arena_id = manager.sports_arena_id WHERE user_id=:user_id';
        $db = static::getDB();
        $stm1 = $db->prepare($sql1);
        $stm1->bindValue(':user_id',$id, PDO::PARAM_INT);
        $stm1->execute();
        $result1 = $stm1->fetch(PDO::FETCH_ASSOC);
        $arena_profile_id = $result1['sa_profile_id'];
        $image_2 = new Image("image_2");
        $photo2_name = $image_2->getURL();

        $sql2 = 'UPDATE sports_arena_profile_photo SET photo2_name=:photo2 WHERE sa_profile_id=:arena_profile_id';
        $stm2 = $db->prepare($sql2);
        $stm2->bindValue(':photo2', $photo2_name, PDO::PARAM_STR);
        $stm2->bindValue(':arena_profile_id', $arena_profile_id, PDO::PARAM_INT);
        return $stm2->execute();
    }

    public static function changeImage3($id, $image_3)
    {
        $sql1 = 'SELECT sports_arena_profile_photo.sa_profile_id FROM sports_arena_profile_photo INNER JOIN 
        sports_arena_profile ON sports_arena_profile_photo.sa_profile_id=sports_arena_profile.s_a_profile_id INNER JOIN 
    sports_arena ON sports_arena_profile.sports_arena_id =sports_arena.sports_arena_id INNER JOIN manager 
        ON sports_arena.sports_arena_id = manager.sports_arena_id WHERE user_id=:user_id';
        $db = static::getDB();
        $stm1 = $db->prepare($sql1);
        $stm1->bindValue(':user_id',$id, PDO::PARAM_INT);
        $stm1->execute();
        $result1 = $stm1->fetch(PDO::FETCH_ASSOC);
        $arena_profile_id = $result1['sa_profile_id'];
        $image_3 = new Image("image_3");
        $photo3_name = $image_3->getURL();

        $sql2 = 'UPDATE sports_arena_profile_photo SET photo3_name=:photo3 WHERE sa_profile_id=:arena_profile_id';
        $stm2 = $db->prepare($sql2);
        $stm2->bindValue(':photo3', $photo3_name, PDO::PARAM_STR);
        $stm2->bindValue(':arena_profile_id', $arena_profile_id, PDO::PARAM_INT);
        return $stm2->execute();
    }

    public static function changeImage4($id, $image_4)
    {
        $sql1 = 'SELECT sports_arena_profile_photo.sa_profile_id FROM sports_arena_profile_photo INNER JOIN 
        sports_arena_profile ON sports_arena_profile_photo.sa_profile_id=sports_arena_profile.s_a_profile_id INNER JOIN 
    sports_arena ON sports_arena_profile.sports_arena_id =sports_arena.sports_arena_id INNER JOIN manager 
        ON sports_arena.sports_arena_id = manager.sports_arena_id WHERE user_id=:user_id';
        $db = static::getDB();
        $stm1 = $db->prepare($sql1);
        $stm1->bindValue(':user_id',$id, PDO::PARAM_INT);
        $stm1->execute();
        $result1 = $stm1->fetch(PDO::FETCH_ASSOC);
        $arena_profile_id = $result1['sa_profile_id'];
        $image_4 = new Image("image_4");
        $photo4_name = $image_4->getURL();

        $sql2 = 'UPDATE sports_arena_profile_photo SET photo4_name=:photo4 WHERE sa_profile_id=:arena_profile_id';
        $stm2 = $db->prepare($sql2);
        $stm2->bindValue(':photo4', $photo4_name, PDO::PARAM_STR);
        $stm2->bindValue(':arena_profile_id', $arena_profile_id, PDO::PARAM_INT);
        return $stm2->execute();
    }

    public static function changeImage5($id, $image_5)
    {
        $sql1 = 'SELECT sports_arena_profile_photo.sa_profile_id FROM sports_arena_profile_photo INNER JOIN 
        sports_arena_profile ON sports_arena_profile_photo.sa_profile_id=sports_arena_profile.s_a_profile_id INNER JOIN 
    sports_arena ON sports_arena_profile.sports_arena_id =sports_arena.sports_arena_id INNER JOIN manager 
        ON sports_arena.sports_arena_id = manager.sports_arena_id WHERE user_id=:user_id';
        $db = static::getDB();
        $stm1 = $db->prepare($sql1);
        $stm1->bindValue(':user_id',$id, PDO::PARAM_INT);
        $stm1->execute();
        $result1 = $stm1->fetch(PDO::FETCH_ASSOC);
        $arena_profile_id = $result1['sa_profile_id'];
        $image_5 = new Image("image_5");
        $photo5_name = $image_5->getURL();

        $sql2 = 'UPDATE sports_arena_profile_photo SET photo5_name=:photo5 WHERE sa_profile_id=:arena_profile_id';
        $stm2 = $db->prepare($sql2);
        $stm2->bindValue(':photo5', $photo5_name, PDO::PARAM_STR);
        $stm2->bindValue(':arena_profile_id', $arena_profile_id, PDO::PARAM_INT);
        return $stm2->execute();
    }

    public static function addStaff($manager_id,$first_name,$last_name,$mobile_number,$username,$password,$staff_type,$image)
    {
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
        $image = new Image("image");
        $profile_pic = $image->getURL();
        $sql1 = 'INSERT INTO user(username, password, first_name, last_name, security_status, account_status,primary_contact, type, profile_pic)
                 VALUES (:username, :password, :first_name, :last_name, "active", "active",:primary_contact, :type, :profile_pic)';
        $db = static::getDB();
        $stmt1 = $db->prepare($sql1);
        $stmt1->bindValue(':username',$username, PDO::PARAM_STR);
        $stmt1->bindValue(':password',$password_hashed, PDO::PARAM_STR);
        $stmt1->bindValue(':first_name',$first_name, PDO::PARAM_STR);
        $stmt1->bindValue(':last_name',$last_name, PDO::PARAM_STR);
        $stmt1->bindValue(':primary_contact',$mobile_number, PDO::PARAM_STR);
        $stmt1->bindValue(':type',$staff_type, PDO::PARAM_STR);
        $stmt1->bindValue(':profile_pic',$profile_pic, PDO::PARAM_STR);
        $stmt1->execute();

        $sql2 = 'SELECT user_id FROM user ORDER BY user_id DESC LIMIT 1';
        $stmt2 = $db->prepare($sql2);
        $stmt2->execute();
        $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
        $user_id = $result2['user_id'];

        $sql3 = 'SELECT manager.sports_arena_id FROM manager WHERE manager.user_id=:manager_id';
        $stmt3 = $db->prepare($sql3);
        $stmt3->bindValue(':manager_id',$manager_id, PDO::PARAM_INT);
        $stmt3->execute();
        $result3 = $stmt3->fetch(PDO::FETCH_ASSOC);
        $arena_id = $result3['sports_arena_id'];



        if($staff_type == "BookingHandlingStaff"){
            $sql4 = 'INSERT INTO booking_handling_staff(user_id, sports_arena_id, manager_user_id, 
                                    manager_sports_arena_id) VALUES (:user_id, :arena_id, :manager_id, :manager_arena_id)';
            $stmt4 = $db->prepare($sql4);
            $stmt4->bindValue(':user_id',$user_id, PDO::PARAM_INT);
            $stmt4->bindValue(':arena_id',$arena_id, PDO::PARAM_INT);
            $stmt4->bindValue(':manager_id',$manager_id, PDO::PARAM_INT);
            $stmt4->bindValue(':manager_arena_id',$arena_id, PDO::PARAM_INT);
            return $stmt4->execute();
        }
        if($staff_type == "AdministrationStaff"){
            $sql5 = 'INSERT INTO administration_staff(user_id, sports_arena_id, manager_user_id, manager_sports_arena_id, 
                     profile_sports_arena_id, s_a_profile_id) VALUES (:user_id, :arena_id, :manager_id, 
                     :manager_sports_arena_id, :profile_sports_arena_id, :s_a_profile_id)';
            $stmt5 = $db->prepare($sql5);
            $stmt5->bindValue(':user_id',$user_id, PDO::PARAM_INT);
            $stmt5->bindValue(':arena_id',$arena_id, PDO::PARAM_INT);
            $stmt5->bindValue(':manager_id',$manager_id, PDO::PARAM_INT);
            $stmt5->bindValue(':manager_sports_arena_id',$arena_id, PDO::PARAM_INT);
            $stmt5->bindValue(':profile_sports_arena_id',$arena_id, PDO::PARAM_INT);
            $stmt5->bindValue(':s_a_profile_id',$arena_id, PDO::PARAM_INT);
            return $stmt5->execute();
        }
    }
}
