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
        
        $sql = 'SELECT booking.booking_id,booking.customer_user_id, booking.booked_date,booking.payment_status,booking.payment_method,
                booking.price_per_booking,sports_arena_profile.sa_name,sports_arena_profile.category,time_slot.start_time,time_slot.end_time
                FROM booking_timeslot
                INNER JOIN (booking
                INNER JOIN sports_arena_profile ON booking.sports_arena_id = sports_arena_profile.sports_arena_id)
                ON booking_timeslot.booking_id = booking.booking_id 
                INNER JOIN time_slot ON booking_timeslot.timeslot_id = time_slot.time_slot_id
                WHERE booking.customer_user_id=:id';




                // $sql = 'SELECT booking.booking_id,booking.customer_user_id, booking.booking_date,booking.payment_status,booking.payment_method,
                // booking.price_per_booking,sports_arena_profile.sa_name,sports_arena_profile.category,time_slot. FROM booking_timeslot
                // INNER Join booking
                //     INNER JOIN sports_arena_profile ON booking_timeslot.sports_arena_id=sports_arena_profile.sports_arena_id
                // ON booking_timeslot.booking_id=booking.booking_id
                // INNER JOIN time_slot ON booking_timeslot.time_slot_id = time_slot.time_slot_id 
                // WHERE customer_user_id = :id;';



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
