<?php

namespace App\Models;
use Core\Model;
use PDO;
use PDOException;

class SpArenaModel extends \Core\Model
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

    /**
     * Save the sports arena model with the current property values
     *
     * @return boolean  True if the details are saved, false otherwise
     */
    public function save()
    {    
        $this->validate();

        if (empty($this->errors)) {

            $password = password_hash($this->password, PASSWORD_DEFAULT);
            $sql = 'INSERT INTO `sports_arena`(`sa_name) 
            VALUES (:arena_name);

           INSERT INTO `sports_arena_profile`(`sa_name`,`location, `google_map_link`, `description`
            ,`category`,`payment_method`, `other_facilities`, `contact_no`) 
            VALUES (:arena_name, :location, :google_map_link, :description, 
            :category, :payment_method, :other_facilities, :contact);

            INSERT INTO `sports_arena_profile_photo`(`photo1_name`,`photo2_name`, `photo3_name`, `photo4_name`, `photo5_name`) 
            VALUES (:photo1, :photo2, :photo3, :photo4, :photo5);

            INSERT INTO `user`(`username`,`password`, `first_name`, `last_name`
            ,`primary_contact`) 
            VALUES (:username, :password, :first_name, :last_name, :mobile_number)';

            $db = static::getDB(); 

            $stmt = $db->prepare($sql);

            $stmt->bindValue(':arena_name', $this->arena_name, PDO::PARAM_STR);
            $stmt->bindValue(':location', $this->location, PDO::PARAM_STR);
            $stmt->bindValue(':google_map_link', $this->google_map_link, PDO::PARAM_STR);
            $stmt->bindValue(':description', $this->description, PDO::PARAM_STR);
            $stmt->bindValue(':category', $this->category, PDO::PARAM_STR);
            $stmt->bindValue(':payment_method', $this->payment_method, PDO::PARAM_STR);
            $stmt->bindValue(':other_facilities', $this->other_facilities, PDO::PARAM_STR);
            $stmt->bindValue(':contact', $this->contact, PDO::PARAM_INT);

            $stmt->bindValue(':photo1', $this->photo1, PDO::PARAM_STR);
            $stmt->bindValue(':photo2', $this->photo1, PDO::PARAM_STR);
            $stmt->bindValue(':photo3', $this->photo1, PDO::PARAM_STR);
            $stmt->bindValue(':photo4', $this->photo1, PDO::PARAM_STR);
            $stmt->bindValue(':photo5', $this->photo1, PDO::PARAM_STR);

            $stmt->bindValue(':first_name', $this->first_name, PDO::PARAM_STR);
            $stmt->bindValue(':last_name', $this->last_name, PDO::PARAM_STR);
            $stmt->bindValue(':mobile_number', $this->mobile_number, PDO::PARAM_INT);
            $stmt->bindValue(':username', $this->username, PDO::PARAM_STR);
            $stmt->bindValue(':password', $password, PDO::PARAM_STR);

            return ($stmt->execute());
        }

        return false;
    }

    /**
     * Validate current property values, adding valiation error messages to the errors array property
     *
     * @return void
     */
    public function validate(){
       
         // Arena Name
         if ($this->arena_name == '') {
            $this->errors[] = 'Sports Arena name is required';
        }
         // contact number
         if ($this->contact == '') {
            $this->errors[] = 'Contact number is required';
        }
        //Category 
        if ($this->category == '') {
            $this->errors[] = 'Select a sports category';
        }

        //Other Category

        //Location

        //Other Location

        //Google map Link
        if ($this->google_map_link == '') {
            $this->errors[] = 'Google Map link is required';
        }
        if (preg_match('/@(\-?[0-9]+\.[0-9]+),(\-?[0-9]+\.[0-9]+)/', $this->first_name) == 0) {
            $this->errors[] = 'Google Map link enetered is invalid';
        }

        //Accepted Payment Method

        //Photos


        // First Name
        if ($this->first_name == '') {
            $this->errors[] = 'First Name is required';
        }
        // letter match
        if (preg_match('/.*[a-z\s]+.*/i', $this->first_name) == 0) {
            $this->errors[] = 'First Name should consists of only letters';
        }

        // Last Name
        if ($this->last_name == '') {
            $this->errors[] = 'Last Name is required';
        }
        //letter match
        if (preg_match('/.*[a-z\s]+.*/i', $this->last_name) == 0) {
            $this->errors[] = 'Last Name should consists of only letters';
        }

        // mobile number
        if ($this->mobile_number == '') {
            $this->errors[] = 'Mobile number is required';
        }
        if (preg_match('/.*07[0-9]{8}+.*/', $this->mobile_number) == 0) {
            $this->errors[] = 'Mobile number entered is invalid';
        }
        if (static::mobileNumberExists($this->mobile_number)) {
            $this->errors[] = 'An account already exists with this mobile number';
        }

        // username
        if ($this->username == '') {
            $this->errors[] = 'Username is required';
        }
        if (static::usernameExists($this->username)) {
            $this->errors[] = 'Username is already taken';
        }

        // Password
        if ($this->password == '') {
            $this->errors[] = 'Password is required';
        }
        if (strlen($this->password) < 8) {
            $this->errors[] = 'Please enter at least 8 characters for the password';
        }
        //Letter match
        if (preg_match('/.*[a-z]+.*/i', $this->password) == 0) {
            $this->errors[] = 'Password needs at least one simple letter';
        }
        if (preg_match('/.*[A-Z]+.*/i', $this->password) == 0) {
            $this->errors[] = 'Password needs at least one capital letter';
        }

        //number match
        if (preg_match('/.*\d+.*/i', $this->password) == 0) {
            $this->errors[] = 'Password needs at least one number';
        }

        //character match
        if (preg_match('/.*[!@#$%^&*-].*/i', $this->password) == 0) {
            $this->errors[] = 'Password needs at least one character';
        }
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
         //And Check whether account_status== inactive 
    }
    //Checking whether the mobile number is already exists
    public static function mobileNumberExists($mobile_number)
    {
        return static::findByMobileNumber($mobile_number) !== false;
    //      
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
        $stmt->bindValue(':mobile_number', $mobile_number, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetch();
    }

    public function validateSMS(){
    }

    /**
     * Authenticate a user by username and password.
     *
     * @param string $username username
     * @param string $password password
     *
     * @return mixed  The user object or false if authentication fails
     */
    public static function authenticate($username, $password)
    {
        $user = static::findByUsername($username);

        if ($user) {
            if (password_verify($password, $user->password)) {
                return $user;
            }
        }

        return false;
    }


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
