<?php

namespace App\Models;
use Core\Model;
use PDO;
use PDOException;
use App\Auth;

class CustomerModel extends \Core\Model
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


    
    public static function customerBookings($id){
        //correct
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
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
        // var_dump($result);
        return $result;
    }

    public static function customerFavouriteList($id){
        //correct 
        $sql = 'SELECT sports_arena_profile.sa_name, sports_arena_profile.category, sports_arena_profile.location
        FROM favourite_list INNER JOIN favourite_list_sports_arena ON favourite_list.fav_list_id = favourite_list_sports_arena.fav_list_id 
        INNER JOIN sports_arena_profile ON favourite_list_sports_arena.sports_arena_id = sports_arena_profile.sports_arena_id 
        WHERE favourite_list.customer_profile_id=:id AND favourite_list_sports_arena.security_status="active"
        AND sports_arena_profile.security_status="active"';


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
        //  var_dump($result);
        return $result;
    }

    public static function customerNotification($id){
        
        $sql = 'SELECT subject,description, DATE(date) as date , TIME(date) as time 
        FROM notification WHERE user_id=:id
        AND notification.security_status="active";
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

    public static function customerViewTimeSlots($arena_id){
        
        $sql = 'SELECT time_slot.time_slot_id,time_slot.start_time,time_slot.end_time,
                time_slot.price,sports_arena_profile.sa_name
                 FROM time_slot  INNER JOIN sports_arena_profile 
                 ON time_slot.manager_sports_arena_id=sports_arena_profile.sports_arena_id
                WHERE time_slot.manager_sports_arena_id=:arena_id';
                // have to change this is wrong we use it for testing


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':arena_id', $arena_id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
        // var_dump($result);
        return $result;
    }

    public static function customerViewArenaDetails($arena_id){
        
        $sql = 'SELECT *
                 FROM  sports_arena_profile 
                WHERE s_a_profile_id=:arena_id';
                // have to change this is wrong we use it for testing


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':arena_id', $arena_id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
        // var_dump($result);
        return $result;
    }
    public static function customerArenaFacilities($arena_id){
        
        $sql = 'SELECT facility.facility_name
                 FROM  facility 
                WHERE sports_arena_id=:arena_id';
                // have to change this is wrong we use it for testing


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':arena_id', $arena_id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
        // var_dump($result);
        return $result;
    }

    public static function customerAddFavoriteList($arena_id,$customer_id){

        $sql = 'SELECT fav_list_id
                 FROM  favourite_list 
                WHERE customer_profile_id=:id';
                // have to change this is wrong we use it for testing


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $customer_id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
    
        $favorite_list_id=$result;






        
        $sql = 'INSERT INTO favourite_list_sports_arena (fav_list_id,sports_arena_id )
        VALUES (:favorite_list_id,:arena_id);';
                


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':arena_id', $arena_id, PDO::PARAM_INT);
        $stmt->bindValue(':favorite_list_id', $favorite_list_id, PDO::PARAM_INT);

        // $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        // $result = $stmt->fetchAll();
        // return $result;
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
