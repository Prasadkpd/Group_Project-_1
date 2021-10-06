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



    // OLD ONE
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

    public static function adminAddFAQ($type,$question,$solution,$id){
        
        
            $db = static::getDB();

            $sql = 'INSERT INTO `faq`(`question`,`answer`,`type`,`admin_user_id`)
            VALUES (:question, :answer, :type, :id) ';

            // if($this->type=='customer'){
            //     $type= "customer";
            // }
            // if($this->type=='sports_arena'){
            //     $type= "sports_arena";
            // }

            $stmt = $db->prepare($sql);
            $stmt->bindValue(':type', $type, PDO::PARAM_STR);
            $stmt->bindValue(':question', $question, PDO::PARAM_STR);
            $stmt->bindValue(':answer', $solution, PDO::PARAM_STR);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);

            //$stmt2->setFetchMode(PDO::FETCH_CLASS, get_called_class());

            //$stmt->execute();
            //$result = $stmt->fetchAll();
            // var_dump($result);
            return ($stmt->execute());
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

    public static function adminGetQuestionDetails($type){
        $sql = 'SELECT faq_id,question FROM faq WHERE type=:qtype ';


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':qtype', $type, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        // $result = $stmt->fetch(PDO::FETCH_ASSOC);
        // $question = $result["question"];

        // $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $output = '<option value="0" selected>Select the question</option>';

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $output .= "<option value={$row["faq_id"]}>{$row["question"]}</option>";
        }
        
        // var_dump($result);
        return $output;
        // return '<option>Kiri Shawty</option>';
        // echo $type;
    }

    public static function adminGetSolutionDetails($question){
        $sql = 'SELECT answer FROM faq WHERE faq_id=:question ';


        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':question', $question, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        // $result = $stmt->fetch(PDO::FETCH_ASSOC);
        // $question = $result["question"];

        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        $output = $result["answer"];

        // while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        //     $output .= "<option value={$row["faq_id"]}>{$row["question"]}</option>";
        // }
        
        // var_dump($result);
        return $output;
        // return '<option>Kiri Shawty</option>';
        // echo $type;
    }


    public static function adminRemoveRatings(){
        
        $sql = 'SELECT feedback.feedback_id,feedback.feedback_rating,feedback.description,sports_arena_profile.sa_name,DATE(feedback.posted_date) as "date",
                feedback.sports_arena_id FROM feedback
        INNER JOIN sports_arena_profile ON feedback.sports_arena_id=sports_arena_profile.sports_arena_id 
        WHERE feedback.security_status="active" AND feedback.feedback_rating<3 ';


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
                END AS Time_Registered, COUNT(DISTINCT user_id) AS No_Of_Customers
                FROM user
                WHERE user.account_status="active" AND user.type="Customer"
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
        
        $sql = 'SELECT CASE EXTRACT(MONTH FROM sports_arena.registered_time)
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
                END AS Time_Registered, COUNT(DISTINCT sports_arena.sports_arena_id) AS No_Of_Sports_Arenas
                FROM sports_arena
                WHERE sports_arena.security_status="active"
                GROUP BY Time_Registered
                ORDER BY sports_arena.registered_time ASC ';


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
                WHERE security_status="active"
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
                WHERE security_status="active"
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
        WHERE security_status="active"
        GROUP BY location
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

