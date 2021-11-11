<?php

namespace App\Models;

use Core\Model;
use PDO;
use PDOException;
use App\Auth;

class CustomerModel extends \Core\Model
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

    //Start of Displaying customer's bookings
    public static function customerBookings($id)
    {

        //Retrieving customers from the database
        $sql = 'SELECT booking.booking_id,booking.customer_user_id, booking.booking_date,
                booking.payment_status,booking.payment_method, booking.price_per_booking,booking.sports_arena_id,
                sports_arena_profile.sa_name,
                sports_arena_profile.category,sports_arena_profile.google_map_link,         
                TIME_FORMAT(time_slot.start_time, "%H" ":" "%i") AS startTime, 
                TIME_FORMAT(time_slot.end_time, "%H" ":" "%i") AS endTime
                FROM booking INNER JOIN booking_timeslot ON booking.booking_id = booking_timeslot.booking_id 
                INNER JOIN time_slot ON booking_timeslot.timeslot_id = time_slot.time_slot_id INNER JOIN sports_arena_profile 
                ON booking.sports_arena_id = sports_arena_profile.sports_arena_id WHERE booking.customer_user_id=:id AND `booking`.`security_status`="active"
                ORDER BY booking.booking_date DESC';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        //Binding the customer id and Converting retrieved data from database into PDOs
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
        return $result;
    }
    //End of Displaying customer's bookings

    //Start of Displaying customer's favourite list
    public static function customerFavouriteList($id)
    {

        //Retrieving favourite lists from the database
        $sql = 'SELECT favourite_list.fav_list_id, sports_arena_profile.sports_arena_id, sports_arena_profile.sa_name, sports_arena_profile.category, sports_arena_profile.location 
        FROM favourite_list
        INNER JOIN customer_profile ON  favourite_list.customer_profile_id=customer_profile.customer_profile_id
        INNER JOIN favourite_list_sports_arena ON favourite_list.fav_list_id = favourite_list_sports_arena.fav_list_id 
        INNER JOIN sports_arena_profile ON favourite_list_sports_arena.sports_arena_id = sports_arena_profile.sports_arena_id 
        WHERE customer_profile.customer_user_id=:id AND favourite_list_sports_arena.security_status="active"
        AND sports_arena_profile.security_status="active"';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        //Binding the customer id and Converting retrieved data from database into PDOs
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
        return $result;
    }
    //End of Displaying customer's favourite list

    //Start of Displaying customer's notifications
    public static function customerNotification($id)
    {
        //Retrieving customer notifications from the database
        $sql = 'SELECT subject,description, DATE(date) as date , TIME_FORMAT( TIME(date) ,"%H" ":" "%i") as time 
        FROM notification WHERE user_id=:id
        AND notification.security_status="active"
        ORDER BY date DESC,time DESC';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        //Binding the customer id and Converting retrieved data from database into PDOs
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
        return $result;
    }
    //End of Displaying customer's notifications

    //Start of displaying sports arena timeslot
    public static function customerViewTimeSlots($arena_id)
    {
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
        // AND booked_date> CURRENT_TIMESTAMP

        // have to change this is wrong we use it for testing

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




    public static function customerSearchTimeSlotsDate($arena_id, $date)
    {
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

            <!-- toggle button -->
            <div class='payment_cart'>";
                
            if ($row["payment_method"]=='card'){
                $output .= "<div class='toggle-button-cover'>
                <div class='button-cover'>
                    <div class='button r' id='button-1'>
                        <input type='checkbox' class='checkbox' name='paymentMethod' value='card' checked>
                       
                        <div class='layer1'>card</div>
                        
                    </div>
                </div>
            </div>";
            } elseif ($row["payment_method"]=='cash'){    
                
                $output .= "<div class='toggle-button-cover'>
                <div class='button-cover'>
                    <div class='button r' id='button-1'>
                        <input type='checkbox' class='checkbox' name='paymentMethod' value='cash' checked>
                        
                        <div class='layer1'>cash</div>
                    </div>
                </div>
            </div>";
            } elseif ($row["payment_method"]=='both'){
                $output .= "<div class='toggle-button-cover'>
                <div class='button-cover'>
                <div class='button r' id='button-1'>
                        <input type='checkbox' class='checkbox' name='paymentMethod' onclick='paymentclick()' value='card' checked>
                        <div class='knobs'></div>
                        <div class='layer'></div>
                    </div>
                </div>
            </div>";
            }
                
            $output .= "<div>
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


    //Start of displaying sports arena details
    public static function customerViewArenaDetails($arena_id)
    {
        //Retrieving sports arena profile from the database
        $sql = 'SELECT *
                 FROM  sports_arena_profile 
                WHERE s_a_profile_id=:arena_id';
        // have to change this is wrong we use it for testing

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        //Binding the arena id and Converting retrieved data from database into PDOs
        $stmt->bindValue(':arena_id', $arena_id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
        return $result;
    }
    //End of displaying sports arena details


    //Start adding a sports arena to the favourite list 
    public static function customerAddFavoriteList($arena_id, $customer_id)
    {
        //Retrieving favoorute list id  from the database
        $sql = 'SELECT favourite_list.fav_list_id
                FROM  customer_profile
                INNER JOIN favourite_list ON customer_profile.customer_profile_id=favourite_list.customer_profile_id
                WHERE customer_profile.customer_user_id=:id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        //Binding the customer id and Converting retrieved data from database into PDOs
        $stmt->bindValue(':id', $customer_id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();

        //Assigning the favourite list it from the fetched PDO
        $favorite_list_id = $result[0]->fav_list_id;
        //Why var_dump?
        var_dump($favorite_list_id);

        //Retreiving all the sports arenas in favorite list
        $sql = 'SELECT sports_arena_id
        FROM  favourite_list_sports_arena
        WHERE fav_list_id=:favorite_list_id AND sports_arena_id=:arena_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        //Binding the favourite list id, arena id and Converting retrieved data from database into PDOs
        $stmt->bindValue(':favorite_list_id', $favorite_list_id, PDO::PARAM_STR);
        $stmt->bindValue(':arena_id', $arena_id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $spArenaExists = $stmt->fetchAll();

        //If there's no such sports arena in the favourite list
        if (!$spArenaExists) {

            //Inserting the sports arenas to favorite list
            $sql = 'INSERT INTO favourite_list_sports_arena (fav_list_id,sports_arena_id)
        VALUES (:favorite_list_id,:arena_id);';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            //Binding the sports arena id and favourite list id Converting retrieved data from database into PDOs
            $stmt->bindValue(':arena_id', $arena_id, PDO::PARAM_INT);
            $stmt->bindValue(':favorite_list_id', $favorite_list_id, PDO::PARAM_STR);
            $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

            return ($stmt->execute());
        }
    }


    public static function customerCancelBooking($booking_id)
    {
        //update booking security status as a inactive
        $sql = 'UPDATE booking 
                SET security_status="inactive"
                WHERE booking_id=:booking_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        //Binding the customer id and Converting retrieved data from database into PDOs
        $stmt->bindValue(':booking_id', $booking_id, PDO::PARAM_INT);
        $stmt->execute();






        //update booking_timeslot security  status as a inactive
        $sql = 'UPDATE booking_timeslot 
                SET security_status="inactive"
                WHERE booking_id=:booking_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        //Binding the customer id and Converting retrieved data from database into PDOs
        $stmt->bindValue(':booking_id', $booking_id, PDO::PARAM_INT);
        return $stmt->execute();
    }



    public static function customerDeleteBooking($booking_id)
    {
        //update booking status as a inactive
        $sql = 'UPDATE booking 
                SET security_status="inactive"
                WHERE booking_id=:booking_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        //Binding the customer id and Converting retrieved data from database into PDOs
        $stmt->bindValue(':booking_id', $booking_id, PDO::PARAM_INT);
        return $stmt->execute();
    }

    public static function customerDeleteFavoriteArena($fav_list_id, $arena_id)
    {
        //update booking status as a inactive
        $sql = 'UPDATE favourite_list_sports_arena 
                SET security_status="inactive"
                WHERE fav_list_id=:fav_list_id AND sports_arena_id=:arena_id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        //Binding the customer id and Converting retrieved data from database into PDOs
        $stmt->bindValue(':fav_list_id', $fav_list_id, PDO::PARAM_INT);
        $stmt->bindValue(':arena_id', $arena_id, PDO::PARAM_INT);
        return $stmt->execute();
    }


    public static function customerAddFeedback($feedback)
    {
        //insert query for add feedbacks
        $sql = 'INSERT INTO feedback(booking_id,feedback_rating,sports_arena_id,description,customer_user_id)
        VALUES(:booking_id,:feedback_rating,:sports_arena_id,:description,:customer_user_id)';

        // get database connection
        $db = static::getDB();
        $stmt = $db->prepare($sql);

        //Binding the customer id and Converting retrieved data from database into PDOs
        $stmt->bindValue(':booking_id', $feedback["booking_id"], PDO::PARAM_INT);
        $stmt->bindValue(':feedback_rating', $feedback["rate"], PDO::PARAM_INT);
        $stmt->bindValue(':sports_arena_id', $feedback["arena_id"], PDO::PARAM_STR);
        $stmt->bindValue(':description', $feedback["rating_description"], PDO::PARAM_STR);
        $stmt->bindValue(':customer_user_id', $feedback["customer_id"], PDO::PARAM_INT);



        return $stmt->execute();
    }

    public static function customerAddToCart($customer_id,$timeslot_id, $booking_date, $payment_method)
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
        $facility_id= $result['facility_id'];
        $arena_id = $result['manager_sports_arena_id'];
        //Assigning the fetched PDOs to result

        //insert query for add feedbacks
        $sql2 = 'INSERT INTO `booking`(`customer_user_id`, `booking_date`, 
        `payment_method`, `price_per_booking`, `facility_id`, 
        `sports_arena_id`) VALUES 
        (:customer_user_id,:booking_date,:payment_method,:price,:facility_id,
        :sports_arena_id)';

        // get database connection
        $stmt2 = $db->prepare($sql2);
        //Binding the customer id and Converting retrieved data from database into PDOs
        $stmt2->bindValue(':customer_user_id', $customer_id, PDO::PARAM_INT);
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

    public static function customerCartView($id)
    {
        // get database connection
        $db = static::getDB();

        //select booked dated from booking and add 30 mins to it and rename it as prev time and next time
        // $sql='SELECT SUBTIME(NOW(),"0:30:00")AS prev_time, NOW() AS next_time';
        // //fetch from this one

        // $stmt = $db->prepare($sql);

        $sql2='SELECT booking.price_per_booking, booking.booking_id, time_slot.start_time,time_slot.end_time, 
        sports_arena_profile.sa_name, sports_arena_profile.category, sports_arena_profile.location,
         booking.booked_date,booking.payment_method
        FROM booking
        INNER JOIN booking_timeslot ON booking.booking_id=booking_timeslot.booking_id
        INNER JOIN time_slot ON booking_timeslot.timeslot_id=time_slot.time_slot_id
        INNER JOIN sports_arena_profile ON booking.sports_arena_id=sports_arena_profile.sports_arena_id
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
}
