<?php

namespace App\Models;

use Core\Model;
use PDO;
use PDOException;
use App\Auth;

class HomeModel extends \Core\Model
{
    // Array of Error messages
    public $errors = [];

    //Start of Class constructor 
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->$key = $value;
        };
    }
    //End of Class constructor

    //Start of showing the sports arenas by default in visitor's view
    public static function homeViewArenas()
    {

        //Retrieving the sports arenas from the database
        $sql = 'SELECT * FROM sports_arena_profile ORDER BY RAND()
        LIMIT 10';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        //Converting retrieved data from database into PDOs
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
        return $result;
    }
    //End of showing the sports arenas by default in visitor's view

    //Start of showing the sports arenas after searched in visitor's view
    public static function homeSearchArenas($location, $category, $name = "")
    {
        // var_dump($location,$category,$name);
        //Retrieving the sports arenas from the database with/without name
        $qu = "SELECT * FROM sports_arena_profile WHERE sa_name LIKE :name ";
        $params = ["name" => "%$name%"];
        //If a is location is selected, concatinate it to the original query
        if ($location != "select location") {
            $qu = $qu . "AND  sports_arena_profile.location = :location ";
            $params['location'] = $location;
        }
        //If a category is selected, concatinate it to the original query
        if ($category != "select category") {
            $qu = $qu . "AND  sports_arena_profile.category = :category ";
            $params['category'] = $category;
        }

        $db = static::getDB();
        $stmt = $db->prepare($qu);

        //Converting retrieved data from database into PDO
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute($params);

        if($location === "select location" && $category === "select category" &&
        $name === "")
        {
            $output = "";
        }
        else{
            $output = "<h3 id='search-heading'>Search Results:</h3>";
        }

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $output .= "<div class='result-details'><h4><a>{$row["sa_name"]}</a></h4>
                        <div class='sp-arena-summary-items'>
                        <div class='arena-details'>
                                <div class='arena-detail'>
                                    <h5>City:<h6>{$row["location"]}</h6>
                                    </h5>
                                </div>
                                <div class='arena-detail'>
                                    <h5>Category: <h6>{$row["category"]}</h6>
                                    </h5>
                                </div>
                        </div>
                        <a href='/customer/booking/{$row["sports_arena_id"]}' class='btn check-availability-btn'> Availability</a>
                        </div></div>";
        }


        return $output;
    }
    //End of showing the sports arenas after searched in visitor's view

    //Start of displaying the feedbacks in visitor's view
    public static function homeViewfeedbacks()
    {
        //Retrieving the feedbacks from the database
        $sql = 'SELECT feedback.description, feedback.feedback_rating,sports_arena.sa_name,user.first_name,
        user.last_name, user.profile_pic
                FROM feedback INNER JOIN sports_arena ON feedback.sports_arena_id = sports_arena.sports_arena_id
                INNER JOIN user ON feedback.customer_user_id=user.user_id  
                LIMIT 10';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        //Converting retrieved data from database into PDO
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
        return $result;
    }
    //End of displaying the feedbacks in visitor's view

    //Start of displaying the customer FAQs in visitor's view
    public static function homeViewCustomerFAQs()
    {
        //Retrieving the feedbacks from the database
        $sql = 'SELECT faq.question,faq.answer
                FROM faq WHERE faq.security_status="active" 
                AND faq.type="customer"';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        //Converting retrieved data from database into PDO
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
        return $result;
    }
    //End of displaying the customer FAQs in visitor's view

    //Start of displaying the customer FAQs in visitor's view
    public static function homeViewArenaFAQs()
    {
        //Retrieving the sports arena faq from the database
        $sql = 'SELECT faq.question,faq.answer
                FROM faq WHERE faq.security_status="active" AND faq.type="sports_arena"';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        //Converting retrieved data from database into PDO
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
        return $result;
    }
    //End of displaying the customer FAQs in visitor's view

    //Start of displaying the the distinct locations for search arenas in visitor's view
    public static function homeSelectLocations()
    {
        //Retrieving the sports arena location from the database
        $sql = 'SELECT DISTINCT(sports_arena_profile.location)
                FROM sports_arena_profile';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        //Converting retrieved data from database into PDO
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
        return $result;
    }
    //End of displaying the the distinct locations for search arenas in visitor's view

    //Start of displaying the the distinct categories for search arenas in visitor's view
    public static function homeSelectCategories()
    {
        //Retrieving the sports arena category from the database
        $sql = 'SELECT DISTINCT(sports_arena_profile.category)
        FROM sports_arena_profile';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        
        //Converting retrieved data from database into PDO
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        //Assigning the fetched PDOs to result
        $result = $stmt->fetchAll();
        return $result;
    }
    //End of displaying the the distinct categories for search arenas in visitor's view
}
