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
                booking.payment_status,booking.payment_method, booking.price_per_booking,sports_arena_profile.sa_name,
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
        $sql = 'SELECT sports_arena_profile.sa_name, sports_arena_profile.category, sports_arena_profile.location
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
                INNER JOIN facility ON time_slot.facility_id=facility.facility_id
                WHERE time_slot.manager_sports_arena_id=:arena_id';
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

    // public static function customerArenaFacilities($arena_id){

    //     $sql = 'SELECT facility.facility_name
    //              FROM  facility 
    //             WHERE sports_arena_id=:arena_id';
    //             // have to change this is wrong we use it for testing


    //     $db = static::getDB();
    //     $stmt = $db->prepare($sql);
    //     $stmt->bindValue(':arena_id', $arena_id, PDO::PARAM_INT);

    //     $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

    //     $stmt->execute();
    //     $result = $stmt->fetchAll();
    //     // var_dump($result);
    //     return $result;
    // }
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

    // public static function customerAddToCart($timeslot_id,$current_user){

    //     $db = static::getDB();

    //     $sql1 = 'UPDATE `time_slot` SET `security_status`="inactive" 
    //             WHERE `time_slot_id`=:timeslot_id';
    //             // have to change this is wrong we use it for testing


    //     $stmt1 = $db->prepare($sql);
    //     $stmt1->bindValue(':timeslot_id', $timeslot_id, PDO::PARAM_INT);
    //     $stmt1->execute();

    //     $sql2 = 'UPDATE `time_slot` SET `security_status`="inactive" 
    //             WHERE `time_slot_id`=:timeslot_id';
    //             // have to change this is wrong we use it for testing


    //     $stmt2 = $db->prepare($sql2);
    //     $stmt2->bindValue(':timeslot_id', $timeslot_id, PDO::PARAM_INT);
    //     $stmt2->execute();

    //     return ($stmt->execute());
    // }

}
