<?php

namespace App\Models;

use Core\Image;
use Core\Model;
use PDO;
use PDOException;
use App\Auth;

class SpAdministrationStaffModel extends \Core\Model
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

    /**********************************************************************************************************************************/
    //Start of displaying sports arena profile
    public static function arenaProfileView($id)
    {
        try {
            //Create a new database connection
            $db = static::getDB();

            //Start transaction
            $db->beginTransaction();

            //Retrieving saAdmin's sports arena id
            $sql1 = 'SELECT sports_arena_id 
            FROM administration_staff 
            WHERE user_id =:user_id';

            $stmt1 = $db->prepare($sql1);
            $stmt1->bindValue(':user_id', $id, PDO::PARAM_INT);
            $stmt1->execute();

            //Fetching the sports arena id
            $result1 = $stmt1->fetch(PDO::FETCH_ASSOC);
            $arena_id = $result1['sports_arena_id'];

            //Retrieving the sports arena profile details
            $sql2 = 'SELECT sports_arena_profile.sports_arena_id,sports_arena_profile.sa_name, 
            sports_arena_profile.location, sports_arena_profile.google_map_link, 
            sports_arena_profile.profile_photo, sports_arena_profile.description, 
            sports_arena_profile.category, sports_arena_profile.payment_method,
            sports_arena_profile.other_facilities, sports_arena_profile.contact_no,
            sports_arena_profile_photo.photo1_name, sports_arena_profile_photo.photo2_name, 
            sports_arena_profile_photo.photo3_name,sports_arena_profile_photo.photo4_name,
            sports_arena_profile_photo.photo5_name
            FROM sports_arena_profile 
            INNER JOIN sports_arena_profile_photo ON sports_arena_profile.s_a_profile_id = sports_arena_profile_photo.sa_profile_id 
            WHERE sports_arena_profile.sports_arena_id=:arena_id';

            $stmt2 = $db->prepare($sql2);
            $stmt2->bindValue(':arena_id', $arena_id, PDO::PARAM_INT);
            $stmt2->execute();

            //Fetching all the sports arena details
            $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);

            //End transaction
            $db->commit();
            return $result2;
        } catch (PDOException $e) {
            $db->rollback();
            throw $e;
        }
    }
    //End of displaying sports arena profile

    /**********************************************************************************************************************************/

    //Start of sports arena edit details
    //Start of editing sports arena profile(Excluding images)
    public static function editArenaProfile($arena_id, $name, $location, $contact, $category, $map_link, $description, $other_facility, $payment)
    {
        try {
            //Create a new database connection
            $db = static::getDB();
            //Start transaction
            $db->beginTransaction();

            //Update the sports arena name in sports arena table
            $sql1 = "UPDATE sports_arena 
            SET sa_name=:sa_name 
            WHERE sports_arena_id=:arena_id";

            $stmt1 = $db->prepare($sql1);
            $stmt1->bindValue(':arena_id', $arena_id, PDO::PARAM_INT);
            $stmt1->bindValue(':sa_name', $name, PDO::PARAM_STR);
            $stmt1->execute();

            //Update the sports arena details in sports arena profile table
            $sql2 = "UPDATE sports_arena_profile 
            SET sa_name=:sa_name, location=:location, google_map_link=:google_map_link, 
            description=:description, category=:category, payment_method=:payment_method, 
            other_facilities=:other_facilities, contact_no=:contact 
            WHERE sports_arena_id=:arena_id";

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
            $stmt2->execute();

            //End transaction
            $db->commit();
            return true;
        } catch (PDOException $e) {
            $db->rollback();
            throw $e;
        }
    }
    //End of editing sports arena profile(Excluding images)

    //Start of editing image 1
    public static function changeImageone($id, $image_1)
    {
        try {
            //Create a new database connection
            $db = static::getDB();

            //Start transaction
            $db->beginTransaction();

            //Retrieving the sports arena profile id
            $sql1 = 'SELECT sports_arena_profile_photo.sa_profile_id 
            FROM sports_arena_profile_photo 
            INNER JOIN sports_arena_profile ON sports_arena_profile_photo.sa_profile_id=sports_arena_profile.s_a_profile_id 
            INNER JOIN sports_arena ON sports_arena_profile.sports_arena_id =sports_arena.sports_arena_id 
            INNER JOIN administration_staff ON sports_arena.sports_arena_id = administration_staff.sports_arena_id 
            WHERE user_id=:user_id';

            $stm1 = $db->prepare($sql1);
            $stm1->bindValue(':user_id', $id, PDO::PARAM_INT);
            $stm1->execute();

            //Fetching the sports arena profile id
            $result1 = $stm1->fetch(PDO::FETCH_ASSOC);
            $arena_profile_id = $result1['sa_profile_id'];

            //Creating image1 from image class and obtaining saved loaction
            $image_1 = new Image("image_1");
            $photo1_name = $image_1->getURL();

            //Update sports arena profile photo1
            $sql2 = 'UPDATE sports_arena_profile_photo 
            SET photo1_name=:photo1 
            WHERE sa_profile_id=:arena_profile_id';

            $stm2 = $db->prepare($sql2);
            $stm2->bindValue(':photo1', $photo1_name, PDO::PARAM_STR);
            $stm2->bindValue(':arena_profile_id', $arena_profile_id, PDO::PARAM_INT);
            $stm2->execute();

            //End transaction
            $db->commit();
            return true;
        } catch (PDOException $e) {
            $db->rollback();
            throw $e;
        }
    }
    //End of editing image 1

    //Start of editing image 2
    public static function changeImage2($id, $image_2)
    {
        try {
            //Create a new database connection
            $db = static::getDB();

            //Start transaction
            $db->beginTransaction();

            //Retrieving the sports arena profile id
            $sql1 = 'SELECT sports_arena_profile_photo.sa_profile_id 
            FROM sports_arena_profile_photo 
            INNER JOIN sports_arena_profile ON sports_arena_profile_photo.sa_profile_id=sports_arena_profile.s_a_profile_id 
            INNER JOIN sports_arena ON sports_arena_profile.sports_arena_id =sports_arena.sports_arena_id 
            INNER JOIN administration_staff ON sports_arena.sports_arena_id = administration_staff.sports_arena_id 
            WHERE user_id=:user_id';

            $stm1 = $db->prepare($sql1);
            $stm1->bindValue(':user_id', $id, PDO::PARAM_INT);
            $stm1->execute();

            //Fetching the sports arena profile id
            $result1 = $stm1->fetch(PDO::FETCH_ASSOC);
            $arena_profile_id = $result1['sa_profile_id'];

            //Creating image2 from image class and obtaining saved loaction
            $image_2 = new Image("image_2");
            $photo2_name = $image_2->getURL();

            //Update sports arena profile photo2
            $sql2 = 'UPDATE sports_arena_profile_photo 
            SET photo2_name=:photo2 
            WHERE sa_profile_id=:arena_profile_id';

            $stm2 = $db->prepare($sql2);
            $stm2->bindValue(':photo2', $photo2_name, PDO::PARAM_STR);
            $stm2->bindValue(':arena_profile_id', $arena_profile_id, PDO::PARAM_INT);
            $stm2->execute();

            //End transaction
            $db->commit();
            return true;
        } catch (PDOException $e) {
            $db->rollback();
            throw $e;
        }
    }
    //End of editing image 2

    //Start of editing image 3
    public static function changeImage3($id, $image_3)
    {
        try {
            //Create a new database connection
            $db = static::getDB();

            //Start transaction
            $db->beginTransaction();

            //Retrieving the sports arena profile id
            $sql1 = 'SELECT sports_arena_profile_photo.sa_profile_id 
            FROM sports_arena_profile_photo 
            INNER JOIN sports_arena_profile ON sports_arena_profile_photo.sa_profile_id=sports_arena_profile.s_a_profile_id 
            INNER JOIN sports_arena ON sports_arena_profile.sports_arena_id =sports_arena.sports_arena_id 
            INNER JOIN administration_staff ON sports_arena.sports_arena_id = administration_staff.sports_arena_id 
            WHERE user_id=:user_id';

            $stm1 = $db->prepare($sql1);
            $stm1->bindValue(':user_id', $id, PDO::PARAM_INT);
            $stm1->execute();

            //Fetching the sports arena profile id
            $result1 = $stm1->fetch(PDO::FETCH_ASSOC);
            $arena_profile_id = $result1['sa_profile_id'];

            //Creating image3 from image class and obtaining saved loaction
            $image_3 = new Image("image_3");
            $photo3_name = $image_3->getURL();

            //Update sports arena profile photo3
            $sql2 = 'UPDATE sports_arena_profile_photo 
            SET photo3_name=:photo3 
            WHERE sa_profile_id=:arena_profile_id';

            $stm2 = $db->prepare($sql2);
            $stm2->bindValue(':photo3', $photo3_name, PDO::PARAM_STR);
            $stm2->bindValue(':arena_profile_id', $arena_profile_id, PDO::PARAM_INT);
            $stm2->execute();

            //End transaction
            $db->commit();
            return true;
        } catch (PDOException $e) {
            $db->rollback();
            throw $e;
        }
    }
    //End of editing image 3

    //Start of editing image 4
    public static function changeImage4($id, $image_4)
    {
        try {
            //Create a new database connection
            $db = static::getDB();

            //Start transaction
            $db->beginTransaction();

            //Retrieving the sports arena profile id
            $sql1 = 'SELECT sports_arena_profile_photo.sa_profile_id 
            FROM sports_arena_profile_photo 
            INNER JOIN sports_arena_profile ON sports_arena_profile_photo.sa_profile_id=sports_arena_profile.s_a_profile_id 
            INNER JOIN sports_arena ON sports_arena_profile.sports_arena_id =sports_arena.sports_arena_id 
            INNER JOIN administration_staff ON sports_arena.sports_arena_id = administration_staff.sports_arena_id 
            WHERE user_id=:user_id';

            $stm1 = $db->prepare($sql1);
            $stm1->bindValue(':user_id', $id, PDO::PARAM_INT);
            $stm1->execute();

            //Fetching the sports arena profile id
            $result1 = $stm1->fetch(PDO::FETCH_ASSOC);
            $arena_profile_id = $result1['sa_profile_id'];

            //Creating image4 from image class and obtaining saved loaction
            $image_4 = new Image("image_4");
            $photo4_name = $image_4->getURL();

            //Update sports arena profile photo4
            $sql2 = 'UPDATE sports_arena_profile_photo 
            SET photo4_name=:photo4 
            WHERE sa_profile_id=:arena_profile_id';

            $stm2 = $db->prepare($sql2);
            $stm2->bindValue(':photo4', $photo4_name, PDO::PARAM_STR);
            $stm2->bindValue(':arena_profile_id', $arena_profile_id, PDO::PARAM_INT);
            $stm2->execute();

            //End transaction
            $db->commit();
            return true;
        } catch (PDOException $e) {
            $db->rollback();
            throw $e;
        }
    }
    //End of editing image 4

    //Start of editing image 5
    public static function changeImage5($id, $image_5)
    {
        try {
            //Create a new database connection
            $db = static::getDB();

            //Start transaction
            $db->beginTransaction();

            //Retrieving the sports arena profile id
            $sql1 = 'SELECT sports_arena_profile_photo.sa_profile_id 
            FROM sports_arena_profile_photo 
            INNER JOIN sports_arena_profile ON sports_arena_profile_photo.sa_profile_id=sports_arena_profile.s_a_profile_id 
            INNER JOIN sports_arena ON sports_arena_profile.sports_arena_id =sports_arena.sports_arena_id 
            INNER JOIN administration_staff ON sports_arena.sports_arena_id = administration_staff.sports_arena_id 
            WHERE user_id=:user_id';

            $stm1 = $db->prepare($sql1);
            $stm1->bindValue(':user_id', $id, PDO::PARAM_INT);
            $stm1->execute();

            //Fetching the sports arena profile id
            $result1 = $stm1->fetch(PDO::FETCH_ASSOC);
            $arena_profile_id = $result1['sa_profile_id'];

            //Creating image5 from image class and obtaining saved loaction
            $image_5 = new Image("image_5");
            $photo5_name = $image_5->getURL();

            //Update sports arena profile photo5
            $sql2 = 'UPDATE sports_arena_profile_photo 
            SET photo5_name=:photo5 
            WHERE sa_profile_id=:arena_profile_id';

            $stm2 = $db->prepare($sql2);
            $stm2->bindValue(':photo5', $photo5_name, PDO::PARAM_STR);
            $stm2->bindValue(':arena_profile_id', $arena_profile_id, PDO::PARAM_INT);
            $stm2->execute();

            //End transaction
            $db->commit();
            return true;
        } catch (PDOException $e) {
            $db->rollback();
            throw $e;
        }
    }
    //End of editing image 5
    //End of sports arena edit details

    /**********************************************************************************************************************************/

    //Start of manage bookings
    //Start of Displaying sports arena bookings
    public static function saAdminViewBookings($id)
    {
        //Create a new database connection
        $db = static::getDB();

        //Retrieving bookings from the database
        $sql = 'SELECT booking.booking_id,booking.price_per_booking, DATE(booking.booking_date) AS booking_date,
        booking.payment_method,booking.payment_status,TIME_FORMAT(time_slot.start_time, "%H" ":" "%i") AS start_time, 
        TIME_FORMAT(time_slot.end_time, "%H" ":" "%i") AS end_time,user.primary_contact 
        FROM  booking
        INNER JOIN booking_timeslot ON booking.booking_id = booking_timeslot.booking_id
        INNER JOIN time_slot ON booking_timeslot.timeslot_id=time_slot.time_slot_id
        INNER JOIN user ON user.user_id=booking.customer_user_id
        INNER JOIN administration_staff ON booking.sports_arena_id =administration_staff.sports_arena_id
        WHERE booking.security_status="active" AND administration_staff.user_id=:id                 
        ORDER BY booking.booking_date DESC';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        //Converting retrieved data from database into PDOs
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
        return $result;
    }
    //End of Displaying sports arena bookings

    //Start of displaying sports arena's available timeslots for booking(by default)
    public static function saAdminViewAvailableTimeSlots($saAdmin_id)
    {
        try {
            //Create a new database connection
            $db = static::getDB();

            //Start transaction
            $db->beginTransaction();

            //Retrieving the sports arena id
            $sql = 'SELECT sports_arena_id 
            FROM administration_staff 
            WHERE user_id=:user_id';

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':user_id', $saAdmin_id, PDO::PARAM_INT);
            $stmt->execute();

            //Fetching the sports arena id
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $arena_id = $result['sports_arena_id'];

            //Retrieving sports arena timeslot from the database
            $sql = 'SELECT DISTINCT time_slot.time_slot_id,TIME_FORMAT(time_slot.start_time, "%H:%i") AS startTime,
            TIME_FORMAT(time_slot.end_time, "%H:%i") AS endTime,time_slot.price,facility.facility_name,
            sports_arena_profile.payment_method
            FROM time_slot
            INNER JOIN facility ON time_slot.facility_id= facility.facility_id
            INNER JOIN sports_arena_profile ON facility.sports_arena_id= sports_arena_profile.sports_arena_id
            Left JOIN booking_timeslot ON time_slot.time_slot_id =booking_timeslot.timeslot_id
            Left JOIN booking ON booking_timeslot.booking_id=booking.booking_id
            WHERE time_slot.time_slot_id NOT IN
                                                (SELECT booking_timeslot.timeslot_id 
                                                FROM booking 
                                                INNER JOIN booking_timeslot ON booking.booking_id=booking_timeslot.booking_id 
                                                WHERE ((booking.booking_date=CURRENT_DATE()) OR 
                                                (payment_status="pending" AND booked_date +INTERVAL 30 MINUTE > CURRENT_TIMESTAMP))
                                                AND booking_timeslot.security_status="active")
            AND time_slot.manager_sports_arena_id=:arena_id 
            AND time_slot.security_status="active" 
            AND time_slot.start_time > CURRENT_TIME() 
            GROUP BY time_slot.time_slot_id
            ORDER BY time_slot.start_time';

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':arena_id', $arena_id, PDO::PARAM_INT);
            $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
            $stmt->execute();

            //Assigning the fetched PDOs to result
            $result = $stmt->fetchAll();

            //End transaction
            $db->commit();
            return $result;
        } catch (PDOException $e) {
            $db->rollback();
            throw $e;
        }
    }
    //End of displaying sports arena's available timeslots for booking(by default)

    //Start of displaying sports arena's available timeslots for booking(after selecting a date)
    public static function saAdminSearchTimeSlotsDate($saAdmin_id, $date)
    {
        try {
            //Create a new database connection
            $db = static::getDB();

            //Start transaction
            $db->beginTransaction();

            //Retrieving the sports arena id
            $sql = 'SELECT sports_arena_id 
            FROM administration_staff 
            WHERE user_id=:user_id';

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':user_id', $saAdmin_id, PDO::PARAM_INT);
            $stmt->execute();

            //Fetching the sports arena id
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $arena_id = $result['sports_arena_id'];

            //Chaning the date format
            $current_date = date('Y-m-d');

            //If selected date is current date, execute the following
            if ($date != $current_date) {

                //Retrieving sports arena timeslots
                $sql = 'SELECT DISTINCT time_slot.time_slot_id, TIME_FORMAT(time_slot.start_time, "%H:%i") AS startTime,
                TIME_FORMAT(time_slot.end_time, "%H:%i") AS endTime, time_slot.price,facility.facility_name,
                sports_arena_profile.payment_method
                FROM time_slot
                INNER JOIN facility ON time_slot.facility_id= facility.facility_id
                INNER JOIN sports_arena_profile ON facility.sports_arena_id= sports_arena_profile.sports_arena_id
                WHERE time_slot.time_slot_id NOT IN
                                                    (SELECT booking_timeslot.timeslot_id 
                                                    FROM booking 
                                                    INNER JOIN booking_timeslot ON booking.booking_id=booking_timeslot.booking_id 
                                                    WHERE ((booking.booking_date=:date) AND 
                                                    (payment_status="pending" AND booked_date +INTERVAL 30 MINUTE > CURRENT_TIMESTAMP))
                                                    AND booking_timeslot.security_status="active")
                AND time_slot.manager_sports_arena_id=:arena_id
                AND time_slot.security_status="active"
                GROUP BY time_slot.time_slot_id
                ORDER BY time_slot.start_time;';

                $stmt = $db->prepare($sql);
                $stmt->bindValue(':date', $date, PDO::PARAM_STR);
                $stmt->bindValue(':arena_id', $arena_id, PDO::PARAM_INT);
            }

            //If selected date is not the current date, execute the following
            else {

                //Retrieving sports arena timeslots
                $sql = 'SELECT DISTINCT time_slot.time_slot_id,TIME_FORMAT(time_slot.start_time, "%H:%i") AS startTime,
                TIME_FORMAT(time_slot.end_time, "%H:%i") AS endTime,time_slot.price,facility.facility_name,
                sports_arena_profile.payment_method
                FROM time_slot
                INNER JOIN facility ON time_slot.facility_id= facility.facility_id
                INNER JOIN sports_arena_profile ON facility.sports_arena_id= sports_arena_profile.sports_arena_id
                Left JOIN booking_timeslot ON time_slot.time_slot_id =booking_timeslot.timeslot_id
                Left JOIN booking ON booking_timeslot.booking_id=booking.booking_id
                WHERE time_slot.time_slot_id NOT IN
                                                    (SELECT booking_timeslot.timeslot_id 
                                                    FROM booking 
                                                    INNER JOIN booking_timeslot ON booking.booking_id=booking_timeslot.booking_id 
                                                    WHERE ((booking.booking_date=CURRENT_DATE()) OR 
                                                    (payment_status="pending" AND booked_date +INTERVAL 30 MINUTE > CURRENT_TIMESTAMP))
                                                    AND booking_timeslot.security_status="active")
                AND time_slot.manager_sports_arena_id=:arena_id 
                AND time_slot.security_status="active" 
                AND time_slot.start_time > CURRENT_TIME() 
                GROUP BY time_slot.time_slot_id
                ORDER BY time_slot.start_time';

                $stmt = $db->prepare($sql);
                $stmt->bindValue(':arena_id', $arena_id, PDO::PARAM_INT);
            }

            $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
            $stmt->execute();

            //End transaction
            $db->commit();
        } catch (PDOException $e) {
            $db->rollback();
            throw $e;
        }

        //Creating output for ajax
        $output = "";

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $output .= "
        <li id={$row["time_slot_id"]} class='hideDetails'>
        <div class='row'>
            <span class='s-time'>{$row["startTime"]}</span>&nbsp;-
            <span class='e-time'>{$row["endTime"]}</span>
        </div>
        <div class='row'>
            <span class='facility'>{$row["facility_name"]}</span>
        </div>
        <div class='row'>
            <span class='price'>LKR {$row["price"]}</span>
        </div>
        <div>
            <button class='removeItem' value={$row["time_slot_id"]} type='button'>
            <i class='fas fa-cart-plus'></i></button>
        </div>
        </div>
        <input type='hidden' name='timeSlotId' value={$row["time_slot_id"]}>
        <input type='date' name='bookingDate' class='bookingDatehidden' value={$date} style='display: none;'>
    </li>";
        }
        return $output;
    }
    //End of displaying sports arena's available timeslots for booking(after selecting a date)

    //Start of adding items to booking table and cart
    public static function saAdminAddToCart($saAdmin_id, $timeslot_id, $booking_date, $payment_method)
    {
        try {
            //Create a new database connection
            $db = static::getDB();

            //Start transaction
            $db->beginTransaction();

            //Retrieving the selected timeslot details
            $sql = 'SELECT time_slot.start_time, time_slot.end_time,time_slot.price,time_slot.facility_id,
            time_slot.manager_sports_arena_id
            FROM time_slot   
            WHERE time_slot.security_status="active" AND time_slot.time_slot_id=:timeslot_id';

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':timeslot_id', $timeslot_id, PDO::PARAM_INT);
            $stmt->execute();

            //Assigning the fetched PDOs to result
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $price = $result['price'];
            $facility_id = $result['facility_id'];
            $arena_id = $result['manager_sports_arena_id'];

            //Insert the timeslot details to the booking table
            $sql2 = 'INSERT INTO `booking`
            (`booking_date`,`customer_user_id`, `payment_method`, `price_per_booking`, `facility_id`, `sports_arena_id`) 
            VALUES (:booking_date,:customer_user_id,:payment_method,:price,:facility_id,:sports_arena_id)';


            $stmt2 = $db->prepare($sql2);
            $stmt2->bindValue(':customer_user_id', $saAdmin_id, PDO::PARAM_INT);
            $stmt2->bindValue(':booking_date', $booking_date, PDO::PARAM_STR);
            $stmt2->bindValue(':payment_method', $payment_method, PDO::PARAM_STR);
            $stmt2->bindValue(':price', $price, PDO::PARAM_INT);
            $stmt2->bindValue(':facility_id', $facility_id, PDO::PARAM_INT);
            $stmt2->bindValue(':sports_arena_id', $arena_id, PDO::PARAM_INT);
            $stmt2->execute();

            //Retrieving the booking id
            $sql3 = 'SELECT booking.booking_id 
            FROM booking 
            ORDER BY booking.booking_id DESC LIMIT 1';

            $stmt3 = $db->prepare($sql3);
            $stmt3->execute();

            //Assigning the retrieved booking id
            $result3 = $stmt3->fetch(PDO::FETCH_ASSOC);
            $booking_id = $result3['booking_id'];

            //Insert the timeslot details to the booking_timeslot table
            $sql4 = 'INSERT INTO `booking_timeslot`
            (`timeslot_id`, `booking_id`) 
            VALUES (:timeslot_id,:booking_id)';

            $stmt4 = $db->prepare($sql4);
            $stmt4->bindValue(':timeslot_id', $timeslot_id, PDO::PARAM_INT);
            $stmt4->bindValue(':booking_id', $booking_id, PDO::PARAM_INT);
            $stmt4->execute();

            //End transaction
            $db->commit();
            return $arena_id;
        } catch (PDOException $e) {
            $db->rollback();
            throw $e;
        }
    }
    //End of adding items to booking table and cart

    //Start of displaying the cart items
    public static function saAdminCartView($id)
    {
        //Create a new database connection
        $db = static::getDB();

        //Retrieving the cart items
        $sql2 = 'SELECT booking.price_per_booking, booking.booking_id, time_slot.start_time,time_slot.end_time, 
        sports_arena_profile.sa_name, sports_arena_profile.category, sports_arena_profile.location,
        booking.booked_date,booking.payment_method, facility.facility_name
        FROM booking
        INNER JOIN booking_timeslot ON booking.booking_id=booking_timeslot.booking_id
        INNER JOIN time_slot ON booking_timeslot.timeslot_id=time_slot.time_slot_id
        INNER JOIN sports_arena_profile ON booking.sports_arena_id=sports_arena_profile.sports_arena_id
        INNER JOIN facility ON booking.facility_id= facility.facility_id
        WHERE booking_timeslot.security_status="active" AND booking.payment_status="pending"
        AND booking.customer_user_id=:user_id AND DATE(booking.booked_date)=DATE(CURRENT_TIMESTAMP)
        AND TIME(booking.booked_date) + INTERVAL 30 MINUTE > TIME(CURRENT_TIMESTAMP)';

        $stmt = $db->prepare($sql2);
        $stmt->bindValue(':user_id', $id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
        return $result;
    }
    //End of displaying the cart items

    //Start of checking out a visitor
    public static function saAdminAddbookingPaymentSuccess($saAdmin_id, $first_name, $last_name, $primary_contact)
    {
        try {
            //Create a new database connection
            $db = static::getDB();

            //Start transaction
            $db->beginTransaction();

            //Insert the visitor details to the user table
            $sql = 'INSERT INTO `user` 
            (`first_name`,`last_name`,`account_status`,`primary_contact`) 
            VALUES (:first_name,:last_name,:account_status,:primary_contact)';

            $stmt = $db->prepare($sql);
            $account_status = "visitor";
            $stmt->bindValue(':first_name', $first_name, PDO::PARAM_STR);
            $stmt->bindValue(':last_name', $last_name, PDO::PARAM_STR);
            $stmt->bindValue(':account_status', $account_status, PDO::PARAM_STR);
            $stmt->bindValue(':primary_contact', $primary_contact, PDO::PARAM_STR);
            $stmt->execute();

            //Retrieving the user id
            $sql2 = 'SELECT `user_id` 
            FROM `user` 
            ORDER BY `user_id` DESC LIMIT 1;';

            $stmt2 = $db->prepare($sql2);
            $stmt2->execute();

            //Obtaining the user id retrieved from result1
            $result1 = $stmt2->fetch(PDO::FETCH_ASSOC);
            $user_id = $result1["user_id"];

            //Insert into customer table in database
            $sql3 = 'INSERT INTO `customer`
            (`customer_user_id`) 
            VALUES (:customer_user_id);';

            $stmt3 = $db->prepare($sql3);
            $stmt3->bindValue(':customer_user_id', $user_id, PDO::PARAM_INT);
            $stmt3->execute();

            //Initialise payment in payment table
            $sql7 = 'INSERT INTO `payment` 
            (`net_amount`) 
            VALUES (0)';

            $stmt = $db->prepare($sql7);
            $stmt->execute();

            //Retrieving the payment id
            $sql2 = 'SELECT `payment_id` 
            FROM `payment` 
            ORDER BY `payment_id` DESC LIMIT 1;';

            $stmt2 = $db->prepare($sql2);
            $stmt2->execute();

            //Obtaining the payment id retrieved from result1
            $result1 = $stmt2->fetch(PDO::FETCH_ASSOC);
            $payment_id = $result1["payment_id"];

            //Retrieving the booking id
            $sql2 = 'SELECT booking.booking_id
            FROM booking
            INNER JOIN booking_timeslot ON booking.booking_id=booking_timeslot.booking_id
            INNER JOIN time_slot ON booking_timeslot.timeslot_id=time_slot.time_slot_id
            INNER JOIN sports_arena_profile ON booking.sports_arena_id=sports_arena_profile.sports_arena_id
            INNER JOIN facility ON booking.facility_id= facility.facility_id
            WHERE booking_timeslot.security_status="active" AND booking.payment_status="pending"
            AND booking.customer_user_id=:user_id AND DATE(booking.booked_date)=DATE(CURRENT_TIMESTAMP)
            AND TIME(booking.booked_date) + INTERVAL 30 MINUTE > TIME(CURRENT_TIMESTAMP)';

            $stmt = $db->prepare($sql2);
            $stmt->bindValue(':user_id', $saAdmin_id, PDO::PARAM_INT);
            $stmt->execute();

            //Assigning the fetched PDOs to result
            $result = $stmt->fetchAll();

            //Initialize the total amount to 0
            $total_amount = 0;

            //Loop through the result
            $len = count($result);

            for ($x = 0; $x < $len; $x++) {

                //Assigning the booking id to booking_id one by one
                $booking_id = $result[$x][0];

                $sql4 = 'SELECT booking.price_per_booking, booking.booking_date, facility.facility_name, 
                `time_slot`.`start_time`,`time_slot`.`end_time`, sports_arena_profile.sa_name
                FROM booking 
                INNER JOIN `facility` ON `facility`.facility_id = booking.facility_id
                INNER JOIN `sports_arena_profile` ON `sports_arena_profile`.sports_arena_id = `booking`.sports_arena_id
                INNER JOIN `booking_timeslot` ON `booking_timeslot`.`booking_id`= `booking`.`booking_id`
                INNER JOIN `time_slot` ON `time_slot`.`time_slot_id`=`booking_timeslot`.`timeslot_id`
                WHERE booking.booking_id =:booking_id';

                $stmt4 = $db->prepare($sql4);
                $stmt4->bindValue(':booking_id', $booking_id, PDO::PARAM_INT);
                $stmt4->execute();

                //Assigning the fetched PDOs to result
                $result1 = $stmt4->fetch(PDO::FETCH_ASSOC);

                //Assigining the booking's details to variables
                $amount = $result1["price_per_booking"];
                $arena_name = $result1["sa_name"];
                $facility_name = $result1["facility_name"];
                $booking_date = $result1["booking_date"];
                $start_time = $result1["start_time"];
                $end_time = $result1["end_time"];

                //Add the current booking's value to total amount
                $total_amount = $total_amount + $amount;

                //Insert booking details and payment ids into invoice table in database
                $sql5 = 'INSERT INTO `invoice` 
                (`payment_method`, `net_amount`,`payment_id`) 
                VALUES ("cash", :amount, :payment_id)';

                $stmt = $db->prepare($sql5);
                $stmt->bindValue(':amount', $amount, PDO::PARAM_INT);
                $stmt->bindValue(':payment_id', $payment_id, PDO::PARAM_INT);
                $stmt->execute();

                //Retrieving the invoice id
                $sql6 = 'SELECT `invoice_id` 
                FROM `invoice` 
                ORDER BY `invoice_id` DESC LIMIT 1;';

                $stmt6 = $db->prepare($sql6);
                $stmt6->execute();

                //Obtaining the invoice id retrieved from result1
                $result1 = $stmt6->fetch(PDO::FETCH_ASSOC);
                $invoice_id = $result1["invoice_id"];

                //Updating status of the bookings in the database
                $sql = 'UPDATE `booking` 
                SET `payment_status`="paid", `invoice_id`=:invoice_id, `customer_user_id`=:user_id
                WHERE `booking_id`=:booking_id';

                $stmt = $db->prepare($sql);
                $stmt->bindValue(':booking_id', $booking_id, PDO::PARAM_INT);
                $stmt->bindValue(':invoice_id', $invoice_id, PDO::PARAM_INT);
                $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->execute();

                //Start-Function to send booking confirmation SMS to visitor

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
                $text = urlencode("You have successfully made a booking to " . $arena_name . " on " . $booking_date . " from " . $start_time . " to " . $end_time . " for " . $facility_name . ".");

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

            //End-Function to send booking confirmation SMS to visitor

            //Updating the total amount of the payment table 
            $sql = 'UPDATE `payment` 
            SET `net_amount`=:total_amount
            WHERE `payment_id`=:payment_id';

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':total_amount', $total_amount, PDO::PARAM_INT);
            $stmt->bindValue(':payment_id', $payment_id, PDO::PARAM_INT);
            $stmt->execute();

            //End transaction
            $db->commit();

            return ($payment_id);
        } catch (PDOException $e) {
            $db->rollback();
            throw $e;
        }
    }
    //End of checking out a visitor

    // Start of removing a booking from cart
    public static function saAdminClearBooking($booking_id)
    {
        try {
            //Create a new database connection
            $db = static::getDB();

            //Start transaction
            $db->beginTransaction();

            //Removing the booking from booking table
            $sql = 'UPDATE booking 
            SET security_status="inactive" 
            WHERE booking_id=:id';

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':id', $booking_id, PDO::PARAM_INT);
            $stmt->execute();

            //Removing the booking from booking_timeslot table
            $sql = 'UPDATE booking_timeslot 
            SET security_status="inactive" 
            WHERE booking_id=:id';

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':id', $booking_id, PDO::PARAM_INT);
            $stmt->execute();

            //End transaction
            $db->commit();
            return true;
        } catch (PDOException $e) {
            $db->rollback();
            throw $e;
        }
    }
    // End of clearing a booking from cart

    //Start of displaying sports arena's cancel bookings view
    public static function saAdminCancelBookings($id)
    {
        //Create a new database connection
        $db = static::getDB();

        //Retrieving sports arena's current bookings
        $sql = 'SELECT booking.booking_id,booking.price_per_booking,DATE(booking.booked_date) AS booked_date,
        DATE(booking.booking_date) AS booking_date,booking.payment_method,booking.payment_status,
        TIME_FORMAT(time_slot.start_time, "%H" ":" "%i") AS start_time, TIME_FORMAT(time_slot.end_time, "%H" ":" "%i") AS end_time,
        user.primary_contact 
        FROM  booking
        INNER JOIN booking_timeslot ON booking.booking_id = booking_timeslot.booking_id
        INNER JOIN time_slot ON booking_timeslot.timeslot_id=time_slot.time_slot_id
        INNER JOIN user ON user.user_id=booking.customer_user_id
        INNER JOIN administration_staff ON booking.sports_arena_id =administration_staff.sports_arena_id
        WHERE booking.security_status="active" AND administration_staff.user_id=:id AND booking.booking_date>=CURRENT_DATE()
        ORDER BY booking.booking_date DESC';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
        return $result;
    }
    //End of displaying sports arena's cancel bookings view

    //Start of booking cancellation 
    public static function bookingCancellation($booking_id, $user_id, $reason)
    {
        try {
            //Create a new database connection
            $db = static::getDB();

            //Start transaction
            $db->beginTransaction();

            //Retrieving the sports arena id and manager id
            $sql = 'SELECT sports_arena_id, manager_user_id
            FROM administration_staff 
            WHERE user_id =:user_id';

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();

            //Converting retrieved data from database into PDOs
            $result1 = $stmt->fetch(PDO::FETCH_ASSOC);
            $arena_id = $result1["sports_arena_id"];
            $manager_user_id = $result1["manager_user_id"];

            //Retrieving customer id of the booking
            $sql = 'SELECT customer_user_id
            FROM booking 
            WHERE booking_id =:booking_id';

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':booking_id', $booking_id, PDO::PARAM_INT);
            $stmt->execute();

            //Converting retrieved data from database into PDOs
            $result1 = $stmt->fetch(PDO::FETCH_ASSOC);
            $customer_user_id = $result1["customer_user_id"];

            //Adding the cancelled booking to booking cancellation table
            $sql = 'INSERT INTO `booking_cancellation` 
            (`reason`,`manager_sports_arena_id`,`administration_staff_sports_arena_id`,
            `manager_user_id`,`administration_staff_user_id`,`customer_user_id`,`booking_id`) 
            VALUES (:reason,:manager_arena_id,:saAdmin_arena_id,:manager_user_id,:saAdmin_user_id,:customer_user_id,:booking_id)';

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':reason', $reason, PDO::PARAM_STR);
            $stmt->bindValue(':manager_arena_id', $arena_id, PDO::PARAM_INT);
            $stmt->bindValue(':saAdmin_arena_id', $arena_id, PDO::PARAM_INT);
            $stmt->bindValue(':manager_user_id', $manager_user_id, PDO::PARAM_INT);
            $stmt->bindValue(':saAdmin_user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindValue(':customer_user_id', $customer_user_id, PDO::PARAM_INT);
            $stmt->bindValue(':booking_id', $booking_id, PDO::PARAM_INT);
            $stmt->execute();

            //Removing the booking from booking table
            $sql = 'UPDATE booking 
            SET security_status="inactive" 
            WHERE booking_id=:booking_id';

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':booking_id', $booking_id, PDO::PARAM_INT);
            $stmt->execute();

            //Removing the booking from booking_timeslot table
            $sql = 'UPDATE booking_timeslot 
            SET security_status="inactive" 
            WHERE booking_id=:booking_id';

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':booking_id', $booking_id, PDO::PARAM_INT);
            $stmt->execute();

            //End transaction
            $db->commit();
            return (true);
        } catch (PDOException $e) {
            $db->rollback();
            throw $e;
        }
    }
    //End of booking cancellation 

    //Start of displaying sports arena's booking payment view
    public static function saAdminBookingPayment($id)
    {
        //Create a new database connection
        $db = static::getDB();

        //Retrieving sports arena's cash-unpaid bookings
        $sql = 'SELECT booking.booking_id,booking.price_per_booking,DATE(booking.booking_date) AS booking_date,
        booking.payment_method,booking.payment_status,TIME_FORMAT(time_slot.start_time, "%H" ":" "%i") AS start_time,
        TIME_FORMAT(time_slot.end_time, "%H" ":" "%i") AS end_time,time_slot.price 
        FROM  booking
        INNER JOIN booking_timeslot ON booking.booking_id = booking_timeslot.booking_id
        INNER JOIN time_slot ON booking_timeslot.timeslot_id=time_slot.time_slot_id
        INNER JOIN user ON user.user_id=booking.customer_user_id
        INNER JOIN administration_staff ON booking.sports_arena_id =administration_staff.sports_arena_id
        WHERE booking.security_status="active" AND booking.payment_method="cash" AND
        booking.payment_status="unpaid" AND administration_staff.user_id=:id';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
        return $result;
    }
    //End of displaying sports arena's booking payment view

    //Start of getting payment for cash bookings
    public static function updateBookingPayment($booking_id)
    {
        //Create a new database connection
        $db = static::getDB();

        //Updating status of the bookings in the database
        $sql = 'UPDATE `booking` 
        SET `payment_status`="paid" 
        WHERE `booking_id`=:booking_id';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':booking_id', $booking_id, PDO::PARAM_INT);

        //Need to update invoice table and payment table too
        return ($stmt->execute());
    }
    //End of getting payment for cash bookings
    //End of manage bookings

    /***************************************************************************************************/

    //Start of displaying notifications 
    public static function saAdminNotification($id)
    {
        //Create a new database connection
        $db = static::getDB();

        //Retrieving notifications from the database
        $sql = 'SELECT subject,description, DATE(date) as date , 
        TIME_FORMAT( TIME(date) ,"%H" ":" "%i") as time 
        FROM notification 
        WHERE user_id=:id 
        ORDER BY notification.date DESC';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
        return $result;
    }
    //End of displaying notifications 

    /***************************************************************************************************/

    //Start of manage timeslots
    //Start of displaying sports arenas timeslots
    public static function saAdminViewTimeSlots($id)
    {
        //Create a new database connection
        $db = static::getDB();

        //Retrieving sa Admin's timeslots to view from the database
        $sql = 'SELECT time_slot.time_slot_id, TIME_FORMAT(time_slot.start_time, "%H" ":" "%i") AS startTime, 
        TIME_FORMAT(time_slot.end_time, "%H" ":" "%i") AS endTime,time_slot.price, facility.facility_id, 
        facility.facility_name 
        FROM time_slot 
        INNER JOIN facility ON time_slot.facility_id = facility.facility_id
        INNER JOIN administration_staff ON time_slot.manager_user_id=administration_staff.manager_user_id
        WHERE administration_staff.user_id=:id AND time_slot.security_status="active"
        ORDER BY  startTime ASC';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
        return $result;
    }
    //End of displaying sports arenas timeslots

    //Start of checking existing timeslots in sports arena
    public static function CheckExistingTimeslots($user_id, $start_time, $duration, $price, $facility)
    {
        try {
            //Create a new database connection
            $db = static::getDB();

            //Start transaction
            $db->beginTransaction();

            // Changing start_time variable to hh:mm:ss format
            $start_time = (string)($start_time . ":00");
            $hours = (int)substr($start_time, 0, 2);
            $minutes = (int)substr($start_time, 3, 2);

            $end_time = $hours + $duration;

            // If end_time is less than 10:00, add a zero before the hh:mm:ss time format. Else just change it to hh:mm:ss
            if ($end_time < 10) {
                $end_time = (string)("0" . $end_time . ":" . $minutes . ":00");
            } else {
                $end_time = (string)($end_time . ":" . $minutes . ":00");
            }

            //Retrieving sports arena from  user id
            $sql = 'SELECT sports_arena_id 
            FROM administration_staff
            WHERE administration_staff.user_id=:user_id';

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
            $stmt->execute();

            //Assigning the fetched PDOs to result
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $arena_id = $result["sports_arena_id"];

            $sql = 'SELECT * 
            FROM  time_slot
            INNER JOIN facility ON time_slot.facility_id=facility.facility_id
            WHERE time_slot.manager_sports_arena_id=:arena_id AND time_slot.facility_id=:facility
            AND facility.security_status="active" AND time_slot.security_status="active"
            ORDER BY end_time ASC';

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':facility', $facility, PDO::PARAM_STR);
            $stmt->bindValue(':arena_id', $arena_id, PDO::PARAM_INT);
            $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
            $stmt->execute();

            // Assigning each database row to a variable
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                /*If input start time is between database timeslot range excluding end time and 
                If input end time is between database timeslot range excluding start time*/
                // strtotime is used to convert string to time. So times can be compared
                if ((strtotime($row["end_time"]) > strtotime($start_time) && strtotime($row["start_time"]) <= strtotime($start_time)) || (strtotime($row["end_time"]) >= strtotime($end_time) && strtotime($row["start_time"]) < strtotime($end_time))) {
                    return false;
                }
            }
            //End transaction
            $db->commit();

            // If Timeslot can be inserted, return true
            return true;
        } catch (PDOException $e) {
            $db->rollback();
            throw $e;
        }
    }
    //End of checking existing timeslots in sports arena

    //Start of displaying existing sports arenas to add a timeslot
    public static function saAdminGetFacilityName($id)
    {
        //Create a new database connection
        $db = static::getDB();

        //Retrieving the existing facilities from the database
        $sql = 'SELECT facility.facility_id, facility.facility_name
         FROM facility
         INNER JOIN administration_staff ON facility.manager_sports_arena_id=administration_staff.manager_sports_arena_id
         WHERE administration_staff.user_id=:id AND facility.security_status="active"
         ORDER BY facility.facility_name';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
        return $result;
    }
    //End of displaying existing sports arenas to add a timeslot

    //Start of adding timeslot to a sports arena for administration staff
    public static function saAdminAddTimeSlots($id, $start_time, $duration, $amount, $fid)
    {
        try {
            //Create a new database connection
            $db = static::getDB();

            //Start transaction
            $db->beginTransaction();

            //Assigning the start time
            $hours = (int)substr($start_time, 0, 2);
            $minutes = (int)substr($start_time, 3, 5);

            //Assigning the end time
            $end_time = $hours + $duration;
            $end_time = (string)($end_time . ":" . $minutes);

            // select query to select sports arena id and manager id
            $sql1 = 'SELECT manager_user_id, manager_sports_arena_id 
            FROM administration_staff 
            WHERE user_id = :id';

            $stmt1 = $db->prepare($sql1);
            $stmt1->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt1->execute();

            //Assigning the fetched PDOs to result
            $result1 = $stmt1->fetch(PDO::FETCH_ASSOC);
            $mid = $result1["manager_user_id"];
            $arena_id = $result1["manager_sports_arena_id"];

            // Add time slot to the database
            $sql2 = 'INSERT INTO `time_slot`
            (`start_time`,`end_time`,`price`,`facility_id`,`manager_user_id`,`manager_sports_arena_id`)
            VALUES (:stime, :etime, :amount, :fid, :mid, :arena_id)';

            $stmt2 = $db->prepare($sql2);
            $stmt2->bindValue(':stime', $start_time, PDO::PARAM_STR);
            $stmt2->bindValue(':etime', $end_time, PDO::PARAM_STR);
            $stmt2->bindValue(':amount', $amount, PDO::PARAM_STR);
            $stmt2->bindValue(':fid', $fid, PDO::PARAM_INT);
            $stmt2->bindValue(':mid', $mid, PDO::PARAM_INT);
            $stmt2->bindValue(':arena_id', $arena_id, PDO::PARAM_INT);
            $stmt2->execute();

            //Retrieving timeslot id
            $sql3 = 'SELECT time_slot_id 
            FROM time_slot 
            ORDER BY time_slot_id DESC LIMIT 1';

            $stmt3 = $db->prepare($sql3);
            $stmt3->execute();

            //Assigning the fetched PDOs to result
            $result3 = $stmt3->fetch(PDO::FETCH_ASSOC);
            $time_slot_id = $result3['time_slot_id'];

            //Insert into administration_staff_manages_time_slot table
            $sql3 = 'INSERT INTO administration_staff_manages_time_slot 
            (`time_slot_id`,`administration_staff_user_id`,`administration_staff_sports_arena_id`)
            VALUES (:time_slot_id,:user_id,:arena_id)';

            $stmt3 = $db->prepare($sql3);
            $stmt3->bindValue(':time_slot_id', $time_slot_id, PDO::PARAM_INT);
            $stmt3->bindValue(':user_id', $id, PDO::PARAM_INT);
            $stmt3->bindValue(':arena_id', $arena_id, PDO::PARAM_INT);
            $stmt3->execute();

            //End transaction
            $db->commit();

            //Return timeslot id
            return ($time_slot_id);
        } catch (PDOException $e) {
            $db->rollback();
            throw $e;
        }
    }
    //End of adding timeslot to a sports arena for administration staff

    //Start of displaying available timeslots to delete
    public static function saAdminDeleteTimeSlots($current_user, $timeslot_id)
    {
        try {
            //Create a new database connection
            $db = static::getDB();

            //Start transaction
            $db->beginTransaction();

            //Obtaining current user's id
            $id = $current_user->user_id;

            //Updating the timeslot table from the database
            $sql = 'UPDATE time_slot 
            SET time_slot.security_status="inactive"
            WHERE time_slot.time_slot_id=:timeslot_id';

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':timeslot_id', $timeslot_id, PDO::PARAM_INT);
            $stmt->execute();

            //Updating the administration_staff_manages timeslot table from the database
            $sql = 'UPDATE administration_staff_manages_time_slot
            SET administration_staff_manages_time_slot.administration_staff_user_id=:id
            WHERE administration_staff_manages_time_slot.time_slot_id=:timeslot_id';

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':timeslot_id', $timeslot_id, PDO::PARAM_INT);
            $stmt->execute();

            //Selecting future customer bookings made with this timeslot
            $sql = 'SELECT booking.customer_user_id, booking.booking_id, time_slot.manager_user_id,
            time_slot.manager_sports_arena_id,administration_staff_manages_time_slot.administration_staff_user_id
            FROM booking 
            INNER JOIN booking_timeslot ON booking.booking_id=booking_timeslot.booking_id
            INNER JOIN time_slot ON booking_timeslot.timeslot_id=time_slot.time_slot_id
            INNER JOIN administration_staff_manages_time_slot ON booking_timeslot.timeslot_id=administration_staff_manages_time_slot.time_slot_id
            WHERE time_slot.time_slot_id=:timeslot_id AND booking.booking_date > (SELECT NOW())';

            $data_stmt = $db->prepare($sql);
            $data_stmt->bindValue(':timeslot_id', $timeslot_id, PDO::PARAM_INT);
            $data_stmt->execute();

            //Assigning the fetched PDOs to result
            $data = $data_stmt->fetchAll(PDO::FETCH_BOTH);

            //If there are any future bookings with this timeslot
            if ($data != null) {

                //Count of number of bookings
                $len = count($data);

                //Obtain manager id, administration id and arena id
                $manager_user_id = $data[0][2];
                $manager_arena_id = $data[0][3];
                $admin_user_id = $data[0][4];

                for ($x = 0; $x < $len; $x++) {

                    //Obtain customer id and booking id
                    $customer_user_id = $data[$x][0];
                    $booking_id = $data[$x][1];

                    //Removing booking from booking timeslot
                    $sql = 'UPDATE booking_timeslot 
                    SET booking_timeslot.security_status="inactive"
                    WHERE booking_timeslot.booking_id=:booking_id';

                    $stmt = $db->prepare($sql);
                    $stmt->bindValue(':booking_id', $booking_id, PDO::PARAM_INT);
                    $stmt->execute();

                    //Removing booking from booking table
                    $sql = 'UPDATE booking
                    SET booking_timeslot.security_status="inactive"
                    WHERE booking_timeslot.booking_id=:booking_id';

                    $stmt = $db->prepare($sql);
                    $stmt->bindValue(':booking_id', $booking_id, PDO::PARAM_INT);
                    $stmt->execute();

                    //Reason for booking cancellation
                    $reason = "Removal of timeslot from the sports arena";

                    //Insert cancelled booking into booking cancellation table
                    $sql3 = 'INSERT INTO booking_cancellation 
                    (`reason`,manager_sports_arena_id,`administration_staff_sports_arena_id`, 
                    manager_user_id,`administration_staff_user_id`, customer_user_id, booking_id)
                    VALUES (:reason, :manager_arena_id,:admin_arena_id, :manager_user_id, :admin_user_id, 
                    :customer_user_id, :booking_id)';

                    $stmt3 = $db->prepare($sql3);
                    $stmt3->bindValue(':reason', $reason, PDO::PARAM_STR);
                    $stmt3->bindValue('manager_arena_id', $manager_arena_id, PDO::PARAM_INT);
                    $stmt3->bindValue(':admin_arena_id', $manager_arena_id, PDO::PARAM_INT);
                    $stmt3->bindValue(':manager_user_id', $manager_user_id, PDO::PARAM_INT);
                    $stmt3->bindValue(':admin_user_id', $admin_user_id, PDO::PARAM_INT);
                    $stmt3->bindValue(':customer_user_id', $customer_user_id, PDO::PARAM_INT);
                    $stmt3->bindValue(':booking_id', $booking_id, PDO::PARAM_INT);
                    $stmt3->execute();

                    //Sending cancellation notification to customer
                    NotificationModel::customerBookingCancellationDeleteTimeslotNotification($timeslot_id);
                }
            }

            //Sending timeslot delete notification to sports arena staff
            NotificationModel::arenaDeleteTimeslotNotification($current_user, $timeslot_id);

            //End transaction
            $db->commit();
            return true;
        } catch (PDOException $e) {
            $db->rollback();
            throw $e;
        }
    }
    //End of displaying sports arenas deleting the timeslots for administrationstaff

    //End of manage timeslot
    /***************************************************************************************************/

    //Start of manage facility
    //Start of displaying sports arenas facilities for administrationstaff
    public static function saAdminViewFacility($id)
    {
        //Create a new database connection
        $db = static::getDB();

        $sql = 'SELECT *  
        FROM facility 
        INNER JOIN administration_staff ON facility.manager_user_id=administration_staff.manager_user_id
        WHERE  administration_staff.user_id=:id AND facility.security_status="active"';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
        return $result;
    }
    //End of displaying sports arenas facilities for administrationstaff

    //Start of adding a facility to sports arena for administartion staff
    public static function saAdminAddFacility($user_id, $facility)
    {
        try {

            //Create a new database connection
            $db = static::getDB();

            //Start a transaction
            $db->beginTransaction();

            // Retrieving sports arena id and manager id
            $sql = 'SELECT sports_arena_id, manager_user_id, manager_sports_arena_id 
            FROM administration_staff
            WHERE administration_staff.user_id=:user_id';

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
            $stmt->execute();

            //Assigning the fetched PDOs to result
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $arena_id = $result["sports_arena_id"];
            $manager_id = $result["manager_user_id"];
            $manager_arena_id = $result["manager_sports_arena_id"];

            // Insert a facility
            $sql1 = 'INSERT INTO `facility`
            (`facility_name`,`sports_arena_id`,`manager_user_id`,`manager_sports_arena_id`)
            VALUES (:facility,:arena_id,:manager_user_id,:manager_sports_arena_id)';

            $stmt = $db->prepare($sql1);
            $stmt->bindValue(':facility', $facility, PDO::PARAM_STR);
            $stmt->bindValue(':arena_id', $arena_id, PDO::PARAM_INT);
            $stmt->bindValue(':manager_user_id', $manager_id, PDO::PARAM_INT);
            $stmt->bindValue(':manager_sports_arena_id', $manager_arena_id, PDO::PARAM_INT);
            $stmt->execute();

            //Retrieve the inserted facility id
            $sql2 = 'SELECT facility_id
            FROM facility
            ORDER BY facility_id DESC LIMIT 1';

            $stmt = $db->prepare($sql2);
            $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
            $stmt->execute();

            //Assigning the fetched PDOs to result
            $result2 = $stmt->fetch(PDO::FETCH_ASSOC);
            $facility_id = $result2['facility_id'];

            //Insert facility insertion into administration staff manages facility
            $sql3 = 'INSERT INTO `administration_staff_manages_facility`
            (`facility_id`,`administration_staff_user_id`,`administration_staff_sports_arena_id`)
            VALUES (:facility_id,:user_id,:arena_id)';

            $stmt = $db->prepare($sql3);
            $stmt->bindValue(':facility_id', $facility_id, PDO::PARAM_INT);
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindValue(':arena_id', $arena_id, PDO::PARAM_INT);
            $stmt->execute();

            //End transaction
            $db->commit();

            //Return the facility id of inserted facility
            return ($facility_id);
        } catch (PDOException $e) {
            $db->rollback();
            throw $e;
        }
    }
    //End of adding facility to sports arena for administartion staff

    //Start of checking whether a facility exists with same name in the sports arena
    public static function findFacilityByName($id, $fname)
    {
        try {
            //Create a new database connection
            $db = static::getDB();

            //Start transaction
            $db->beginTransaction();

            //Retrieving sports arena id
            $sql = 'SELECT sports_arena_id 
            FROM administration_staff 
            WHERE administration_staff.user_id=:id';

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
            $stmt->execute();

            //Assigning the fetched PDOs to result
            $result1 = $stmt->fetch(PDO::FETCH_ASSOC);
            $arena_id = $result1["sports_arena_id"];

            //Retrieving facility names
            $sql = 'SELECT facility_name  
            FROM facility
            WHERE LOWER(facility.facility_name) = LOWER(:fname) AND facility.sports_arena_id=:arena_id AND security_status="active"';

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':fname', $fname, PDO::PARAM_STR);
            $stmt->bindValue(':arena_id', $arena_id, PDO::PARAM_INT);
            $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
            $stmt->execute();

            //Assigning the fetched PDOs to result
            $result2 = $stmt->fetch(PDO::FETCH_ASSOC);
            $facility_name = $result2['facility_name'];

            //End transaction
            $db->commit();

            //If there's no sports arena with the same name
            if (empty($facility_name)) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            $db->rollback();
            throw $e;
        }
    }
    //End of checking whether a facility exists with same name in the sports arena

    //Start of displaying sports arenas facilities update for administration staff
    public static function saAdminUpdateFacility($current_user, $facility_id, $facility_name)
    {
        try {

            //Create a new database connection
            $db = static::getDB();

            //Start transaction
            $db->beginTransaction();

            //Retrieve current facility name 
            $sql = 'SELECT facility.facility_name 
            FROM facility 
            WHERE facility.facility_id=:facility_id AND facility.security_status="active"';

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':facility_id', $facility_id, PDO::PARAM_INT);
            $stmt->execute();

            //Assigning the fetched PDOs to result
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            $old_facility_name = $data["facility_name"];
            $id = $current_user->user_id;

            //Updating the facility name
            $sql = 'UPDATE facility 
            SET facility.facility_name=:facility_name
            WHERE facility.facility_id=:facility_id';

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':facility_name', $facility_name, PDO::PARAM_STR);
            $stmt->bindValue(':facility_id', $facility_id, PDO::PARAM_INT);
            $stmt->execute();

            //Updating the administration staff manages facility table
            $sql = 'UPDATE administration_staff_manages_facility 
            SET administration_staff_manages_facility.administration_staff_user_id=:id
            WHERE administration_staff_manages_facility.facility_id=:facility_id';

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':facility_id', $facility_id, PDO::PARAM_INT);
            $stmt->execute();

            //Update facility notification to sports arena staff
            $success = NotificationModel::saAdminUpdatefacilitySuccessNotification($current_user, $old_facility_name, $facility_name, $facility_id);

            //End transaction
            $db->commit();

            //If notification is successful
            if ($success) {
                return true;
            }
        } catch (PDOException $e) {
            $db->rollback();
            throw $e;
        }
    }
    //End of displaying sports arenas facilities update for administration staff

    //Start of Checking whether any other facility exists with the new name other than the current facility
    public static function findFacilityExcludeByName($id, $facility_id, $fname)
    {
        try {
            //Create a new database connection
            $db = static::getDB();

            //Start transaction
            $db->beginTransaction();

            //Retrieving sports arena id
            $sql = 'SELECT sports_arena_id 
            FROM administration_staff 
            WHERE administration_staff.user_id=:id';

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
            $stmt->execute();

            //Assigning the fetched PDOs to result
            $result1 = $stmt->fetch(PDO::FETCH_ASSOC);
            $arena_id = $result1["sports_arena_id"];

            //Retrieving all the facility names except the current one's name
            $sql = 'SELECT facility_name  
            FROM facility 
            WHERE LOWER(facility.facility_name) = LOWER(:fname) AND facility.sports_arena_id =:arena_id
            AND facility.facility_id <> :facility_id AND security_status="active"';

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':fname', $fname, PDO::PARAM_STR);
            $stmt->bindValue(':facility_id', $facility_id, PDO::PARAM_INT);
            $stmt->bindValue(':arena_id', $arena_id, PDO::PARAM_INT);
            $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
            $stmt->execute();

            //Assigning the fetched PDOs to result
            $result2 = $stmt->fetch(PDO::FETCH_ASSOC);
            $facility_name = $result2['facility_name'];

            //End transaction
            $db->commit();

            //If there are facilities
            if (!empty($facility_name)) {
                return true;
            } else {
                return false;
            }
        } catch (PDOException $e) {
            $db->rollback();
            throw $e;
        }
    }
    // End of Checking whether any other facility exists with the new name other than the current facility

    //Start of Removing a facility from the sports arena
    public static function saAdminDeleteFacility($current_user, $facility_id)
    {
        try {

            //Create a new database connection
            $db = static::getDB();

            //Start transaction
            $db->beginTransaction();
            $id = $current_user->user_id;

            //Retrieve all the timeslots made on this facility
            $sql = 'SELECT time_slot.time_slot_id 
            FROM time_slot
            INNER JOIN facility ON facility.facility_id = time_slot.facility_id
            WHERE  time_slot.facility_id=:facility_id AND facility.security_status="active"';

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':facility_id', $facility_id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetchAll(PDO::FETCH_BOTH);

            //Assign the number of timeslots
            $len = count($result);

            //Removing the facility
            $sql = 'UPDATE facility 
            SET facility.security_status="inactive"
            WHERE facility.facility_id=:facility_id';

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':facility_id', $facility_id, PDO::PARAM_INT);
            $stmt->execute();

            //Updating the administration_staff_manages_facility table
            $sql = 'UPDATE administration_staff_manages_facility 
            SET administration_staff_manages_facility.administration_staff_user_id=:id
            WHERE administration_staff_manages_facility.facility_id=:facility_id';

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->bindValue(':facility_id', $facility_id, PDO::PARAM_INT);
            $stmt->execute();

            //If there are timeslots added with the facility
            if ($result != null) {

                for ($y = 0; $y < $len; $y++) {
                    $timeslot_id = $result[$y][0];

                    //Deleting the timeslots respected to that facility
                    $sql = 'UPDATE time_slot 
                    SET time_slot.security_status="inactive"
                    WHERE time_slot.time_slot_id=:timeslot_id';

                    $stmt = $db->prepare($sql);
                    $stmt->bindValue(':timeslot_id', $timeslot_id, PDO::PARAM_INT);
                    $stmt->execute();

                    //Updating the administration_staff_manages_timeslot table
                    $sql = 'UPDATE administration_staff_manages_time_slot
                    SET administration_staff_manages_time_slot.administration_staff_user_id=:id
                    WHERE administration_staff_manages_time_slot.time_slot_id=:timeslot_id';

                    $stmt = $db->prepare($sql);
                    $stmt->bindValue(':id', $id, PDO::PARAM_INT);
                    $stmt->bindValue(':timeslot_id', $timeslot_id, PDO::PARAM_INT);
                    $stmt->execute();

                    $sql = 'SELECT booking.customer_user_id, booking.booking_id, time_slot.manager_user_id,
                    time_slot.manager_sports_arena_id,administration_staff_manages_time_slot.administration_staff_user_id
                    FROM booking 
                    INNER JOIN booking_timeslot ON booking.booking_id=booking_timeslot.booking_id
                    INNER JOIN time_slot ON booking_timeslot.timeslot_id=time_slot.time_slot_id
                    INNER JOIN administration_staff_manages_time_slot ON booking_timeslot.timeslot_id=administration_staff_manages_time_slot.time_slot_id
                    WHERE time_slot.time_slot_id=:timeslot_id AND booking.booking_date > (SELECT NOW())';

                    $data_stmt = $db->prepare($sql);
                    $data_stmt->bindValue(':timeslot_id', $timeslot_id, PDO::PARAM_INT);
                    $data_stmt->execute();

                    //Assigning the fetched PDOs to result
                    $data = $data_stmt->fetchAll(PDO::FETCH_BOTH);

                    //If there's any bookings made on the deleted facility
                    if ($data != null) {

                        //Assigning the number of future bookings
                        $len = count($data);

                        //Obtaining manager id, administration_staff_user_id and arena id
                        $manager_user_id = $data[0][2];
                        $manager_arena_id = $data[0][3];
                        $admin_user_id = $data[0][4];

                        for ($x = 0; $x < $len; $x++) {

                            //Obtaining customer id and booking id of each booking
                            $customer_user_id = $data[$x][0];
                            $booking_id = $data[$x][1];

                            //Removing booking from booking timeslot table
                            $sql = 'UPDATE booking_timeslot 
                            SET booking_timeslot.security_status="inactive"
                            WHERE booking_timeslot.booking_id=:booking_id';

                            $stmt = $db->prepare($sql);
                            $stmt->bindValue(':booking_id', $booking_id, PDO::PARAM_INT);
                            $stmt->execute();

                            //Removing booking from booking table
                            $sql = 'UPDATE booking
                            SET booking_timeslot.security_status="inactive"
                            WHERE booking_timeslot.booking_id=:booking_id';

                            $stmt = $db->prepare($sql);
                            $stmt->bindValue(':booking_id', $booking_id, PDO::PARAM_INT);
                            $stmt->execute();

                            //Reason for booking cancellation
                            $reason = "Removal of facility and its timeslots from the sports arena";


                            $sql3 = 'INSERT INTO booking_cancellation 
                            (`reason`,manager_sports_arena_id,`administration_staff_sports_arena_id`, 
                            manager_user_id,`administration_staff_user_id`, customer_user_id, booking_id)
                            VALUES (:reason, :manager_arena_id,:admin_arena_id, :manager_user_id, :admin_user_id,
                             :customer_user_id, :booking_id)';

                            $stmt3 = $db->prepare($sql3);
                            $stmt3->bindValue(':reason', $reason, PDO::PARAM_STR);
                            $stmt3->bindValue('manager_arena_id', $manager_arena_id, PDO::PARAM_INT);
                            $stmt3->bindValue(':admin_arena_id', $manager_arena_id, PDO::PARAM_INT);
                            $stmt3->bindValue(':manager_user_id', $manager_user_id, PDO::PARAM_INT);
                            $stmt3->bindValue(':admin_user_id', $admin_user_id, PDO::PARAM_INT);
                            $stmt3->bindValue(':customer_user_id', $customer_user_id, PDO::PARAM_INT);
                            $stmt3->bindValue(':booking_id', $booking_id, PDO::PARAM_INT);
                            $stmt3->execute();

                            //Sending booking cancellation notification to customers
                            NotificationModel::customerBookingCancellationDeleteFacilityNotification($timeslot_id);
                        }
                    }
                }
            }

            //End transaction
            $db->commit();
            return true;
        } catch (PDOException $e) {
            $db->rollback();
            throw $e;
        }
    }
    //End of Removing a facility from the sports arena
//End of manage facility
    /***************************************************************************************************/
}
