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
        
        $sql = 'SELECT * FROM sports_arena_profile';
        



        $db = static::getDB();
        $stmt = $db->prepare($sql);
        // $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
        // var_dump($result);
        return $result;
    }


    public static function homeSearchArenas($location=null,$category = null,$name = null){

        if(!is_null($location)){
            if(!is_null($category)){
                if(!is_null($name)){
                    $sql = 'SELECT * FROM sports_arena_profile 
                WHERE sa_name=":name" AND category=":category " AND 
                sports_arena_profile.location=":location"';
                }
                else{
                    $sql = 'SELECT * FROM sports_arena_profile 
                WHERE category=":category " AND sports_arena_profile.location=":location"';
                }
            
            }
            else{
                if(!is_null($name)){
                    $sql = 'SELECT * FROM sports_arena_profile 
                WHERE sa_name=":name" AND 
                sports_arena_profile.location=":location"';
                }
                else{
                    $sql = 'SELECT * FROM sports_arena_profile 
                WHERE AND sports_arena_profile.location=":location"';
                };
            }


            
        }


        else{
            if(!is_null($category)){
                if(!is_null($name)){
                    $sql = 'SELECT * FROM sports_arena_profile 
                WHERE sa_name=":name" AND category=":category "';
                }
                else{
                    $sql = 'SELECT * FROM sports_arena_profile 
                WHERE category=":category "';
                }
            
            }
            else{
                if(!is_null($name)){
                    $sql = 'SELECT * FROM sports_arena_profile 
                WHERE sa_name=":name"';
                }
                else{
                    $sql = 'SELECT * FROM sports_arena_profile ';
                };
            }
        }
        
        
        



        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':locations', $location, PDO::PARAM_STR);
        $stmt->bindValue(':category', $category, PDO::PARAM_STR);
        $stmt->bindValue(':name', $name, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
        // var_dump($result);
        return $result;
    }

    

    public static function homeViewfeedbacks(){
        
        $sql = 'SELECT feedback.description, feedback.feedback_rating,sports_arena.sa_name,user.first_name,user.last_name
                FROM feedback INNER JOIN sports_arena ON feedback.sports_arena_id = sports_arena.sports_arena_id
                INNER JOIN user ON feedback.customer_user_id=user.user_id';
        



        $db = static::getDB();
        $stmt = $db->prepare($sql);
        // $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();
        $result = $stmt->fetchAll();
        // var_dump($result);
        return $result;
    }


    public static function homeViewFAQs(){
        
        $sql = 'SELECT faq.question,faq.answer
                FROM faq WHERE faq.security_status="active"';
        



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
