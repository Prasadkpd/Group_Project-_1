<?php

namespace App\Models;

use Core\Model;
use PDO;
use PDOException;
use App\Auth;

class SpBookStaffModel extends \Core\Model
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
        $sql1 = 'SELECT sports_arena_id FROM booking_handling_staff WHERE user_id =:user_id';
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

    // Start of view bookings of booking handling staff
    public static function saBookViewBookings($id){
        
        // Retrieving booking details from the database
        $sql = 'SELECT booking.booking_id,booking.price_per_booking,
                DATE(booking.booking_date) AS booking_date,
                booking.payment_method,booking.payment_status,
                TIME_FORMAT(time_slot.start_time, "%H" ":" "%i") AS start_time, 
                TIME_FORMAT(time_slot.end_time, "%H" ":" "%i") AS end_time,
                user.primary_contact FROM  booking
                INNER JOIN booking_timeslot ON booking.booking_id = booking_timeslot.booking_id
                INNER JOIN time_slot ON booking_timeslot.timeslot_id=time_slot.time_slot_id
                INNER JOIN user ON user.user_id=booking.customer_user_id
                INNER JOIN booking_handling_staff ON booking.sports_arena_id =booking_handling_staff.sports_arena_id
                 WHERE booking.security_status="active" AND booking_handling_staff.user_id=:id
                 ORDER BY booking.booking_date DESC';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        //Binding the user id and Converting retrieved data from database into PDOs
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
        return $result;
    }
    // End of view bookings of booking handling staff

    // Start of view notifications of booking handling staff
    public static function saBookNotification($id){
        
        //Retrieving of all the user notifications
        $sql = 'SELECT subject,description, DATE(date) as date , TIME_FORMAT( TIME(date) ,"%H" ":" "%i") as time 
        FROM notification WHERE user_id=:id
        AND notification.security_status="active"
        ORDER BY date DESC,time DESC';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        //Binding data and Converting retrieved data from database into PDOs
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
  
        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
        return $result;
    }
    // End of view notifications of booking handling staff
}
