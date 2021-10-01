<?php

namespace App\Models;
use Core\Model;
use PDO;
use PDOException;
use App\Auth;

class HomeModel extends \Core\Model
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


    public static function homeViewArenas(){
        
        $sql = 'SELECT * FROM sports_arena_profile ORDER BY RAND()
        LIMIT 10';
        



        $db = static::getDB();
        $stmt = $db->prepare($sql);
        // $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
        // var_dump($result);
        return $result;
    }


    public static function homeSearchArenas($location = null,$category=null,$name=""){

        var_dump($location,$category,$name);
        $qu = "SELECT * FROM sports_arena_profile WHERE sa_name LIKE :name ";
        $params = [ "name" => "%$name%" ];
        if($location != "select location"){
            $qu = $qu . "AND  sports_arena_profile.location = :location ";
            $params['location'] = $location;
        }

        if($category != "select category"){
            $qu = $qu . "AND  sports_arena_profile.category = :category ";
            $params['category'] = $category;
        }
        // var_dump($qu,)
        $db = static::getDB();
        $stmt = $db->prepare($qu);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute($params);
        $result = $stmt->fetchAll();
        // var_dump($result);
        return $result;



        /*
        if($location=='select location'){
            if($category=='select category'){
                if(!empty($name)){
                    $sql = 'SELECT * FROM sports_arena_profile 
                WHERE sa_name=:names';
                }
                else{
                    $sql = 'SELECT * FROM sports_arena_profile ';
                }
            
            }
            else{
                if(!empty($name)){
                    $sql = 'SELECT * FROM sports_arena_profile 
                WHERE sa_name=:name AND 
                sports_arena_profile.category=:category';
                }
                else{
                    $sql = 'SELECT * FROM sports_arena_profile 
                WHERE sports_arena_profile.category=:category';
                };
            }


            
        }


        else{
            if($category=='select category'){
                if(!empty($name)){
                    $sql = 'SELECT * FROM sports_arena_profile
                WHERE sa_name=:names AND  sports_arena_profile.location=:locations';
                }
                else{
                    $sql = 'SELECT * FROM sports_arena_profile 
                WHERE sports_arena_profile.location=:locations';
                }
            
            }
            else{
                if(!empty($name)){
                    $sql = 'SELECT * FROM sports_arena_profile 
                WHERE sa_name=:names AND sports_arena_profile.category=:category
                AND  sports_arena_profile.location=:locations';
                }
                else{
                    $sql = 'SELECT * FROM sports_arena_profile 
                    WHERE sports_arena_profile.location=:locations ';
                }
            }
        }
      
        
        // if($category=='Select One'){
        //     $sql = 'SELECT * FROM sports_arena_profile 
        //         WHERE sa_name=:names  AND
        //         sports_arena_profile.location=:locations OR category=:category';
        // }
        // if($location=='Select One'){
        //     $sql = 'SELECT * FROM sports_arena_profile 
        //         WHERE sa_name=:names   AND category=:category
        //         OR sports_arena_profile.location=:locations';
        // }

        // else{
        //     $sql = 'SELECT * FROM sports_arena_profile 
        //         WHERE sa_name=:names OR category=:category OR 
        //         sports_arena_profile.location=:locations';
        // }

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        
        // if($location!='select location'){
        //     $stmt->bindValue(':locations', $location, PDO::PARAM_STR);
        // }
        // if($category!='select category'){
        //     $stmt->bindValue(':category', $category, PDO::PARAM_STR);
        // }
        // if(!empty($name)){
        //     $stmt->bindValue(':names', $name, PDO::PARAM_STR);
        // }
    
        



        if($location=='select location'){
            if($category=='select category'){
                if(!empty($name)){
                    
                    $stmt->bindValue(':names', $name, PDO::PARAM_STR);
                }
                else{
                    
                }
            
            }
            else{
                if(!empty($name)){
                    $stmt->bindValue(':category', $category, PDO::PARAM_STR);
                    $stmt->bindValue(':names', $name, PDO::PARAM_STR);
                }
                else{
                    $stmt->bindValue(':category', $category, PDO::PARAM_STR);
                }
            }


            
        }


        else{
            if($category=='select category'){
                if(!empty($name)){
                    $stmt->bindValue(':locations', $location, PDO::PARAM_STR);
                    $stmt->bindValue(':names', $name, PDO::PARAM_STR);
                }
                else{
                    $stmt->bindValue(':locations', $location, PDO::PARAM_STR);
                }
            
            }
            else{
                if(!empty($name)){
                    $stmt->bindValue(':category', $category, PDO::PARAM_STR);
                    $stmt->bindValue(':locations', $location, PDO::PARAM_STR);
                    $stmt->bindValue(':names', $name, PDO::PARAM_STR);
                }
                else{
                    $stmt->bindValue(':category', $category, PDO::PARAM_STR);
                    $stmt->bindValue(':locations', $location, PDO::PARAM_STR);
                }
            }
        }
        
        

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
        // var_dump($result);
        return $result;
          */
    }

    

    public static function homeViewfeedbacks(){
        
        $sql = 'SELECT feedback.description, feedback.feedback_rating,sports_arena.sa_name,user.first_name,user.last_name
                FROM feedback INNER JOIN sports_arena ON feedback.sports_arena_id = sports_arena.sports_arena_id
                INNER JOIN user ON feedback.customer_user_id=user.user_id  ORDER BY RAND()
                LIMIT 10';
        



        $db = static::getDB();
        $stmt = $db->prepare($sql);
        // $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
        // var_dump($result);
        return $result;
    }


    public static function homeViewCustomerFAQs(){
        
        $sql = 'SELECT faq.question,faq.answer
                FROM faq WHERE faq.security_status="active" AND faq.type="customer"';
        



        $db = static::getDB();
        $stmt = $db->prepare($sql);
        // $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
        // var_dump($result);
        return $result;
    }


    public static function homeViewArenaFAQs(){
        
        $sql = 'SELECT faq.question,faq.answer
                FROM faq WHERE faq.security_status="active" AND faq.type="sports_arena"';
        



        $db = static::getDB();
        $stmt = $db->prepare($sql);
        // $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
        // var_dump($result);
        return $result;
    }

    public static function homeSelectLocations(){
        
        $sql = 'SELECT DISTINCT(sports_arena_profile.location)
                FROM sports_arena_profile';
        



        $db = static::getDB();
        $stmt = $db->prepare($sql);
        // $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
        // var_dump($result);
        return $result;
    }

    public static function homeSelectCategories(){
        
        $sql = 'SELECT DISTINCT(sports_arena_profile.category)
        FROM sports_arena_profile';
        



        $db = static::getDB();
        $stmt = $db->prepare($sql);
        // $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
        // var_dump($result);
        return $result;
    }

}
