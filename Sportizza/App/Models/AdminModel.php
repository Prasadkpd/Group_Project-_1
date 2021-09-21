<?php

namespace App\Models;
use Core\Model;
use PDO;
use PDOException;
use App\Auth;

class AdminModel extends \Core\Model
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

    public static function adminRemoveCustomers(){
        
        $sql = 'SELECT * FROM user WHERE type="customer"';
        



        $db = static::getDB();
        $stmt = $db->prepare($sql);
        // $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
        // var_dump($result);
        return $result;
    }

    public static function adminAddSportsArenas(){
        
        $sql = 'SELECT sports_arena_profile.sa_name,sports_arena_profile.contact_no, DATE(user.registered_time) as "date" FROM sports_arena_profile
               INNER  JOIN manager  ON sports_arena_profile.sports_arena_id =manager.sports_arena_id 
               INNER JOIN user ON user.user_id = manager.user_id
               WHERE sports_arena_profile.account_status="inactive"';


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        // $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
        //  var_dump($result);
        return $result;
    }

    public static function adminRemoveSportsArenas(){
        
        $sql = 'SELECT DISTINCT(sports_arena_profile.s_a_profile_id),
        sports_arena_profile.sa_name, COUNT(feedback.sports_arena_id) as "count",
        sports_arena_profile.account_status FROM feedback
        INNER JOIN sports_arena_profile ON feedback.sports_arena_id=sports_arena_profile.sports_arena_id 
        WHERE feedback.feedback_rating<3 AND sports_arena_profile.account_status!="inactive" 
        GROUP BY (feedback.sports_arena_id)';




// 'SELECT sports_arena_profile.sa_name,sports_arena_profile.s_a_profile_id,
//                 sports_arena_profile.account_status,  COUNT(feedback.sports_arena_id) as "count",
//                 FROM feedback INNER JOIN sports_arena_profile ON feedback.sports_arena_id= sports_arena_profile.sports_arena_id
//                 WHERE feedback.feedback_rating<3 AND sports_arena_id = 100000000';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        // $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
        //  var_dump($result);
        return $result;
    }

    public static function adminViewFAQ(){
        
        $sql = 'SELECT * FROM faq WHERE security_status="active" ';


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        // $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
        // var_dump($result);
        return $result;
    }

    public static function adminDeleteFAQ(){
        
        $sql = 'SELECT * FROM faq WHERE security_status="active" ';


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        // $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
        // var_dump($result);
        return $result;
    }


    public static function adminRemoveRatings(){
        
        $sql = 'SELECT feedback.feedback_id,feedback.feedback_rating,sports_arena_profile.sa_name,DATE(feedback.posted_date) as "date",
                feedback.sports_arena_id FROM feedback
        INNER JOIN sports_arena_profile ON feedback.sports_arena_id=sports_arena_profile.sports_arena_id 
        WHERE feedback.security_status="active" ';


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        // $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
        //  var_dump($result);
        return $result;
    }

    // Models for displaying charts

    public static function adminChart1(){
        
        $sql = 'SELECT CASE EXTRACT(MONTH FROM user.registered_time)
                    WHEN "1" THEN "January"
                    WHEN "2" THEN "February"
                    WHEN "3" THEN "March"
                    WHEN "4" THEN "April"
                    WHEN "5" THEN "May"
                    WHEN "6" THEN "June"
                    WHEN "7" THEN "July"
                    WHEN "8" THEN "August"
                    WHEN "9" THEN "September"
                    WHEN "10" THEN "October"
                    WHEN "11" THEN "November"
                    WHEN "12" THEN "December"
                    ELSE "Not Valid"
                END AS Time_Registered, COUNT(DISTINCT customer_user_id) AS No_Of_Customers
                FROM customer
                JOIN user ON customer.customer_user_id=user.user_id
                GROUP BY Time_Registered
                ORDER BY user.registered_time ASC ';


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        // $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
        //  var_dump($result);
        return $result;
    }

    public static function adminChart2(){
        
        $sql = 'SELECT CASE EXTRACT(MONTH FROM user.registered_time)
                    WHEN "1" THEN "January"
                    WHEN "2" THEN "February"
                    WHEN "3" THEN "March"
                    WHEN "4" THEN "April"
                    WHEN "5" THEN "May"
                    WHEN "6" THEN "June"
                    WHEN "7" THEN "July"
                    WHEN "8" THEN "August"
                    WHEN "9" THEN "September"
                    WHEN "10" THEN "October"
                    WHEN "11" THEN "November"
                    WHEN "12" THEN "December"
                    ELSE "Not Valid"
                END AS Time_Registered, COUNT(DISTINCT manager.user_id) AS No_Of_Sports_Arenas
                FROM manager
                JOIN user ON manager.user_id=user.user_id
                JOIN sports_arena ON manager.sports_arena_id=sports_arena.sports_arena_id
                GROUP BY Time_Registered
                ORDER BY user.registered_time ASC ';


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        // $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
        //  var_dump($result);
        return $result;
    }

    public static function adminChart3(){
        
        $sql = 'SELECT booking_date, COUNT(DISTINCT booking_id) AS No_Of_Bookings
                FROM booking
                GROUP BY booking_date ';


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        // $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
        //  var_dump($result);
        return $result;
    }

    public static function adminChart4(){
        
        $sql = 'SELECT payment_method, COUNT(DISTINCT booking_id) AS No_Of_Bookings
                FROM booking
                GROUP BY payment_method ';


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        // $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
        //  var_dump($result);
        return $result;
    }

    public static function adminChart5(){
        
        $sql = 'SELECT category, COUNT(DISTINCT sports_arena_id) AS No_Of_Sports_Arenas
        FROM sports_arena_profile
        GROUP BY category;
        ORDER BY category ASC ';


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        // $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
        //  var_dump($result);
        return $result;
    }

    public static function adminChart6(){
        
        $sql = 'SELECT location, COUNT(DISTINCT sports_arena_id) AS No_Of_Sports_Arenas
        FROM sports_arena_profile
        GROUP BY location;
        ORDER BY location ASC ';


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        // $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
        //  var_dump($result);
        return $result;
    }
    



}
