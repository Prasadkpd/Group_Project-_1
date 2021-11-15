<?php

namespace App\Models;

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

    //Start of displaying sports arena profile
    public static function arenaProfileView($id)
    {
        $sql1 = 'SELECT sports_arena_id FROM administration_staff WHERE user_id =:user_id';
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

    //Start of Displaying sports arena bookings
    public static function saAdminViewBookings($id)
    {

        //Retrieving bookings from the database
        $sql = 'SELECT booking.booking_id,booking.price_per_booking,
        DATE(booking.booking_date) AS booking_date,
                booking.payment_method,booking.payment_status,
                TIME_FORMAT(time_slot.start_time, "%H" ":" "%i") AS start_time, 
                TIME_FORMAT(time_slot.end_time, "%H" ":" "%i") AS end_time,
                user.primary_contact FROM  booking
                INNER JOIN booking_timeslot ON booking.booking_id = booking_timeslot.booking_id
                INNER JOIN time_slot ON booking_timeslot.timeslot_id=time_slot.time_slot_id
                INNER JOIN user ON user.user_id=booking.customer_user_id
                INNER JOIN administration_staff ON booking.sports_arena_id =administration_staff.sports_arena_id
                 WHERE booking.security_status="active" AND administration_staff.user_id=:id
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
    //End of Displaying sports arena bookings

    //Start of displaying sports arena timeslot
    public static function saAdminViewAvailableTimeSlots($saAdmin_id)
    {
        $sql = 'SELECT sports_arena_id FROM administration_staff WHERE user_id=:user_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        //Binding the sports arena id and Converting retrieved data from database into PDOs
        $stmt->bindValue(':user_id', $saAdmin_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $arena_id = $result['sports_arena_id'];

        //Retrieving sports arena timeslot from the database
        $sql = 'SELECT time_slot.time_slot_id,TIME_FORMAT(time_slot.start_time, "%H:%i")
    AS startTime,TIME_FORMAT(time_slot.end_time, "%H:%i") AS endTime,
    time_slot.price,facility.facility_name
    FROM time_slot
    INNER JOIN facility ON time_slot.facility_id= facility.facility_id
    WHERE time_slot.time_slot_id NOT IN
     (SELECT booking_timeslot.timeslot_id FROM booking 
    INNER JOIN booking_timeslot ON booking.booking_id=booking_timeslot.booking_id WHERE 
    booking.booking_date=CURRENT_DATE() OR (payment_status="pending" 
     AND booked_date +INTERVAL 30 MINUTE > CURRENT_TIMESTAMP) )
     AND time_slot.manager_sports_arena_id=:arena_id 
     AND time_slot.security_status="active" ORDER BY time_slot.start_time';

        // payment_status="pending" 
        $db = static::getDB();
        $stmt = $db->prepare($sql);

        //Binding the sports arena id and Converting retrieved data from database into PDOs
        $stmt->bindValue(':arena_id', $arena_id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
        return $result;
    }
    //End of Displaying sports arena timeslot


    public static function saAdminSearchTimeSlotsDate($saAdmin_id, $date)
    {
        $sql = 'SELECT sports_arena_id FROM administration_staff WHERE user_id=:user_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        //Binding the sports arena id and Converting retrieved data from database into PDOs
        $stmt->bindValue(':user_id', $saAdmin_id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $arena_id = $result['sports_arena_id'];

        //Retrieving sports arena timeslot from the database
        $sql = 'SELECT time_slot.time_slot_id,TIME_FORMAT(time_slot.start_time, "%H:%i")
    AS startTime,TIME_FORMAT(time_slot.end_time, "%H:%i") AS endTime,
    time_slot.price,facility.facility_name,sports_arena_profile.payment_method
    FROM time_slot
    INNER JOIN facility ON time_slot.facility_id= facility.facility_id
    INNER JOIN sports_arena_profile ON facility.sports_arena_id= sports_arena_profile.sports_arena_id
    WHERE time_slot.time_slot_id NOT IN
     (SELECT booking_timeslot.timeslot_id FROM booking 
    INNER JOIN booking_timeslot ON booking.booking_id=booking_timeslot.booking_id WHERE 
    booking.booking_date=:date )
     AND time_slot.manager_sports_arena_id=:arena_id 
     AND time_slot.security_status="active"
     ORDER BY time_slot.start_time';


        // have to change this is wrong we use it for testing

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        //Binding the sports arena id and Converting retrieved data from database into PDOs
        $stmt->bindValue(':date', $date, PDO::PARAM_STR);
        $stmt->bindValue(':arena_id', $arena_id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

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

    public static function saAdminAddToCart($saAdmin_id, $timeslot_id, $booking_date, $payment_method)
    {
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
        $stmt2->bindValue(':customer_user_id', $saAdmin_id, PDO::PARAM_INT);
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
        // echo($booking_id);
        // echo($timeslot_id);
        $sql4 = 'INSERT INTO `booking_timeslot`(`timeslot_id`, `booking_id`) VALUES 
    (:timeslot_id,:booking_id)';

        // get database connection
        $stmt4 = $db->prepare($sql4);
        //Binding the timeslot id and booking id Converting retrieved data from database into PDOs

        $stmt4->bindValue(':timeslot_id', $timeslot_id, PDO::PARAM_INT);
        $stmt4->bindValue(':booking_id', $booking_id, PDO::PARAM_INT);
        $stmt4->execute();
        return $arena_id;
    }

    public static function saAdminCartView($id)
    {
        // get database connection
        $db = static::getDB();

        //select booked dated from booking and add 30 mins to it and rename it as prev time and next time
        // $sql='SELECT SUBTIME(NOW(),"0:30:00")AS prev_time, NOW() AS next_time';
        // //fetch from this one

        // $stmt = $db->prepare($sql);

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

    //Start of displaying sports arena's cancel bookings
    public static function saAdminCancelBookings($id)
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
                INNER JOIN administration_staff ON booking.sports_arena_id =administration_staff.sports_arena_id
                 WHERE booking.security_status="active"AND administration_staff.user_id=:id
                 ORDER BY booking.booking_date DESC
                ';


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
    public static function saAdminBookingPayment($id)
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
                INNER JOIN administration_staff ON booking.sports_arena_id =administration_staff.sports_arena_id
                 WHERE (booking.security_status="active" AND booking.payment_method="cash") AND
                  booking.payment_status="unpaid" AND administration_staff.user_id=:id';

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

    public static function saAdminAddbookingPaymentSuccess($saAdmin_id, $first_name, $last_name, $primary_contact)
    {
        $db = static::getDB();

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

        $sql7='INSERT INTO `payment` (`net_amount`) VALUES (0)';
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
        $stmt->bindValue(':user_id', $saAdmin_id, PDO::PARAM_INT);
        // $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
        $len = count($result);
       

        $total_amount=0;
        
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
        $arena_name= $result1["sa_name"];
        $facility_name= $result1["facility_name"];
        $booking_date= $result1["booking_date"];
        $start_time= $result1["start_time"];
        $end_time= $result1["end_time"];
       
        $total_amount=$total_amount + $amount;

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
        $otp = mt_rand(100000, 999999);

        // stores the otp code and mobile number into session
        $_SESSION['otp'] = $otp;
        $_SESSION['mobile_number'] = $primary_contact;

        //Message to be sent
        $text = urlencode("You have successfully made a booking to " . $arena_name . " on ". $booking_date . " from " . $start_time . " to " . $end_time . " for " . $facility_name . "." );
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
        $stmt->bindValue(':total_amount',$total_amount,PDO::PARAM_INT);
        $stmt->bindValue(':payment_id', $payment_id, PDO::PARAM_INT);
        $stmt->execute();
        return ($payment_id);
     
    }

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



    //Start of booking cancellation 
    public static function bookingCancellation($booking_id, $user_id, $reason)
    {
        $sql = 'SELECT sports_arena_id, manager_user_id
        FROM administration_staff WHERE user_id =:user_id';
        //Updating status of the bookings in the database

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->execute();

        //Converting retrieved data from database into PDOs
        $result1 = $stmt->fetch(PDO::FETCH_ASSOC);

        //Obtaining the administratoin staff user details retrieved from result1
        $arena_id = $result1["sports_arena_id"];
        $manager_user_id = $result1["manager_user_id"];

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
        $sql = 'INSERT INTO `booking_cancellation` (`reason`,`manager_sports_arena_id`,`administration_staff_sports_arena_id`
        ,`manager_user_id`,`administration_staff_user_id`,`customer_user_id`,`booking_id`) VALUES 
        (:reason,:manager_arena_id,:saAdmin_arena_id,:manager_user_id,:saAdmin_user_id,:customer_user_id,:booking_id)';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':reason', $reason, PDO::PARAM_STR);
        $stmt->bindValue(':manager_arena_id', $arena_id, PDO::PARAM_INT);
        $stmt->bindValue(':saAdmin_arena_id', $arena_id, PDO::PARAM_INT);
        $stmt->bindValue(':manager_user_id', $manager_user_id, PDO::PARAM_INT);
        $stmt->bindValue(':saAdmin_user_id', $user_id, PDO::PARAM_INT);
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

        return ($stmt->execute());
    }
    //End of booking cancellation 

    //Start of displaying notifications for manager
    public static function saAdminNotification($id)
    {

        //Retrieving manager's notifications from the database
        $sql = 'SELECT subject,description, DATE(date) as date , TIME_FORMAT( TIME(date) ,"%H" ":" "%i") as time 
        FROM notification WHERE user_id=:id';

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
    public static function saAdminGetFacilityName($id)
    {
        //Retrieving manager's facility from the database
        $sql = 'SELECT facility.facility_id, facility.facility_name
        FROM facility
        INNER JOIN administration_staff ON facility.manager_sports_arena_id=administration_staff.manager_sports_arena_id
        WHERE administration_staff.user_id=:id';


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
    public static function saAdminViewTimeSlots($id)
    {
        //Retrieving manager's timeslots to view from the database
        $sql = 'SELECT time_slot.time_slot_id, 
         TIME_FORMAT(time_slot.start_time, "%H" ":" "%i") AS startTime, 
        TIME_FORMAT(time_slot.end_time, "%H" ":" "%i") AS endTime,
        time_slot.price, facility.facility_id, facility.facility_name 
        FROM time_slot 
        INNER JOIN facility ON time_slot.facility_id = facility.facility_id
        INNER JOIN administration_staff ON time_slot.manager_user_id=administration_staff.manager_user_id
        WHERE administration_staff.user_id=:id
        AND time_slot.security_status="active"
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
    //End of displaying sports arenas timeslots for manager

    //Start of adding timeslot to a sports arena for manager for administration staff
    public static function saAdminAddTimeSlots($id, $start_time, $duration, $amount, $fid)
    {
        $hours = (int)substr($start_time, 0, 2);
        $minutes = (int)substr($start_time, 3, 5);

        $end_time = $hours + $duration;
        $end_time = (string)($end_time . ":" . $minutes);
        //have to add condition for check timeslot is available
        // select query for select sports arena from  user id 
        $sql1 = 'SELECT manager_user_id, manager_sports_arena_id FROM administration_staff WHERE user_id = :id';

        $db = static::getDB();
        $stmt1 = $db->prepare($sql1);
        $stmt1->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt1->execute();
        $result1 = $stmt1->fetch(PDO::FETCH_ASSOC);
        $mid = $result1["manager_user_id"];
        $arena_id = $result1["manager_sports_arena_id"];

        // insert query for add time slots
        $sql2 = 'INSERT INTO `time_slot`(`start_time`,`end_time`,`price`,`facility_id`,`manager_user_id`,`manager_sports_arena_id`)
                VALUES (:stime, :etime, :amount, :fid, :mid, :arena_id)';

        $stmt2 = $db->prepare($sql2);
        $stmt2->bindValue(':stime', $start_time, PDO::PARAM_STR);
        $stmt2->bindValue(':etime', $end_time, PDO::PARAM_STR);
        $stmt2->bindValue(':amount', $amount, PDO::PARAM_STR);
        $stmt2->bindValue(':fid', $fid, PDO::PARAM_INT);
        $stmt2->bindValue(':mid', $mid, PDO::PARAM_INT);
        $stmt2->bindValue(':arena_id', $arena_id, PDO::PARAM_INT);

        $stmt2->execute();

        $sql3 = 'SELECT time_slot_id FROM time_slot ORDER BY time_slot_id DESC LIMIT 1';

        $stmt3 = $db->prepare($sql3);
        $stmt3->execute();
        $result3 = $stmt3->fetch(PDO::FETCH_ASSOC);
        $time_slot_id = $result3['time_slot_id'];


        $sql3 = 'INSERT INTO administration_staff_manages_time_slot (`time_slot_id`,
       `administration_staff_user_id`,`administration_staff_sports_arena_id`)
       VALUES (:time_slot_id,:user_id,:arena_id)';

        $stmt3 = $db->prepare($sql3);
        $stmt3->bindValue(':time_slot_id', $time_slot_id, PDO::PARAM_INT);
        $stmt3->bindValue(':user_id', $id, PDO::PARAM_INT);
        $stmt3->bindValue(':arena_id', $arena_id, PDO::PARAM_INT);

        return ($stmt3->execute());
    }

    //End of adding timeslot to a sports arena for manager
    public static function CheckExistingTimeslots($user_id, $start_time, $duration, $price, $facility)
    {
        // Changing start_time variable to hh:mm:ss format
        $start_time = (string)($start_time . ":00");

        $hours = (int)substr($start_time, 0, 2);
        $minutes = (int)substr($start_time, 3, 2);

        $end_time = $hours + $duration;
        // $end_time=(string)($end_time.":".$minutes);

        // If end_time is less than 10am, add a zero before the hh:mm:ss time format. Else just change it to hh:mm:ss
        if ($end_time < 10) {
            $end_time = (string)("0" . $end_time . ":" . $minutes . ":00");
        } else {
            $end_time = (string)($end_time . ":" . $minutes . ":00");
        }

        $db = static::getDB();

        // select query for select sports arena from  user id
        $sql = 'SELECT sports_arena_id FROM administration_staff
                WHERE administration_staff.user_id=:user_id';

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
            if ((strtotime($row["end_time"]) > strtotime($start_time) && strtotime($row["start_time"]) <= strtotime($start_time)) || (strtotime($row["end_time"]) >= strtotime($end_time) && strtotime($row["start_time"]) < strtotime($end_time))) {
                return false;
            }
        }
        // Timeslot can be inserted
        return true;
    }


    //Start of displaying sports arenas deleting the timeslots for manager
    public static function saAdminDeleteTimeSlots($id, $timeslot_id)
    {

        $db = static::getDB();

        //Updating the facility table from the database
        $sql = 'UPDATE time_slot 
        SET time_slot.security_status="inactive"
        WHERE time_slot.time_slot_id=:timeslot_id';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':timeslot_id', $timeslot_id, PDO::PARAM_INT);

        $stmt->execute();

        $sql = 'UPDATE administration_staff_manages_time_slot
        SET administration_staff_manages_time_slot.administration_staff_user_id=:id
        WHERE administration_staff_manages_time_slot.time_slot_id=:timeslot_id';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':timeslot_id', $timeslot_id, PDO::PARAM_INT);

        return ($stmt->execute());
    }
    //End of displaying sports arenas deleting the timeslots for administrationstaff

    //Start of displaying sports arenas facilities for administrationstaff
    public static function saAdminViewFacility($id)
    {

        // $sql = 'SELECT *  FROM facility WHERE manager_user_id=:id';

        $sql = 'SELECT *  FROM facility INNER JOIN administration_staff ON 
        facility.manager_user_id=administration_staff.manager_user_id
                 WHERE  administration_staff.user_id=:id 
                 AND facility.security_status="active"';

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

    //End of displaying sports arenas facilities for administrationstaff


    //Remove a facility from the sports arena
    public static function saAdminDeleteFacility($id, $facility_id)
    {
        $db = static::getDB();

        //Updating the facility table from the database
        $sql = 'UPDATE facility 
        SET facility.security_status="inactive"
        WHERE facility.facility_id=:facility_id';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':facility_id', $facility_id, PDO::PARAM_INT);

        $stmt->execute();

        $sql = 'UPDATE administration_staff_manages_facility 
        SET administration_staff_manages_facility.administration_staff_user_id=:id
        WHERE administration_staff_manages_facility.facility_id=:facility_id';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':facility_id', $facility_id, PDO::PARAM_INT);

        return ($stmt->execute());
    }

    // public static function saAdminAddFacility($fname,$ipsw,$id,$rpsw){

    //     // $sql = 'SELECT *  FROM facility WHERE manager_user_id=:id';
    //     $password = password_hash($ipsw, PASSWORD_DEFAULT);

    //     if($password!=$rpsw){
    //         return false;
    //     }

    //     $db = static::getDB();

    //     $sql1 = 'SELECT `manager_user_id`,`manager_sports_arena_id` FROM `administration_staff` WHERE `user_id`=:id';
    //     $stmt1 = $db->prepare($sql1);
    //     $stmt1->bindValue(':id', $id, PDO::PARAM_INT);
    //     $stmt1->execute();

    //     $result1 = $stmt1->fetch(PDO::FETCH_ASSOC);
    //     //Accessing the associative array
    //     $mid = $result1["manager_user_id"];
    //     $said = $result1["manager_sports_arena_id"];

    //     $sql2 = 'INSERT INTO `facility`(`facility_name`,`sports_arena_id`,`manager_user_id`,`manager_sports_arena_id`)
    //             VALUES (:fname, :said, :mid, :said)';

    //     $stmt2 = $db->prepare($sql2);
    //     $stmt2->bindValue(':fname', $stime, PDO::PARAM_STR);
    //     $stmt2->bindValue(':said', $said, PDO::PARAM_INT);
    //     $stmt2->bindValue(':mid', $mid, PDO::PARAM_INT);


    //     // $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
    //     // $result = $stmt->fetchAll();
    //     // var_dump($result);
    //     return ($stmt2->execute());
    // }
    //Start of adding facility to a sports arena for administartion staff
    public static function saAdminAddFacility($user_id, $facility)
    {

        //Check the query
        $db = static::getDB();

        // select query for select sports arena from  user id
        $sql = 'SELECT sports_arena_id, manager_user_id, manager_sports_arena_id 
                FROM administration_staff
                WHERE administration_staff.user_id=:user_id';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        var_dump($result);
        $arena_id = $result["sports_arena_id"];
        $manager_id = $result["manager_user_id"];
        $manager_arena_id = $result["manager_sports_arena_id"];

        // insert query for add time slots
        $sql1 = 'INSERT INTO `facility`(`facility_name`,`sports_arena_id`,`manager_user_id`,`manager_sports_arena_id`)
                VALUES (:facility,:arena_id,:manager_user_id,:manager_sports_arena_id)';


        $stmt = $db->prepare($sql1);
        $stmt->bindValue(':facility', $facility, PDO::PARAM_STR);
        $stmt->bindValue(':arena_id', $arena_id, PDO::PARAM_INT);
        $stmt->bindValue(':manager_user_id', $manager_id, PDO::PARAM_INT);
        $stmt->bindValue(':manager_sports_arena_id', $manager_arena_id, PDO::PARAM_INT);

        $stmt->execute();

        $sql2 = 'SELECT facility_id
                FROM facility
                ORDER BY facility_id DESC LIMIT 1';

        $stmt = $db->prepare($sql2);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        $result2 = $stmt->fetch(PDO::FETCH_ASSOC);
        $facility_id = $result2['facility_id'];


        $sql3 = 'INSERT INTO `administration_staff_manages_facility`(`facility_id`,
        `administration_staff_user_id`,`administration_staff_sports_arena_id`)
        VALUES (:facility_id,:user_id,:arena_id)';

        $stmt = $db->prepare($sql3);
        $stmt->bindValue(':facility_id', $facility_id, PDO::PARAM_INT);
        $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
        $stmt->bindValue(':arena_id', $arena_id, PDO::PARAM_INT);


        return ($stmt->execute());
    }
    //End of adding facility to a sports arena for administartion staff

    //Start of displaying sports arenas facilities update for administration staff
    public static function saAdminUpdateFacility($id, $facility_id, $facility_name)
    {
        //Updating facility name in the database    
        $db = static::getDB();

        //Updating the facility table from the database
        $sql = 'UPDATE facility 
        SET facility.facility_name=:facility_name
        WHERE facility.facility_id=:facility_id';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':facility_name', $facility_name, PDO::PARAM_STR);
        $stmt->bindValue(':facility_id', $facility_id, PDO::PARAM_INT);
        $stmt->execute();

        $sql = 'UPDATE administration_staff_manages_facility 
        SET administration_staff_manages_facility.administration_staff_user_id=:id
        WHERE administration_staff_manages_facility.facility_id=:facility_id';

        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':facility_id', $facility_id, PDO::PARAM_INT);

        return ($stmt->execute());
    }

    public static function findFacilityByName($id, $fname)
    {

        $sql = 'SELECT sports_arena_id FROM administration_staff WHERE administration_staff.user_id=:id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        //Converting retrieved data from database into PDOs
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        $result1 = $stmt->fetch(PDO::FETCH_ASSOC);
        $arena_id = $result1["sports_arena_id"];

        $sql = 'SELECT facility_name  FROM facility
                WHERE LOWER(facility.facility_name) = LOWER(:fname) 
                AND facility.sports_arena_id=:arena_id AND security_status="active"';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':fname', $fname, PDO::PARAM_STR);
        $stmt->bindValue(':arena_id', $arena_id, PDO::PARAM_INT);

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

    public static function findFacilityExcludeByName($id, $facility_id, $fname)
    {
        $sql = 'SELECT sports_arena_id FROM administration_staff WHERE 
        administration_staff.user_id=:id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        //Converting retrieved data from database into PDOs
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        $result1 = $stmt->fetch(PDO::FETCH_ASSOC);
        $arena_id = $result1["sports_arena_id"];

        $sql = 'SELECT facility_name  FROM facility 
                WHERE LOWER(facility.facility_name) = LOWER(:fname) AND 
                facility.sports_arena_id =:arena_id
                AND facility.facility_id <> :facility_id
                AND security_status="active"';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':fname', $fname, PDO::PARAM_STR);
        $stmt->bindValue(':facility_id', $facility_id, PDO::PARAM_INT);
        $stmt->bindValue(':arena_id', $arena_id, PDO::PARAM_INT);

        //Converting retrieved data from database into PDOs
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result2 = $stmt->fetch(PDO::FETCH_ASSOC);
        $facility_name = $result2['facility_name'];
        //Assigning the fetched PDOs to result

        if (!empty($facility_name)) {
            return true;
        } else {
            return false;
        }
    }
}
