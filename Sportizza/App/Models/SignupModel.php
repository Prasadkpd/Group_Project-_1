<?php

namespace App\Models;

use Core\Model;
use PDO;
use PDOException;
use Core\Image;

class SignupModel extends \Core\Model
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
        }
        $this->image_7 =  new Image("image_7"); 
        if (!empty($this->image_7->img_errors)) {  
        $this->errors["image_7"] = $this->image_7->img_errors;
        }
    }

    /**
     * Save the user model with the current property values
     *
     * @return boolean  True if the user was saved, false otherwise
     */
    public function save()
    {

        $this->validate();

        if (empty($this->errors)) {
            $db = static::getDB();
            $password = password_hash($this->password, PASSWORD_DEFAULT);

            $sql1 = 'INSERT INTO `user`(`username`,`password`, `first_name`, `last_name`
            ,`primary_contact`,`profile_pic`) 
            VALUES (:username, :password, :first_name, :last_name, :mobile_number, :profile_pic)';

            $stmt1 = $db->prepare($sql1);

            $stmt1->bindValue(':first_name', $this->first_name, PDO::PARAM_STR);
            $stmt1->bindValue(':last_name', $this->last_name, PDO::PARAM_STR);
            $stmt1->bindValue(':mobile_number', $this->mobile_number, PDO::PARAM_INT);
            $stmt1->bindValue(':username', $this->username, PDO::PARAM_STR);
            $stmt1->bindValue(':password', $password, PDO::PARAM_STR);
            $stmt1->bindValue(':profile_pic', $this->image_7->getURL(), PDO::PARAM_STR);
            $stmt1->execute();

            $sql2 = 'SELECT `user_id` FROM `user` ORDER BY `user_id` DESC LIMIT 1;';
            $stmt2 = $db->prepare($sql2);
            $stmt2->execute();

            $result1 = $stmt2->fetch(PDO::FETCH_ASSOC);
            //Accessing the associative array
            $user_id = $result1["user_id"];

            $sql3 = 'INSERT INTO `customer`
            (`customer_user_id`) 
            VALUES (:customer_user_id);';
            $stmt3 = $db->prepare($sql3);
            $stmt3->bindValue(':customer_user_id', $user_id, PDO::PARAM_INT);
            $stmt3->execute();

            $sql4 = 'INSERT INTO `customer_profile`
            (`customer_user_id`) 
            VALUES (:customer_user_id)';

            $stmt4 = $db->prepare($sql4);
            $stmt4->bindValue(':customer_user_id', $user_id, PDO::PARAM_INT);
           
            return($stmt4->execute());
        }
        else{
            return false;
        }
        
    }

    /**
     * Validate current property values, adding valiation error messages to the errors array property
     *
     * @return void
     */
    public function validate()
    {
        //First Name
        if ($this->first_name == '') {
            $this->errors["first_name1"] = 'First Name is required';
        }
        // letter match
        elseif (preg_match('/^[a-zA-Z ]+$/', $this->first_name) == 0) {
            $this->errors["first_name2"] = 'First Name should consists of only letters';
        }
        
        // Last Name
        if ($this->last_name == '') {
            $this->errors["last_name1"] = 'Last Name is required';
        }
        //letter match
        elseif (preg_match('/^[a-zA-Z ]+$/', $this->first_name) == 0) {
            $this->errors["last_name2"] = 'Last Name should consists of only letters';
        }

        // mobile number
        if ($this->mobile_number == '') {
            $this->errors["mobile_number1"] = 'Mobile number is required';
        }
        elseif (preg_match('/.*07[0-9]{8}+.*/', $this->mobile_number) == 0) {
            $this->errors["mobile_number2"] = 'Mobile number entered is invalid';
        }
        elseif (static::mobileNumberExists($this->mobile_number)) {
            $this->errors["mobile_number3"] = 'An account already exists with this mobile number';
        }

        // username
        if ($this->username == '') {
            $this->errors["username1"] = 'Username is required';
        }
        elseif (static::usernameExists($this->username)) {
            $this->errors["username2"] = 'Username is already taken';
        }

        // Password
        if ($this->password == '') {
            $this->errors["password1"] = 'Password is required';
        }
        elseif (strlen($this->password) < 8) {
            $this->errors["password2"] = 'Please enter at least 8 characters for the password';
        }
        //Letter match
        elseif (preg_match('/.*[a-z]+.*/i', $this->password) == 0) {
            $this->errors["password3"] = 'Password needs at least one simple letter';
        }
        elseif (preg_match('/.*[A-Z]+.*/i', $this->password) == 0) {
            $this->errors["password4"] = 'Password needs at least one capital letter';
        }
        //number match
        elseif (preg_match('/.*\d+.*/i', $this->password) == 0) {
            $this->errors["password5"] = 'Password needs at least one number';
        }
        //character match
        elseif (preg_match('/.*[!@#$%^&*-].*/i', $this->password) == 0) {
            $this->errors["password6"] = 'Password needs at least one character';
        }
       
        return $this->errors;
    
    }



    /**
     * See if a user record already exists with the specified username
     *
     * @param string $username username to search for
     *
     * @return boolean  True if a record already exists with the specified username, false otherwise
     */
    public static function usernameExists($username)
    {
        return static::findByUsername($username) !== false;
    }
    //Checking whether the mobile number is already exists
    public static function mobileNumberExists($mobile_number)
    {
        return static::findByMobileNumber($mobile_number) !== false;    
    }

    //And Check whether account_status== inactive 

    /**
     * Find a user model by username
     *
     * @param string $username username to search for
     *
     * @return mixed User object if found, false otherwise
     */
    public static function findByUsername($username)
    {
        $sql = 'SELECT * FROM user WHERE username = :username AND account_status= "active"';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        return $stmt->fetch();
    }
    /**
     * Find a user model by mobile number
     *
     * @param string $mobile_number to search for
     *
     * @return mixed User object if found, false otherwise
     */

    public static function findByMobileNumber($mobile_number)
    {
        $sql = 'SELECT * FROM user WHERE primary_contact = :mobile_number AND account_status= "active"';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':mobile_number', $mobile_number, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetch();
    }
    

   

    
  
     /** Authenticate a user by username and password.
     *
     * @param string $username username
     * @param string $password password
     *
     * @return mixed  The user object or false if authentication fails
     */
    // public static function authenticate($username, $password)
    // {
    //     $user = static::findByUsername($username);

    //     if ($user) {
    //         if (password_verify($password, $user->password)) {
    //             return $user;
    //         }
    //     }

    //     return false;
    // }


    public static function findByID($id)
    {
        $sql = 'SELECT * FROM user WHERE user_id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetch();
    }

   


}