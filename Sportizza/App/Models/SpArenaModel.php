<?php

namespace App\Models;

use Core\Model;
use PDO;
use PDOException;
use Core\Image;

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
        $this->image_1 =  (new Image("image_1"))->getURL();
        $this->image_2 =  (new Image("image_2"))->getURL();
        $this->image_3 =  (new Image("image_3"))->getURL();
        $this->image_4 =  (new Image("image_4"))->getURL();
        $this->image_5 =  (new Image("image_5"))->getURL();
        $this->image_6 =  (new Image("image_6"))->getURL();
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
        // if (true){
            $db = static::getDB();

            $sql1 = 'INSERT INTO `sports_arena`(`sa_name`) 
            VALUES (:arena_name);';
            $stmt1 = $db->prepare($sql1);
            $stmt1->bindValue(':arena_name', $this->arena_name, PDO::PARAM_STR);
            $stmt1->execute();

            $sql2 = 'SELECT `sports_arena_id` FROM `sports_arena` ORDER BY `sports_arena_id` DESC LIMIT 1;';
            $stmt2 = $db->prepare($sql2);
            $stmt2->execute();

            $result1 = $stmt2->fetch(PDO::FETCH_ASSOC);
            //Accessing the associative array
            $sp_arena_id = $result1["sports_arena_id"];

            $sql3 = 'INSERT INTO `sports_arena_profile`
            (`sports_arena_id`,`sa_name`,`location`, `google_map_link`, `description` ,
            `category`,`payment_method`, `other_facilities`, `contact_no`) 
            VALUES (:sp_arena_id , :arena_name, :location, :google_map_link, :description, 
            :category, :payment_method, :other_facilities, :contact);';
            
            //Assigning Other Category and Other location values to category and location
            if ($this->category=='Other'){
                $this->category=$this->other_category;
            }
            if ($this->location=='Other'){
                $this->location=$this->other_location;
            }

            $stmt3 = $db->prepare($sql3);
            $stmt3->bindValue(':sp_arena_id', $sp_arena_id, PDO::PARAM_INT);
            $stmt3->bindValue(':arena_name', $this->arena_name, PDO::PARAM_STR);
            $stmt3->bindValue(':location', $this->location, PDO::PARAM_STR);
            $stmt3->bindValue(':google_map_link', $this->google_map_link, PDO::PARAM_STR);
            $stmt3->bindValue(':description', $this->description, PDO::PARAM_STR);
            $stmt3->bindValue(':category', $this->category, PDO::PARAM_STR);
            $stmt3->bindValue(':payment_method', $this->payment_method, PDO::PARAM_STR);
            $stmt3->bindValue(':other_facilities', $this->other_facilities, PDO::PARAM_STR);
            $stmt3->bindValue(':contact', $this->contact, PDO::PARAM_INT);
            $stmt3->execute();

            $sql4 = 'SELECT s_a_profile_id FROM sports_arena_profile ORDER BY s_a_profile_id DESC LIMIT 1;';
            $stmt4 = $db->prepare($sql4);
            $stmt4->execute();

            $result2 = $stmt4->fetch(PDO::FETCH_ASSOC);
            //Accessing the associative array
            $sp_arena_profile_id = $result2["s_a_profile_id"];

            $sql5 = 'INSERT INTO `sports_arena_profile_photo`(`sa_profile_id`,`photo1_name`,
            `photo2_name`, `photo3_name`, `photo4_name`, `photo5_name`) 
            VALUES (:profile_id, :photo1, :photo2, :photo3, :photo4, :photo5)';

            $stmt5 = $db->prepare($sql5);
            $stmt5->bindValue('profile_id', $sp_arena_profile_id, PDO::PARAM_INT);
            $stmt5->bindValue(':photo1', $this->image_1, PDO::PARAM_STR);
            $stmt5->bindValue(':photo2', $this->image_2, PDO::PARAM_STR);
            $stmt5->bindValue(':photo3', $this->image_3, PDO::PARAM_STR);
            $stmt5->bindValue(':photo4', $this->image_4, PDO::PARAM_STR);
            $stmt5->bindValue(':photo5', $this->image_5, PDO::PARAM_STR);
            $stmt5->execute();

            $password = password_hash($this->password, PASSWORD_DEFAULT);
            $user_type = "Manager";
            $sql6 =  'INSERT INTO `user`(`username`,`password`, `first_name`, `last_name`,`primary_contact`,`type`, `profile_pic` ) 
            VALUES (:username, :password, :first_name, :last_name, :mobile_number, :type, :profile_pic)';

            $stmt6 = $db->prepare($sql6);
            $stmt6->bindValue(':first_name', $this->first_name, PDO::PARAM_STR);
            $stmt6->bindValue(':last_name', $this->last_name, PDO::PARAM_STR);
            $stmt6->bindValue(':mobile_number', $this->mobile_number, PDO::PARAM_INT);
            $stmt6->bindValue(':username', $this->username, PDO::PARAM_STR);
            $stmt6->bindValue(':password', $password, PDO::PARAM_STR);
            $stmt6->bindValue(':type', $user_type, PDO::PARAM_STR);
            $stmt6->bindValue(':profile_pic', $this->image_6, PDO::PARAM_STR);
            $stmt6->execute();

            //Check whether its a manager
            $sql7 = 'SELECT user_id FROM user WHERE type="Manager" ORDER BY user_id DESC LIMIT 1;';
            $stmt7 = $db->prepare($sql7);
            $stmt7->execute();
            
            $result3 = $stmt7->fetch(PDO::FETCH_ASSOC);
            // //Accessing the associative array
             $manager_user_id = $result3["user_id"];

            $sql8 =  'INSERT INTO `manager`(`user_id`,`sports_arena_id`) 
            VALUES (:user_id, :sp_arena_id)';

            $stmt8 = $db->prepare($sql8);
            $stmt8->bindValue(':user_id', $manager_user_id, PDO::PARAM_INT);
            $stmt8->bindValue(':sp_arena_id', $sp_arena_id, PDO::PARAM_INT);
            
            return ($stmt8->execute());
            
        }

        return false;
    }

    /**
     * Validate current property values, adding valiation error messages to the errors array property
     *
     * @return void
     */
    public function validate()
    {
    

        // Arena Name
        if ($this->arena_name == '') {
            $this->errors[] = 'Sports Arena name is required';
        }

        // // //  contact number     
        if ($this->contact == '') {
            $this->errors[] = 'Contact number is required';
        }
        if (preg_match('/.*0[0-9]{9}+.*/', $this->contact) == 0) {
            $this->errors[] = 'Contact number entered is invalid';
        }

        // // Category & Other category
        if ($this->category == '0') {
            $this->errors[] = 'Select a sports category';
        }

        if ($this->category == 'Other' && $this->other_category == '') {
            $this->errors[] = 'Enter other sports category';
        }
        if($this->category != 'Other' && $this->other_category != '') {
            $this->errors[] = 'Please enter one sports category';
        }


        // Location & Other location
        if ($this->location == '0') {
            $this->errors[] = 'Select a location';
        }
        if ($this->location == 'Other' && $this->other_location == '') {
            $this->errors[] = 'Enter other location';
        }
        if ($this->location != 'Other' && $this->other_location != '') {
            $this->errors[] = 'Please enter one location';
        }

     
         if (static::spArenaExists($this->arena_name, $this->location, $this->category)) {
             $this->errors[] = 'An Sports arena already exists with name, location, and category';
            }
      
        // Google map Link
        if ($this->google_map_link == '') {
            $this->errors[] = 'Google Map link is required';
        }
        if (preg_match('/^https\:\/\/goo\.gl\/maps\/\w+$/', $this->google_map_link) == 0) {
            $this->errors[] = 'Google Map link enetered is invalid';
        }

        // Description
        if ($this->description == '') {
            $this->errors[] = 'Description is required';
        }
      
        // Other Facilities
        if ($this->other_facilities == '') {
            $this->errors[] = 'Other facilities is required';
        }

        //Accepted Payment Method
        if ($this->payment_method == '0') {
            $this->errors[] = 'Select a payment method';
        }

        //Manager details
        // First Name
        if ($this->first_name == '') {
            $this->errors[] = 'First Name is required';
        }
        // letter match
        if (preg_match('/.*[a-z\s]+.*/i', $this->first_name) == 0) {
            $this->errors[] = 'First Name should consists of only letters';
        }

        // // Last Name
        if ($this->last_name == '') {
            $this->errors[] = 'Last Name is required';
        }
        //letter match
        if (preg_match('/.*[a-z\s]+.*/i', $this->last_name) == 0) {
            $this->errors[] = 'Last Name should consists of only letters';
        }

        // // mobile number
        if ($this->mobile_number == '') {
            $this->errors[] = 'Mobile number is required';
        }
        if (preg_match('/.*07[0-9]{8}+.*/', $this->mobile_number) == 0) {
            $this->errors[] = 'Mobile number entered is invalid';
        }
        //Correct
        if (static::mobileNumberExists($this->mobile_number)) {
            $this->errors[] = 'An account already exists with this mobile number';
        }

        //username
        if ($this->username == '') {
            $this->errors[] = 'Username is required';
        }
        //Correct
        if (static::usernameExists($this->username)) {
            $this->errors[] = 'Username is already taken';
        }

        //Password
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

        // //character match
        if (preg_match('/.*[!@#$%^&*-].*/i', $this->password) == 0) {
            $this->errors[] = 'Password needs at least one character';
        }
    }

    //Checking whether a sports arena already exists
    public static function spArenaExists($arena_name, $location, $category)
    {
        return static::findBySportsArena($arena_name, $location, $category) !== false;
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

    /**
     * Find a sports arena model by name, location and category
     *
     * @param string $this->arena_name to search for sports arenas,
     * 
     *
     * @return mixed Arena object if found, false otherwise
     */
    public static function findBySportsArena($arena_name, $category, $location)
    {
        $sql = 'SELECT sa_name,location,category FROM sports_arena_profile WHERE sa_name = :arena_name 
        AND location = :location AND category = :category AND account_status = "active"';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':arena_name', $arena_name, PDO::PARAM_STR);
        $stmt->bindValue(':location', $location, PDO::PARAM_STR);
        $stmt->bindValue(':category', $category, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

       

        $stmt->execute();

        return $stmt->fetch();
    }
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
}
