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
        
        $sql = 'SELECT booking.booking_id,booking.customer_user_id, booking.booked_date,
                booking.payment_status,booking.payment_method, booking.price_per_booking,sports_arena_profile.sa_name,
                sports_arena_profile.category,time_slot.start_time,time_slot.end_time 
                FROM booking INNER JOIN booking_timeslot ON booking.booking_id = booking_timeslot.booking_id 
                INNER JOIN time_slot ON booking_timeslot.timeslot_id = time_slot.time_slot_id INNER JOIN sports_arena_profile 
                ON booking.sports_arena_id = sports_arena_profile.sports_arena_id WHERE booking.customer_user_id=:id';
        



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
        
        $sql = 'SELECT sports_arena_profile.sa_name, sports_arena_profile.category, sports_arena_profile.location
        FROM favourite_list INNER JOIN favourite_list_sports_arena ON favourite_list.fav_list_id = favourite_list_sports_arena.fav_list_id 
        INNER JOIN sports_arena_profile ON favourite_list_sports_arena.sports_arena_id = sports_arena_profile.sports_arena_id 
        WHERE favourite_list.customer_profile_id=:id';


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

    public static function customerViewTimeSlots(){
        
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
    



}
