<?php

namespace App\Models;
use Core\Model;
use PDO;
use PDOException;
use App\Auth;

class SpBookStaffModel extends \Core\Model
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

    public static function saBookViewBookings($id){
        
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
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
        // var_dump($result);
        return $result;
    }




    public static function saBookNotification($id){
        
        $sql = 'SELECT subject,description, DATE(date) as date , TIME_FORMAT( TIME(date) ,"%H" ":" "%i") as time 
        FROM notification WHERE user_id=:id
        AND notification.security_status="active"
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


}
