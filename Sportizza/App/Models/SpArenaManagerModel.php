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
    //Start of displaying sports arena timeslot
    public static function managerViewAvailableTimeSlots($manager_id)
    {
        try {
            $db = static::getDB();
            $db->beginTransaction();


            $sql = 'SELECT sports_arena_id FROM manager WHERE user_id=:user_id';


            $stmt = $db->prepare($sql);
            //Binding the sports arena id and Converting retrieved data from database into PDOs
            $stmt->bindValue(':user_id', $manager_id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $arena_id = $result['sports_arena_id'];

            //Retrieving sports arena timeslot from the database
            $sql = 'SELECT DISTINCT time_slot.time_slot_id,TIME_FORMAT(time_slot.start_time, "%H:%i")
        AS startTime,TIME_FORMAT(time_slot.end_time, "%H:%i") AS endTime,
        time_slot.price,facility.facility_name
        FROM time_slot
        INNER JOIN facility ON time_slot.facility_id= facility.facility_id
        INNER JOIN booking_timeslot ON time_slot.time_slot_id =booking_timeslot.timeslot_id
        INNER JOIN booking ON booking_timeslot.booking_id=booking.booking_id
        
        WHERE time_slot.time_slot_id NOT IN
         (SELECT booking_timeslot.timeslot_id FROM booking 
        INNER JOIN booking_timeslot ON booking.booking_id=booking_timeslot.booking_id WHERE 
        ((booking.booking_date=CURRENT_DATE()) OR (payment_status="pending" 
         AND booked_date +INTERVAL 30 MINUTE > CURRENT_TIMESTAMP))
         AND booking_timeslot.security_status="active")
         AND time_slot.manager_sports_arena_id=:arena_id 
         AND time_slot.security_status="active" 
         AND time_slot.start_time > CURRENT_TIME() 
         GROUP BY time_slot.time_slot_id
         ORDER BY time_slot.start_time';
            // payment_status="pending"

            $stmt = $db->prepare($sql);

            //Binding the sports arena id and Converting retrieved data from database into PDOs
            $stmt->bindValue(':arena_id', $arena_id, PDO::PARAM_INT);
            $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
            $stmt->execute();

            //Assigning the fetched PDOs to result
            $result = $stmt->fetchAll();
            $db->commit();
            return $result;
        } catch (PDOException $e) {
            $db->rollback();
            throw $e;
        }
    }
    //End of Displaying sports arena timeslot
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

    public static function managerSearchTimeSlotsDate($manager_id, $date)
    {
        try {
            $db = static::getDB();
            $db->beginTransaction();
            $sql = 'SELECT sports_arena_id FROM manager WHERE user_id=:user_id';

        
            $stmt = $db->prepare($sql);
            //Binding the sports arena id and Converting retrieved data from database into PDOs
            $stmt->bindValue(':user_id', $manager_id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            $arena_id = $result['sports_arena_id'];

            // have to change this is wrong we use it for testing

            $current_date = date('Y-m-d');

            if ($date != $current_date) {
                // echo($date);
                //Retrieving sports arena timeslot from the database
                $sql = 'SELECT DISTINCT time_slot.time_slot_id,TIME_FORMAT(time_slot.start_time, "%H:%i")
            AS startTime,TIME_FORMAT(time_slot.end_time, "%H:%i") AS endTime,
            time_slot.price,facility.facility_name,sports_arena_profile.payment_method
            FROM time_slot
            INNER JOIN facility ON time_slot.facility_id= facility.facility_id
            INNER JOIN sports_arena_profile ON facility.sports_arena_id= sports_arena_profile.sports_arena_id
            WHERE time_slot.time_slot_id NOT IN
            (SELECT booking_timeslot.timeslot_id FROM booking 
            INNER JOIN booking_timeslot ON booking.booking_id=booking_timeslot.booking_id WHERE 
            ((booking.booking_date=:date) OR (payment_status="pending" 
            AND booked_date +INTERVAL 30 MINUTE > CURRENT_TIMESTAMP))
            AND booking_timeslot.security_status="active")
            AND time_slot.manager_sports_arena_id=:arena_id
            AND time_slot.security_status="active"
            GROUP BY time_slot.time_slot_id
            ORDER BY time_slot.start_time;';




                $stmt = $db->prepare($sql);

                //Binding the sports arena id and Converting retrieved data from database into PDOs
                $stmt->bindValue(':date', $date, PDO::PARAM_STR);
                $stmt->bindValue(':arena_id', $arena_id, PDO::PARAM_INT);
            } else {
                $sql = 'SELECT DISTINCT time_slot.time_slot_id,TIME_FORMAT(time_slot.start_time, "%H:%i")
                AS startTime,TIME_FORMAT(time_slot.end_time, "%H:%i") AS endTime,
                time_slot.price,facility.facility_name
                FROM time_slot
                INNER JOIN facility ON time_slot.facility_id= facility.facility_id
                INNER JOIN booking_timeslot ON time_slot.time_slot_id =booking_timeslot.timeslot_id
                INNER JOIN booking ON booking_timeslot.booking_id=booking.booking_id
                
                WHERE time_slot.time_slot_id NOT IN
                 (SELECT booking_timeslot.timeslot_id FROM booking 
                INNER JOIN booking_timeslot ON booking.booking_id=booking_timeslot.booking_id WHERE 
                ((booking.booking_date=CURRENT_DATE()) OR (payment_status="pending" 
                 AND booked_date +INTERVAL 30 MINUTE > CURRENT_TIMESTAMP))
                 AND booking_timeslot.security_status="active")
                 AND time_slot.manager_sports_arena_id=:arena_id 
                 AND time_slot.security_status="active" 
                 AND time_slot.start_time > CURRENT_TIME() 
              GROUP BY time_slot.time_slot_id
                 ORDER BY time_slot.start_time';

                $stmt = $db->prepare($sql);

                //Binding the sports arena id and Converting retrieved data from database into PDOs
                // $stmt->bindValue(':date', $date, PDO::PARAM_STR);
                $stmt->bindValue(':arena_id', $arena_id, PDO::PARAM_INT);
            }




            $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
            $stmt->execute();
            $db->commit();
        } catch (PDOException $e) {
            $db->rollback();
            throw $e;
        }

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
    //End of Displaying sports arena timeslot

    
    public static function managerAddToCart($manager_id, $timeslot_id, $booking_date, $payment_method)
    {
        try {
            $db = static::getDB();
            $db->beginTransaction();
            $sql = 'SELECT time_slot.start_time, time_slot.end_time,
    time_slot.price,time_slot.facility_id,time_slot.manager_sports_arena_id
    FROM time_slot   
    WHERE time_slot.security_status="active"
    AND time_slot.time_slot_id=:timeslot_id';

            // get database connection
            $db = static::getDB();
            $stmt = $db->prepare($sql);

            //Binding the customer id and Converting retrieved data from database into PDOs
            $stmt->bindValue(':timeslot_id', $timeslot_id, PDO::PARAM_INT);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $price = $result['price'];
            $facility_id = $result['facility_id'];
            $arena_id = $result['manager_sports_arena_id'];
            //Assigning the fetched PDOs to result

            //insert query for add feedbacks
            $sql2 = 'INSERT INTO `booking`(`booking_date`,`customer_user_id`, 
    `payment_method`, `price_per_booking`, `facility_id`, 
    `sports_arena_id`) VALUES 
    (:booking_date,:customer_user_id,:payment_method,:price,:facility_id,
    :sports_arena_id)';

            // get database connection
            $stmt2 = $db->prepare($sql2);
            //Binding the customer id and Converting retrieved data from database into PDOs
            $stmt2->bindValue(':customer_user_id', $manager_id, PDO::PARAM_INT);
            $stmt2->bindValue(':booking_date', $booking_date, PDO::PARAM_STR);
            $stmt2->bindValue(':payment_method', $payment_method, PDO::PARAM_STR);
            $stmt2->bindValue(':price', $price, PDO::PARAM_INT);
            $stmt2->bindValue(':facility_id', $facility_id, PDO::PARAM_INT);
            $stmt2->bindValue(':sports_arena_id', $arena_id, PDO::PARAM_INT);
            $stmt2->execute();

            $sql3 = 'SELECT booking.booking_id from booking ORDER BY booking.booking_id DESC LIMIT 1';
            $stmt3 = $db->prepare($sql3);
            $stmt3->execute();
            $result3 = $stmt3->fetch(PDO::FETCH_ASSOC);
            $booking_id = $result3['booking_id'];

            $sql4 = 'INSERT INTO `booking_timeslot`(`timeslot_id`, `booking_id`) VALUES 
        (:timeslot_id,:booking_id)';

            // get database connection
            $stmt4 = $db->prepare($sql4);
            //Binding the timeslot id and booking id Converting retrieved data from database into PDOs

            $stmt4->bindValue(':timeslot_id', $timeslot_id, PDO::PARAM_INT);
            $stmt4->bindValue(':booking_id', $booking_id, PDO::PARAM_INT);
            $stmt4->execute();

            $db->commit();
            return $arena_id;
        } catch (PDOException $e) {
            $db->rollback();
            throw $e;
        }
    }

    
    public static function managerCartView($id)
    {
        $db = static::getDB();
           
        // get database connection


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
    AND TIME(booking.booked_date) + INTERVAL 30 MINUTE > TIME(CURRENT_TIMESTAMP) ';

        // AND booking.booked_date >= :prev_time AND booking.booked_date <=:next_time
        $stmt = $db->prepare($sql2);

        //Binding the customer id and Converting retrieved data from database into PDOs
        $stmt->bindValue(':user_id', $id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
            
        return $result;
    }

    public static function managerAddbookingPaymentSuccess($manager_id, $first_name, $last_name, $primary_contact)
    {
        try {
            $db = static::getDB();
            $db->beginTransaction();

            $sql = 'INSERT INTO `user` (`first_name`,`last_name`,`account_status`,`primary_contact`) VALUES 
        (:first_name,:last_name,:account_status,:primary_contact)';

            $stmt = $db->prepare($sql);

            $account_status = "visitor";

            $stmt->bindValue(':first_name', $first_name, PDO::PARAM_STR);
            $stmt->bindValue(':last_name', $last_name, PDO::PARAM_STR);
            $stmt->bindValue(':account_status', $account_status, PDO::PARAM_STR);
            $stmt->bindValue(':primary_contact', $primary_contact, PDO::PARAM_STR);

            $stmt->execute();

            $sql2 = 'SELECT `user_id` FROM `user` ORDER BY `user_id` DESC LIMIT 1;';

            $stmt2 = $db->prepare($sql2);
            $stmt2->execute();

            //Converting retrieved data from database into PDOs
            $result1 = $stmt2->fetch(PDO::FETCH_ASSOC);
            //Obtaining the user id retrieved from result1
            $user_id = $result1["user_id"];
            //Insert into customer table in database
            $sql3 = 'INSERT INTO `customer`
        (`customer_user_id`) 
        VALUES (:customer_user_id);';

            $stmt3 = $db->prepare($sql3);
            $stmt3->bindValue(':customer_user_id', $user_id, PDO::PARAM_INT);
            $stmt3->execute();

            $sql7 = 'INSERT INTO `payment` (`net_amount`) VALUES (0)';
            $stmt = $db->prepare($sql7);
            $stmt->execute();

            $sql2 = 'SELECT `payment_id` FROM `payment` ORDER BY `payment_id` DESC LIMIT 1;';

            $stmt2 = $db->prepare($sql2);
            $stmt2->execute();
            $result1 = $stmt2->fetch(PDO::FETCH_ASSOC);
            $payment_id = $result1["payment_id"];


            $sql2 = 'SELECT booking.booking_id
        FROM booking
        INNER JOIN booking_timeslot ON booking.booking_id=booking_timeslot.booking_id
        INNER JOIN time_slot ON booking_timeslot.timeslot_id=time_slot.time_slot_id
        INNER JOIN sports_arena_profile ON booking.sports_arena_id=sports_arena_profile.sports_arena_id
        INNER JOIN facility ON booking.facility_id= facility.facility_id
        WHERE booking_timeslot.security_status="active" AND booking.payment_status="pending"
        AND booking.customer_user_id=:user_id AND DATE(booking.booked_date)=DATE(CURRENT_TIMESTAMP)
        AND TIME(booking.booked_date) + INTERVAL 30 MINUTE > TIME(CURRENT_TIMESTAMP) ';

            // AND booking.booked_date >= :prev_time AND booking.booked_date <=:next_time
            $stmt = $db->prepare($sql2);

            //Binding the customer id and Converting retrieved data from database into PDOs
            $stmt->bindValue(':user_id', $manager_id, PDO::PARAM_INT);
            // $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
            $stmt->execute();

            //Assigning the fetched PDOs to result
            $result = $stmt->fetchAll();
            $len = count($result);

            $total_amount = 0;

            // var_dump($result);
            for ($x = 0; $x < $len; $x++) {
                $booking_id = $result[$x][0];

                $sql4 = 'SELECT booking.price_per_booking, booking.booking_date, facility.facility_name, 
        `time_slot`.`start_time`,
        `time_slot`.`end_time`, sports_arena_profile.sa_name
        FROM booking 
        INNER JOIN `facility` ON `facility`.facility_id = booking.facility_id
        INNER JOIN `sports_arena_profile` ON `sports_arena_profile`.sports_arena_id = `booking`.sports_arena_id
        INNER JOIN `booking_timeslot` ON `booking_timeslot`.`booking_id`= `booking`.`booking_id`
        INNER JOIN `time_slot` ON `time_slot`.`time_slot_id`=`booking_timeslot`.`timeslot_id`
        WHERE booking.booking_id =:booking_id';
                $stmt4 = $db->prepare($sql4);
                $stmt4->bindValue(':booking_id', $booking_id, PDO::PARAM_INT);

                $stmt4->execute();

                $result1 = $stmt4->fetch(PDO::FETCH_ASSOC);

                //Obtaining the administratoin staff user details retrieved from result1
                $amount = $result1["price_per_booking"];
                $arena_name = $result1["sa_name"];
                $facility_name = $result1["facility_name"];
                $booking_date = $result1["booking_date"];
                $start_time = $result1["start_time"];
                $end_time = $result1["end_time"];

                $total_amount = $total_amount + $amount;

                $sql5 = 'INSERT INTO `invoice` (`payment_method`, `net_amount`,`payment_id`) VALUES ("cash", :amount, :payment_id)';
                $stmt = $db->prepare($sql5);
                $stmt->bindValue(':amount', $amount, PDO::PARAM_INT);
                $stmt->bindValue(':payment_id', $payment_id, PDO::PARAM_INT);
                $stmt->execute();


                $sql6 = 'SELECT `invoice_id` FROM `invoice` ORDER BY `invoice_id` DESC LIMIT 1;';

                $stmt6 = $db->prepare($sql6);
                $stmt6->execute();

                //Converting retrieved data from database into PDOs
                $result1 = $stmt6->fetch(PDO::FETCH_ASSOC);
                //Obtaining the user id retrieved from result1
                $invoice_id = $result1["invoice_id"];
                //Updating status of the bookings in the database
                $sql = 'UPDATE `booking` SET `payment_status`="paid", `invoice_id`=:invoice_id, `customer_user_id`=:user_id
         WHERE `booking_id`=:booking_id';

                $db = static::getDB();
                $stmt = $db->prepare($sql);
                $stmt->bindValue(':booking_id', $booking_id, PDO::PARAM_INT);
                $stmt->bindValue(':invoice_id', $invoice_id, PDO::PARAM_INT);
                $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
                $stmt->execute();



                //Function to send booking confirmation SMS to visitor

                //our mobile number
                $user = "94765282976";
                //our account password
                $password = 4772;
                //Random OTP code

                // stores the otp code and mobile number into session
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

            $sql = 'UPDATE `payment` SET `net_amount`=:total_amount
         WHERE `payment_id`=:payment_id';

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':total_amount', $total_amount, PDO::PARAM_INT);
            $stmt->bindValue(':payment_id', $payment_id, PDO::PARAM_INT);
            $stmt->execute();
        
            $db->commit();
            return ($payment_id);
        } catch (PDOException $e) {
            $db->rollback();
            throw $e;
        }
    }
    
    //Start of booking cancellation
    public static function bookingCancellation($booking_id, $user_id, $reason)
    {
        try{
            $db = static::getDB();
            $db->beginTransaction();
            $sql = 'SELECT sports_arena_id
        FROM manager WHERE user_id =:user_id';
            //Updating status of the bookings in the database
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();

            //Converting retrieved data from database into PDOs
            $result1 = $stmt->fetch(PDO::FETCH_ASSOC);

            //Obtaining the administratoin staff user details retrieved from result1
            $arena_id = $result1["sports_arena_id"];
       
            $sql = 'SELECT customer_user_id
        FROM booking WHERE booking_id =:booking_id';
            //Updating status of the bookings in the database

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':booking_id', $booking_id, PDO::PARAM_INT);
            $stmt->execute();

            //Converting retrieved data from database into PDOs
            $result1 = $stmt->fetch(PDO::FETCH_ASSOC);

            //Obtaining the customer id from booking table
            $customer_user_id = $result1["customer_user_id"];

            //Adding the cancelled booking to booking cancellation table
            $sql = 'INSERT INTO booking_cancellation (reason,manager_sports_arena_id , administration_staff_sports_arena_id
        ,manager_user_id,customer_user_id,booking_id) VALUES 
        (:reason,:manager_arena_id,:saAdmin_arena_id,:manager_user_id,:customer_user_id,:booking_id)';

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':reason', $reason, PDO::PARAM_STR);
            $stmt->bindValue(':manager_arena_id', $arena_id, PDO::PARAM_INT);
            $stmt->bindValue(':saAdmin_arena_id', $arena_id, PDO::PARAM_INT);
            $stmt->bindValue(':manager_user_id', $user_id, PDO::PARAM_INT);
            $stmt->bindValue(':customer_user_id', $customer_user_id, PDO::PARAM_INT);
            $stmt->bindValue(':booking_id', $booking_id, PDO::PARAM_INT);
            $stmt->execute();

            $sql = 'UPDATE booking SET security_status="inactive" WHERE booking_id=:booking_id';
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':booking_id', $booking_id, PDO::PARAM_INT);
            $stmt->execute();

            $sql = 'UPDATE booking_timeslot SET security_status="inactive" WHERE booking_id=:booking_id';
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':booking_id', $booking_id, PDO::PARAM_INT);
            $stmt->execute();
            $db->commit();
            return true;
        }catch (PDOException $e) {
            $db->rollback();
            throw $e;
        }
    }
    //End of booking cancellation
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
    public static function managerCheckExistingTimeslots($user_id, $start_time, $duration, $facility)
    {

        // Changing start_time variable to hh:mm:ss format
        $start_time=(string)($start_time.":00");

        $hours=(int)substr($start_time, 0, 2);
        $minutes=(int)substr($start_time, 3, 2);
        
        $end_time=$hours+$duration;
        // $end_time=(string)($end_time.":".$minutes);

        // If end_time is less than 10am, add a zero before the hh:mm:ss time format. Else just change it to hh:mm:ss
        if ($end_time<10) {
            $end_time=(string)("0".$end_time.":".$minutes.":00");
        } else {
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

        $sql = 'SELECT * 
            FROM  time_slot
            INNER JOIN facility ON time_slot.facility_id=facility.facility_id
            WHERE time_slot.manager_sports_arena_id=:arena_id AND time_slot.facility_id=:facility
            AND facility.security_status="active" AND timeslot.security_status="active"
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
            if ((strtotime($row["end_time"]) > strtotime($start_time) && strtotime($row["start_time"]) <= strtotime($start_time)) || (strtotime($row["end_time"]) >= strtotime($end_time) && strtotime($row["start_time"]) < strtotime($end_time))) {
                return false;
            }
        }
        // Timeslot can be inserted
        return true;
    }

    //Start of adding timeslot to a sports arena for manager
    public static function managerAddTimeSlots($user_id, $start_time, $duration, $price, $facility)
    {
        try {        //have to add condition for check timeslot is available
            $db = static::getDB();
            $db->beginTransaction();
            $hours = (int)substr($start_time, 0, 2);
            $minutes = (int)substr($start_time, 3, 5);

            $end_time = $hours + $duration;
            $end_time = (string)($end_time . ":" . $minutes);



            // select query for select sports arena from  user id
            $sql = 'SELECT sports_arena_id FROM manager
                WHERE manager.user_id=:user_id';


            $stmt = $db->prepare($sql);
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            $result1 = $stmt->fetch(PDO::FETCH_ASSOC);
            $arena_id = $result1['sports_arena_id'];
            //  var_dump($result);
            // return $result;


            
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
            $stmt->execute();

            $sql3 = 'SELECT time_slot_id 
            FROM time_slot 
            ORDER BY time_slot_id DESC LIMIT 1';

            $stmt3 = $db->prepare($sql3);
            $stmt3->execute();

            //Assigning the fetched PDOs to result
            $result3 = $stmt3->fetch(PDO::FETCH_ASSOC);
            $time_slot_id = $result3['time_slot_id'];
            $db->commit();
            return $time_slot_id;
        } catch (PDOException $e) {
            $db->rollback();
            throw $e;
        }
    }

    //End of adding timeslot to a sports arena for manager

    public static function removeTimeSlot($current_user, $timeslot_id): bool
    {
        try {
            //Create a new database connection
            $db = static::getDB();

            //Start transaction
            $db->beginTransaction();
            //Updating the timeslot table from the database
            $sql = 'UPDATE time_slot 
            SET time_slot.security_status="inactive"
            WHERE time_slot.time_slot_id=:timeslot_id';

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':timeslot_id', $timeslot_id, PDO::PARAM_INT);
            $stmt->execute();

            //Selecting future customer bookings made with this timeslot
            $sql = 'SELECT booking.customer_user_id, booking.booking_id, time_slot.manager_user_id,
            time_slot.manager_sports_arena_id
            FROM booking 
            INNER JOIN booking_timeslot ON booking.booking_id=booking_timeslot.booking_id
            INNER JOIN time_slot ON booking_timeslot.timeslot_id=time_slot.time_slot_id
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

                    //Reason for booking cancellation
                    $reason = "Removal of timeslot from the sports arena";

                    //Insert cancelled booking into booking cancellation table
                    $sql3 = 'INSERT INTO booking_cancellation 
                    (reason, manager_sports_arena_id, administration_staff_sports_arena_id, 
                    manager_user_id, customer_user_id, booking_id)
                    VALUES (:reason, :manager_arena_id,:admin_arena_id, :manager_user_id, 
                    :customer_user_id, :booking_id)';

                    $stmt3 = $db->prepare($sql3);
                    $stmt3->bindValue(':reason', $reason, PDO::PARAM_STR);
                    $stmt3->bindValue('manager_arena_id', $manager_arena_id, PDO::PARAM_INT);
                    $stmt3->bindValue(':admin_arena_id', $manager_arena_id, PDO::PARAM_INT);
                    $stmt3->bindValue(':manager_user_id', $manager_user_id, PDO::PARAM_INT);
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

    //Start of adding facility to a sports arena for manager
    public static function managerAddFacility($user_id, $facility)
    {
        try {
            $db = static::getDB();
            $db->beginTransaction();
            // select query for select sports arena from  user id
            $sql1 = 'SELECT sports_arena_id FROM manager
                WHERE manager.user_id=:user_id';


            $stmt1 = $db->prepare($sql1);
            $stmt1->bindValue(':user_id', $user_id, PDO::PARAM_INT);

            $stmt1->setFetchMode(PDO::FETCH_CLASS, get_called_class());

            $stmt1->execute();

            // Assign retrieved value to variable
            $result1 = $stmt1->fetch(PDO::FETCH_ASSOC);

            //Accessing the associative array
            $arena_id = $result1["sports_arena_id"];

            //  var_dump($result);
            // return $result;


            // insert query for add time slots
            $sql2 = 'INSERT INTO `facility`(`facility_name`,`sports_arena_id`,`manager_user_id`,`manager_sports_arena_id`)
                VALUES (:facility,:arena_id,:user_id,:arena_id)';

            $stmt2 = $db->prepare($sql2);
            $stmt2->bindValue(':facility', $facility, PDO::PARAM_STR);
            $stmt2->bindValue(':arena_id', $arena_id, PDO::PARAM_INT);
            $stmt2->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt2->execute();

            $sql3 = 'SELECT facility_id FROM facility ORDER BY facility_id DESC LIMIT 1';

            $stmt3 = $db->prepare($sql3);
            $stmt3->execute();
            $result3 = $stmt3->fetch(PDO::FETCH_ASSOC);

            $facility_id = $result3['facility_id'];
            $db->commit();
            return $facility_id;
        } catch (PDOException $e) {
            $db->rollback();
            throw $e;
        }
    }

    public static function findFacilityByName($id, $fname)
    {
        $sql = 'SELECT facility_name  FROM facility
                WHERE LOWER(facility.facility_name) = LOWER(:fname) AND facility.manager_user_id=:manager_id AND security_status="active"';

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

    public static function removeFacility($facility_id)
    {
        try {
            $db = static::getDB();
            $db->beginTransaction();

            $sql = 'SELECT time_slot.time_slot_id 
            FROM time_slot
            INNER JOIN facility ON facility.facility_id = time_slot.facility_id
             WHERE  time_slot.facility_id=:facility_id 
             AND facility.security_status="active"';

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':facility_id', $facility_id, PDO::PARAM_INT);

            $stmt->execute();
            $result1 = $stmt->fetchAll(PDO::FETCH_BOTH);

            $len = count($result1);

            $sql2 = 'UPDATE facility SET security_status="inactive" WHERE facility_id=:facility_id';
            $stmt2 = $db->prepare($sql2);
            $stmt2->bindValue(':facility_id', $facility_id, PDO::PARAM_INT);
            $stmt2->execute();
        
            $sql3 = 'UPDATE time_slot SET security_status="inactive" WHERE facility_id=:facility_id';
            $stmt3 = $db->prepare($sql3);
            $stmt3->bindValue(':facility_id', $facility_id, PDO::PARAM_INT);
            $stmt3->execute();

            if ($result1) {
                for ($y = 0; $y < $len; $y++) {
                    $timeslot_id = $result1[$y][0];

                    //Updating the facility table from the database
                    $sql4 = 'UPDATE time_slot 
                SET time_slot.security_status="inactive"
                WHERE time_slot.time_slot_id=:timeslot_id';

                    $stmt4 = $db->prepare($sql4);
                    $stmt4->bindValue(':timeslot_id', $timeslot_id, PDO::PARAM_INT);
                    $stmt4->execute();

                    $sql5 = 'SELECT booking.customer_user_id, booking.booking_id, 
                time_slot.manager_user_id,time_slot.manager_sports_arena_id
                FROM booking 
                INNER JOIN booking_timeslot ON booking.booking_id=booking_timeslot.booking_id
                INNER JOIN time_slot ON booking_timeslot.timeslot_id=time_slot.time_slot_id
                WHERE time_slot.time_slot_id=:timeslot_id
                AND booking.booking_date > (SELECT NOW())';

                    $stmt5 = $db->prepare($sql5);
                    $stmt5->bindValue(':timeslot_id', $timeslot_id, PDO::PARAM_INT);
                    $stmt5->execute();

                    $data = $stmt5->fetchAll(PDO::FETCH_BOTH);


                    if ($data != null) {
                        $len = count($data);
                        $manager_user_id = $data[0][2];
                        $manager_arena_id = $data[0][3];

                        for ($x = 0; $x < $len; $x++) {
                            $customer_user_id = $data[$x][0];
                            $booking_id = $data[$x][1];

                            //Updating the facility table from the database
                            $sql = 'UPDATE booking_timeslot 
                    SET booking_timeslot.security_status="inactive"
                    WHERE booking_timeslot.booking_id=:booking_id';

                            $stmt = $db->prepare($sql);
                            $stmt->bindValue(':booking_id', $booking_id, PDO::PARAM_INT);
                            $stmt->execute();

                            $reason = "Removal of facility and its timeslots from the sports arena";
                            $sql3 = 'INSERT INTO booking_cancellation (`reason`,manager_sports_arena_id
                    ,manager_user_id, customer_user_id, booking_id)
                    VALUES (:reason, :manager_arena_id, :manager_user_id, :customer_user_id, :booking_id)';

                            $stmt3 = $db->prepare($sql3);

                            $stmt3->bindValue(':reason', $reason, PDO::PARAM_STR);
                            $stmt3->bindValue('manager_arena_id', $manager_arena_id, PDO::PARAM_INT);
                            $stmt3->bindValue(':manager_user_id', $manager_user_id, PDO::PARAM_INT);
                            $stmt3->bindValue(':customer_user_id', $customer_user_id, PDO::PARAM_INT);
                            $stmt3->bindValue(':booking_id', $booking_id, PDO::PARAM_INT);
                            $stmt3->execute();

                            //Change these notifications
                            NotificationModel::customerBookingCancellationDeleteFacilityNotification($timeslot_id);
                        }
                    }
                }
            }
            $db->commit();
            return true;
        } catch (PDOException $e) {
            $db->rollback();
            throw $e;
        }
    }


    public static function updateFacility($current_user, $facility_id, $facility_name)
    {
        try {
            $current_user_id = $current_user->user_id;
            $db = static::getDB();
            $db->beginTransaction();
            $sql1 = 'SELECT facility.facility_name 
            FROM facility 
            WHERE facility.facility_id=:facility_id AND facility.security_status="active"';

            $stmt1 = $db->prepare($sql1);
            $stmt1->bindValue(':facility_id', $facility_id, PDO::PARAM_INT);
            $stmt1->execute();
            $result1 = $stmt1->fetch(PDO::FETCH_ASSOC);
            $old_facility_name = $result1["facility_name"];


            $sql2 = 'UPDATE facility SET facility_name=:facility_Name WHERE facility_id=:facility_Id';
            $stmt2 = $db->prepare($sql2);
            $stmt2->bindValue(':facility_Name', $facility_name, PDO::PARAM_STR);
            $stmt2->bindValue(':facility_Id', $facility_id, PDO::PARAM_INT);
            $stmt2->execute();
            $db->commit();
            $success = NotificationModel::saAdminUpdatefacilitySuccessNotification($current_user, $old_facility_name, $facility_name, $facility_id);
            if ($success) {
                return true;
            }
        } catch (PDOException $e) {
            $db->rollback();
            throw $e;
        }
    }
    //End of displaying sports arenas facilities delete for manager
    
    //Start of displaying sports arenas view staff for manager
    public static function managerViewStaff($id)
    {
        //Retrieving arenas staff to view from the database
        $sql = 'SELECT user.user_id, user.first_name, user.last_name ,user.username,user.primary_contact,user.type
        FROM administration_staff
        INNER JOIN booking_handling_staff ON
        administration_staff.manager_user_id =booking_handling_staff.manager_user_id
        INNER JOIN user ON administration_staff.user_id=user.user_id OR booking_handling_staff.user_id=user.user_id
         WHERE user.security_status="active" AND (administration_staff.manager_user_id=:id OR booking_handling_staff.manager_user_id=:id)
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
    public static function addStaff($manager_id, $first_name, $last_name, $mobile_number, $username, $password, $staff_type, $image)
    {
        try {
            $db = static::getDB();
            $db->beginTransaction();
            $password_hashed = password_hash($password, PASSWORD_DEFAULT);
            $image = new Image("image");
            $profile_pic = $image->getURL();
            $sql1 = 'INSERT INTO user(username, password, first_name, last_name, security_status, account_status,primary_contact, type, profile_pic)
                 VALUES (:username, :password, :first_name, :last_name, "active", "active",:primary_contact, :type, :profile_pic)';
            $stmt1 = $db->prepare($sql1);
            $stmt1->bindValue(':username', $username, PDO::PARAM_STR);
            $stmt1->bindValue(':password', $password_hashed, PDO::PARAM_STR);
            $stmt1->bindValue(':first_name', $first_name, PDO::PARAM_STR);
            $stmt1->bindValue(':last_name', $last_name, PDO::PARAM_STR);
            $stmt1->bindValue(':primary_contact', $mobile_number, PDO::PARAM_STR);
            $stmt1->bindValue(':type', $staff_type, PDO::PARAM_STR);
            $stmt1->bindValue(':profile_pic', $profile_pic, PDO::PARAM_STR);
            $stmt1->execute();

            $sql2 = 'SELECT user_id FROM user ORDER BY user_id DESC LIMIT 1';
            $stmt2 = $db->prepare($sql2);
            $stmt2->execute();
            $result2 = $stmt2->fetch(PDO::FETCH_ASSOC);
            $user_id = $result2['user_id'];

            $sql3 = 'SELECT manager.sports_arena_id FROM manager WHERE manager.user_id=:manager_id';
            $stmt3 = $db->prepare($sql3);
            $stmt3->bindValue(':manager_id', $manager_id, PDO::PARAM_INT);
            $stmt3->execute();
            $result3 = $stmt3->fetch(PDO::FETCH_ASSOC);
            $arena_id = $result3['sports_arena_id'];



            if ($staff_type == "BookingHandlingStaff") {
                $sql4 = 'INSERT INTO booking_handling_staff(user_id, sports_arena_id, manager_user_id, 
                                    manager_sports_arena_id) VALUES (:user_id, :arena_id, :manager_id, :manager_arena_id)';
                $stmt4 = $db->prepare($sql4);
                $stmt4->bindValue(':user_id', $user_id, PDO::PARAM_INT);
                $stmt4->bindValue(':arena_id', $arena_id, PDO::PARAM_INT);
                $stmt4->bindValue(':manager_id', $manager_id, PDO::PARAM_INT);
                $stmt4->bindValue(':manager_arena_id', $arena_id, PDO::PARAM_INT);
                $stmt4->execute();
            }
            if ($staff_type == "AdministrationStaff") {
                $sql5 = 'INSERT INTO administration_staff(user_id, sports_arena_id, manager_user_id, manager_sports_arena_id, 
                     profile_sports_arena_id, s_a_profile_id) VALUES (:user_id, :arena_id, :manager_id, 
                     :manager_sports_arena_id, :profile_sports_arena_id, :s_a_profile_id)';
                $stmt5 = $db->prepare($sql5);
                $stmt5->bindValue(':user_id', $user_id, PDO::PARAM_INT);
                $stmt5->bindValue(':arena_id', $arena_id, PDO::PARAM_INT);
                $stmt5->bindValue(':manager_id', $manager_id, PDO::PARAM_INT);
                $stmt5->bindValue(':manager_sports_arena_id', $arena_id, PDO::PARAM_INT);
                $stmt5->bindValue(':profile_sports_arena_id', $arena_id, PDO::PARAM_INT);
                $stmt5->bindValue(':s_a_profile_id', $arena_id, PDO::PARAM_INT);
                $stmt5->execute();
            }
            $db->commit();
            NotificationModel::managerAddStaffSuccessManagerNotification($manager_id, $user_id);
            NotificationModel::managerAddStaffMobileSuccessNotification($first_name, $username, $password, $mobile_number);
            return true;
        } catch (PDOException $e) {
            $db->rollback();
            throw $e;
        }
    }

    public static function removestaff($current_user_id, $user_id)
    {
        try {
            $db = static::getDB();
            $db->beginTransaction();
            $sql = 'UPDATE user SET security_status="inactive", account_status="inactive"  WHERE user_id=:user_id';
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
            $stmt->execute();
            NotificationModel::managerRemoveStaffSuccessManagerNotification($current_user_id, $user_id);
            $db->commit();
            return true;
        } catch (PDOException $e) {
            $db->rollback();
            throw $e;
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
        
        $date = date("Y-m-d", strtotime($monthsadded, strtotime($current_date)));

        $newYear = date("Y", strtotime($date));
        $newDay = date("d", strtotime($date));

        if ($newYear<$lastYear) {
            $monthsadded = "-1 day";
            $date = date("Y-m-d", strtotime($monthsadded, strtotime($date)));
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
        
        $date = date("Y-m-d", strtotime($monthsadded, strtotime($current_date)));

        $newYear = date("Y", strtotime($date));
        $newDay = date("d", strtotime($date));

        if ($newYear<$lastYear) {
            $monthsadded = "-1 day";
            $date = date("Y-m-d", strtotime($monthsadded, strtotime($date)));
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
        
        $date = date("Y-m-d", strtotime($monthsadded, strtotime($current_date)));

        $newYear = date("Y", strtotime($date));
        $newDay = date("d", strtotime($date));

        if ($newYear<$lastYear) {
            $monthsadded = "-1 day";
            $date = date("Y-m-d", strtotime($monthsadded, strtotime($date)));
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
    public static function managerReshapeChart2($dateValue, $id)
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
        
        $date = date("Y-m-d", strtotime($monthsadded, strtotime($current_date)));

        $newYear = date("Y", strtotime($date));
        $newDay = date("d", strtotime($date));

        if ($newYear<$lastYear) {
            $monthsadded = "-1 day";
            $date = date("Y-m-d", strtotime($monthsadded, strtotime($date)));
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
    public static function managerReshapeChart3($dateValue, $id)
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
        
        $date = date("Y-m-d", strtotime($monthsadded, strtotime($current_date)));

        $newYear = date("Y", strtotime($date));
        $newDay = date("d", strtotime($date));

        if ($newYear<$lastYear) {
            $monthsadded = "-1 day";
            $date = date("Y-m-d", strtotime($monthsadded, strtotime($date)));
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
    public static function managerReshapeChart4($dateValue, $id)
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
        
        $date = date("Y-m-d", strtotime($monthsadded, strtotime($current_date)));

        $newYear = date("Y", strtotime($date));
        $newDay = date("d", strtotime($date));

        if ($newYear<$lastYear) {
            $monthsadded = "-1 day";
            $date = date("Y-m-d", strtotime($monthsadded, strtotime($date)));
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

    //End of adding facility to a sports arena for manager
    
    public static function changeImageone($id, $image_1)
    {
        $sql1 = 'SELECT sports_arena_profile_photo.sa_profile_id FROM sports_arena_profile_photo INNER JOIN 
        sports_arena_profile ON sports_arena_profile_photo.sa_profile_id=sports_arena_profile.s_a_profile_id INNER JOIN 
    sports_arena ON sports_arena_profile.sports_arena_id =sports_arena.sports_arena_id INNER JOIN manager 
        ON sports_arena.sports_arena_id = manager.sports_arena_id WHERE user_id=:user_id';
        $db = static::getDB();
        $stm1 = $db->prepare($sql1);
        $stm1->bindValue(':user_id', $id, PDO::PARAM_INT);
        $stm1->execute();
        $result1 = $stm1->fetch(PDO::FETCH_ASSOC);
        $arena_profile_id = $result1['sa_profile_id'];
        $image_1 = new Image("image_1");
        $photo1_name = $image_1->getURL();
        echo($arena_profile_id);
        echo($photo1_name);
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
        $stm1->bindValue(':user_id', $id, PDO::PARAM_INT);
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
        $stm1->bindValue(':user_id', $id, PDO::PARAM_INT);
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
        $stm1->bindValue(':user_id', $id, PDO::PARAM_INT);
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
        $stm1->bindValue(':user_id', $id, PDO::PARAM_INT);
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
}
