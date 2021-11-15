<?php

namespace App\Models;

use Core\Model;
use PDO;
use PDOException;

class LoginModel extends \Core\Model
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

    //Checking whether the username is already exists
    public static function usernameExists($username)
    {
        return static::findByUsername($username) !== false;
    }

    //Checking whether the mobile number is already exists
    public static function mobileNumberExists($mobile_number)
    {
        return static::findByMobileNumber($mobile_number) !== false;    
    }


    // Start of find by username
    public static function findByUsername($username)
    {
        // Retrieving all user details from the database
        $sql = 'SELECT * FROM user WHERE username = :username AND account_status= "active"
        AND security_status= "active"';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        //Binding the username and Converting retrieved data from database into PDOs
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        // Fetch and return the retrieved results
        return $stmt->fetch();
    }
    // End of find by username
   

    // Start of find by mobile number
    public static function findByMobileNumber($mobile_number)
    {
        // Retrieving all user details from the database
        $sql = 'SELECT * FROM user WHERE primary_contact = :mobile_number AND account_status= "active"
         AND security_status= "active';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        //Binding the mobile number and Converting retrieved data from database into PDOs
        $stmt->bindValue(':mobile_number', $mobile_number, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        // Fetch and return the retrieved results
        return $stmt->fetch();
    }
    // End of find by mobile number
    

    //Start of Authenticate a user by username and password
    public static function authenticate($username, $password)
    {
        // find a user by username
        $user = static::findByUsername($username);

        // If user is found
        if ($user) {
            // If passwords match, authentication successful
            if (password_verify($password, $user->password)) {
                return $user;
            }
        }
        // If passwords don't match, authentication failed
        return false;
    }
    //End of Authenticate a user by username and password


    // Start of find by user id
    public static function findByID($id)
    {
        // Retrieving all user details from the database
        $sql = 'SELECT * FROM user WHERE user_id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        //Binding the user id and Converting retrieved data from database into PDOs
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        // Fetch and return the retrieved results
        return $stmt->fetch();
    }
    // End of find by user id













 




}
