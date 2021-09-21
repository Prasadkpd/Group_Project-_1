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
    



}
