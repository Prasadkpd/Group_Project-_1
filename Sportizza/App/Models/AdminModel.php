<?php

namespace App\Models;

use Core\Model;
use PDO;
use PDOException;
use App\Auth;

class AdminModel extends \Core\Model
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

    //Start of Displaying remove customers
    public static function adminDisplayRemoveCustomers()
    {
        //Retrieving customers from the database
        $sql = 'SELECT * FROM user WHERE type="customer" AND
        security_status="active"';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        //Converting retrieved data from database into PDOs
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        
        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
        return $result;
    }
    //End of Displaying remove customers

    //Start of Displaying pending requests of sports arenas
    public static function adminDisplayAddSportsArenas()
    {
        //Retrieving pending requests of sports arenas from the database
        $sql = 'SELECT sports_arena_profile.sa_name,sports_arena_profile.contact_no, 
                DATE(user.registered_time) as "date" FROM sports_arena_profile
               INNER  JOIN manager ON sports_arena_profile.sports_arena_id =manager.sports_arena_id 
               INNER JOIN user ON user.user_id = manager.user_id
               WHERE sports_arena_profile.account_status="inactive"
               ORDER BY user.registered_time DESC';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        //Converting retrieved data from database into PDOs
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
        return $result;
    }
    //End of Displaying pending requests of sports arenas

    //Start of Displaying of sports arenas with negative feedbacks
    public static function adminDisplayRemoveSportsArenas()
    {
        //Retrieving of sports arenas with negative ratings from the database
        $sql = 'SELECT DISTINCT(sports_arena_profile.s_a_profile_id),
        sports_arena_profile.sa_name, COUNT(feedback.sports_arena_id) as "count",
        sports_arena_profile.account_status FROM feedback
        INNER JOIN sports_arena_profile ON feedback.sports_arena_id=sports_arena_profile.sports_arena_id 
        WHERE feedback.feedback_rating < 3 AND sports_arena_profile.account_status!="inactive" 
        GROUP BY (feedback.sports_arena_id)';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        //Converting retrieved data from database into PDOs
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
        return $result;
    }
    //End of Displaying sports arenas with negative feedbacks

    //Start of Displaying of FAQs
    public static function adminViewFAQ()
    {
        //Retrieving of FAQs from the database
        $sql = 'SELECT * FROM faq WHERE security_status="active" ';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        //Converting retrieved data from database into PDOs
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
        return $result;
    }
    //End of Displaying of FAQs

    //Start of Inserting of FAQs
    public static function adminAddFAQ($type, $question, $solution, $id)
    {
        //Insert FAQs into FAQ table in database
        $sql = 'INSERT INTO `faq`(`question`,`answer`,`type`,`admin_user_id`)
            VALUES (:question, :answer, :type, :id) ';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        //Binding input data into database query variables
        $stmt->bindValue(':type', $type, PDO::PARAM_STR);
        $stmt->bindValue(':question', $question, PDO::PARAM_STR);
        $stmt->bindValue(':answer', $solution, PDO::PARAM_STR);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return ($stmt->execute());
    }
    //End of Inserting of FAQs

    //Start of Displaying Delete FAQs
    public static function adminDisplayDeleteFAQ()
    {
        //Retrieving of FAQs from the database
        $sql = 'SELECT * FROM faq WHERE security_status="active" ';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        //Converting retrieved data from database into PDOs
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
        return $result;
    }
    //End of Displaying Delete FAQs

    //Start of Displaying of Update FAQ questions
    public static function adminGetQuestionDetails($type)
    {
        //Retrieving of FAQs from the database
        $sql = 'SELECT faq_id,question FROM faq WHERE type=:qtype ';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        //Binding input data into database query variables
        $stmt->bindValue(':qtype', $type, PDO::PARAM_STR);

        //Converting retrieved data from database into PDOs
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Converting PDOs to HTML data
        $output = '<option value="0" selected>Select the question</option>';
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $output .= "<option value={$row["faq_id"]}>{$row["question"]}</option>";
        }

        //Returning HTML tags formed above
        return $output;
    }
    //End of Displaying of Update FAQ questions

    //Start of Displaying of Update FAQ answer
    public static function adminGetSolutionDetails($question)
    {
        //Retrieving of FAQs from the database
        $sql = 'SELECT answer FROM faq WHERE faq_id=:question ';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        //Binding input data into database query variables
        $stmt->bindValue(':question', $question, PDO::PARAM_STR);
        $stmt->execute();

        //Converting retrieved data from database into PDOs
        // $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());


        //Retrieving of answer with respected to the question selected
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $output = $result["answer"];

        return $output;
    }
    //End of Displaying of Update FAQ answer

    //Start of Displaying of Remove ratings
    public static function adminDisplayRemoveRatings()
    {
        //Retrieving of negative Customer ratings from the database
        $sql = 'SELECT feedback.feedback_id,feedback.feedback_rating,
        feedback.description,sports_arena_profile.sa_name,sports_arena_profile.contact_no,
        DATE(feedback.posted_date) as "date",
        feedback.sports_arena_id FROM feedback
        INNER JOIN sports_arena_profile ON feedback.sports_arena_id=
        sports_arena_profile.sports_arena_id 
        WHERE feedback.security_status="active" AND feedback.feedback_rating<3 ';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        //Binding input data into database query variables
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
        return $result;
    }
    //End of Displaying of Remove ratings

    //Start of Displaying of admin's chart 1
    public static function adminChart1()
    {
        //Retrieving of chart data from the database
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

        //Converting retrieved data from database into PDOs
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
        return $result;
    }
    //End of Displaying of admin's chart 1
    //Start of Displaying of admin's chart 2
    public static function adminChart2()
    {
        //Retrieving of chart data from the database
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

        //Converting retrieved data from database into PDOs
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
        return $result;
    }
    //End of Displaying of admin's chart 2

    //Start of Displaying of admin's chart 3
    public static function adminChart3()
    {
        //Retrieving of chart data from the database
        $sql = 'SELECT booking_date, COUNT(DISTINCT booking_id) AS No_Of_Bookings
                FROM booking
                WHERE security_status="active"
                GROUP BY booking_date 
                ORDER BY booking_date DESC
                LIMIT 7';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        //Converting retrieved data from database into PDOs
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
        return $result;
    }
    //End of Displaying of admin's chart 3

    //Start of Displaying of admin's chart 4
    public static function adminChart4()
    {
        //Retrieving of chart data from the database
        $sql = 'SELECT payment_method, COUNT(DISTINCT booking_id) AS No_Of_Bookings
                FROM booking
                WHERE security_status="active"
                GROUP BY payment_method ';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        //Converting retrieved data from database into PDOs
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
        return $result;
    }
    //End of Displaying of admin's chart 4

    //Start of Displaying of admin's chart 5
    public static function adminChart5()
    {
        //Retrieving of chart data from the database
        $sql = 'SELECT category, COUNT(DISTINCT sports_arena_id) AS No_Of_Sports_Arenas
        FROM sports_arena_profile
        GROUP BY category;
        ORDER BY category ASC ';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        
        //Converting retrieved data from database into PDOs
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
        return $result;
    }
    //End of Displaying of admin's chart 5

    //Start of Displaying of admin's chart 6
    public static function adminChart6()
    {
        //Retrieving of chart data from the database
        $sql = 'SELECT location, COUNT(DISTINCT sports_arena_id) AS No_Of_Sports_Arenas
        FROM sports_arena_profile
        WHERE security_status="active"
        GROUP BY location
        ORDER BY location ASC ';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        //Converting retrieved data from database into PDOs
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
        return $result;
    }
    //End of Displaying of admin's chart 6
    
}
