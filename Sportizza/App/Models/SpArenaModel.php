<?php

namespace App\Models;

use Core\Model;
use PDO;
use PDOException;
use Core\Image;

class SpArenaModel extends \Core\Model
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

        //Creating an Image object and saving the image path to that object
        $this->image_1 =  new Image("image_1");
        $this->image_2 =  new Image("image_2");
        $this->image_3 =  new Image("image_3");
        $this->image_4 =  new Image("image_4");
        $this->image_5 =  new Image("image_5");
        $this->image_6 =  new Image("image_6");

        //Validate the images with Image Services file in core
        if (!empty($this->image_1->img_errors)) {
            $this->errors["image_1"] = $this->image_1->img_errors;
        }
        if (!empty($this->image_2->img_errors)) {
            $this->errors["image_2"] = $this->image_2->img_errors;
        }
        if (!empty($this->image_3->img_errors)) {
            $this->errors["image_3"] = $this->image_3->img_errors;
        }
        if (!empty($this->image_4->img_errors)) {
            $this->errors["image_4"] = $this->image_4->img_errors;
        }
        if (!empty($this->image_5->img_errors)) {
            $this->errors["image_5"] = $this->image_5->img_errors;
        }
        if (!empty($this->image_6->img_errors)) {
            $this->errors["image_6"] = $this->image_6->img_errors;
        }
    }
    //End of Class constructor

    //Start of saving sports arena and manager details
    public function save()
    {
        // Validate the sports arena and manager details
        $this->validate();

        // Check if there are no errors regard to the signup form
        if (empty($this->errors)) {
            $db = static::getDB();

            // Insert sports arena details into database
            $sql1 = 'INSERT INTO `sports_arena`(`sa_name`) 
            VALUES (:arena_name);';
            $stmt1 = $db->prepare($sql1);
            $stmt1->bindValue(':arena_name', $this->arena_name, PDO::PARAM_STR);
            $stmt1->execute();

            // Retrieve inserted sports arena id from database
            $sql2 = 'SELECT `sports_arena_id` FROM `sports_arena` ORDER BY 
            `sports_arena_id` DESC LIMIT 1;';
            $stmt2 = $db->prepare($sql2);
            $stmt2->execute();

            // Assign retrieved value to variable
            $result1 = $stmt2->fetch(PDO::FETCH_ASSOC);

            //Accessing the associative array
            $sp_arena_id = $result1["sports_arena_id"];

            // Insert sports arena profile details into database
            $sql3 = 'INSERT INTO `sports_arena_profile`
            (`sports_arena_id`,`sa_name`,`location`, `google_map_link`, `description` ,
            `category`,`payment_method`, `other_facilities`, `contact_no`) 
            VALUES (:sp_arena_id , :arena_name, :location, :google_map_link, :description, 
            :category, :payment_method, :other_facilities, :contact);';

            //Assigning Other Category and Other location values to category and location
            if ($this->category == 'Other') {
                $this->category = $this->other_category;
            }
            if ($this->location == 'Other') {
                $this->location = $this->other_location;
            }

            //Binding input data into database query variables
            $stmt3 = $db->prepare($sql3);
            $stmt3->bindValue(':sp_arena_id', $sp_arena_id, PDO::PARAM_INT);
            $stmt3->bindValue(':arena_name', $this->arena_name, PDO::PARAM_STR);
            $stmt3->bindValue(':location', $this->location, PDO::PARAM_STR);
            $stmt3->bindValue(':google_map_link', $this->google_map_link, PDO::PARAM_STR);
            $stmt3->bindValue(':description', $this->description, PDO::PARAM_STR);
            $stmt3->bindValue(':category', $this->category, PDO::PARAM_STR);
            $stmt3->bindValue(':payment_method', $this->payment_method, PDO::PARAM_STR);
            $stmt3->bindValue(':other_facilities', $this->other_facilities, PDO::PARAM_STR);
            $stmt3->bindValue(':contact', $this->contact, PDO::PARAM_STR);
            $stmt3->execute();

            // Retrieve inserted sports arena profile id from database
            $sql4 = 'SELECT s_a_profile_id FROM sports_arena_profile ORDER BY 
            s_a_profile_id DESC LIMIT 1;';
            $stmt4 = $db->prepare($sql4);
            $stmt4->execute();

            // Assign retrieved value to variable
            $result2 = $stmt4->fetch(PDO::FETCH_ASSOC);

            //Accessing the associative array
            $sp_arena_profile_id = $result2["s_a_profile_id"];

            // Insert sports arena photo details into database
            $sql5 = 'INSERT INTO `sports_arena_profile_photo`(`sa_profile_id`,`photo1_name`,
            `photo2_name`, `photo3_name`, `photo4_name`, `photo5_name`) 
            VALUES (:profile_id, :photo1, :photo2, :photo3, :photo4, :photo5)';

            //Binding input data into database query variables
            $stmt5 = $db->prepare($sql5);
            $stmt5->bindValue('profile_id', $sp_arena_profile_id, PDO::PARAM_INT);
            $stmt5->bindValue(':photo1', $this->image_1->getURL(), PDO::PARAM_STR);
            $stmt5->bindValue(':photo2', $this->image_2->getURL(), PDO::PARAM_STR);
            $stmt5->bindValue(':photo3', $this->image_3->getURL(), PDO::PARAM_STR);
            $stmt5->bindValue(':photo4', $this->image_4->getURL(), PDO::PARAM_STR);
            $stmt5->bindValue(':photo5', $this->image_5->getURL(), PDO::PARAM_STR);
            $stmt5->execute();

            // Hash the inputted password
            $password = password_hash($this->password, PASSWORD_DEFAULT);
            
            // Define user type as manager
            $user_type = "Manager";

            // Insert manager details into database
            $sql6 =  'INSERT INTO `user`(`username`,`password`, `first_name`, `last_name`,
            `primary_contact`,`type`, `profile_pic` ) 
            VALUES (:username, :password, :first_name, :last_name, :mobile_number, :type, :profile_pic)';

            //Binding input data into database query variables
            $stmt6 = $db->prepare($sql6);
            $stmt6->bindValue(':first_name', $this->first_name, PDO::PARAM_STR);
            $stmt6->bindValue(':last_name', $this->last_name, PDO::PARAM_STR);
            $stmt6->bindValue(':mobile_number', $this->mobile_number, PDO::PARAM_STR);
            $stmt6->bindValue(':username', $this->username, PDO::PARAM_STR);
            $stmt6->bindValue(':password', $password, PDO::PARAM_STR);
            $stmt6->bindValue(':type', $user_type, PDO::PARAM_STR);
            $stmt6->bindValue(':profile_pic', $this->image_6->getURL(), PDO::PARAM_STR);
            $stmt6->execute();

            // Retrieve inserted manager's user id from database
            $sql7 = 'SELECT user_id FROM user WHERE type="Manager" ORDER BY user_id DESC LIMIT 1;';
            $stmt7 = $db->prepare($sql7);
            $stmt7->execute();

            // Assign retrieved value to variable
            $result3 = $stmt7->fetch(PDO::FETCH_ASSOC);

            // //Accessing the associative array
            $manager_user_id = $result3["user_id"];

            // Insert manager id and sports arena id into database
            $sql8 =  'INSERT INTO `manager`(`user_id`,`sports_arena_id`) 
            VALUES (:user_id, :sp_arena_id)';

            // Binding input data into database query variables
            $stmt8 = $db->prepare($sql8);
            $stmt8->bindValue(':user_id', $manager_user_id, PDO::PARAM_INT);
            $stmt8->bindValue(':sp_arena_id', $sp_arena_id, PDO::PARAM_INT);

            return ($stmt8->execute());
        }

        return false;
    }
    //End of saving sports arena and manager details

    //Start of validate sports arena and manager details
    public function validate()
    {
        // Arena Name
        // Check if it is null
        if ($this->arena_name == '') {
            $this->errors["arena_name1"] = 'Sports Arena name is required';
        } 
        // Check if it already exists
        elseif (static::spArenaExists($this->arena_name, $this->location, $this->category)) {
            $this->errors["arena_name2"] = 'A Sports arena already exists with name, location, and category';
        }

        // // //  contact number     
        // Check if it is null
        if ($this->contact == '') {
            $this->errors["contact1"] = 'Contact number is required';
        } 
        // Check if it already exists
        elseif (preg_match('/.*0[0-9]{9}+.*/', $this->contact) == 0) {
            $this->errors["contact2"] = 'Contact number entered is invalid';
        }

        // // Category & Other category
        // Check if it is null
        if ($this->category == '0') {
            $this->errors["category1"] = 'Select a sports category';
        }

        // Check whether "other category" is chosen and other category is not entered 
        if ($this->category == 'Other' && $this->other_category == '') {
            $this->errors["other_category1"] = 'Enter other sports category';
        } 
        // Check whether "other category" is not chosen and other category is entered 
        elseif ($this->category != 'Other' && $this->other_category != '') {
            $this->errors["other_category2"] = 'Please enter one sports category';
        }


        // Location & Other location
        // Check if it is null
        if ($this->location == '0') {
            $this->errors["location1"] = 'Select a location';
        }
        
        // Check whether "other location" is chosen and other location is not entered 
        if ($this->location == 'Other' && $this->other_location == '') {
            $this->errors["other_location1"] = 'Enter other location';
        } 
        // Check whether "other location" is not chosen and other location is entered 
        elseif ($this->location != 'Other' && $this->other_location != '') {
            $this->errors["other_location2"] = 'Please enter one location';
        }




        // Google map Link
        // Check if it is null
        if ($this->google_map_link == '') {
            $this->errors["google_map1"] = 'Google Map link is required';
        } 
        // Check if the link is valid
        elseif (preg_match('/^https\:\/\/www\.google\.com\/maps\/search\/\?api\=1\&query\=\d\.\d+\%\w+\.\d+$/', $this->google_map_link) == 0) {
            $this->errors["google_map2"] = 'Google Map link enetered is invalid';
        }

        // Description
        // Check if it is null
        if ($this->description == '') {
            $this->errors["description1"] = 'Description is required';
        }

        // Other Facilities
        // Check if it is null
        if ($this->other_facilities == '') {
            $this->errors["Other_facilities1"] = 'Other facilities is required';
        }

        //Accepted Payment Method
        // Check if it is null
        if ($this->payment_method == '0') {
            $this->errors["payment_method1"] = 'Select a payment method';
        }

        //Manager details
        // First Name
        // Check if it is null
        if ($this->first_name == '') {
            $this->errors["first_name1"] = 'First Name is required';
        }
        // Check if only letters
        elseif (preg_match('/^[a-zA-Z ]+$/', $this->first_name) == 0) {
            $this->errors["first_name2"] = 'First Name should consists of only letters';
        }

        // Last Name
        // Check if it is null
        if ($this->last_name == '') {
            $this->errors["last_name1"] = 'Last Name is required';
        }
        // Check if only letters
        elseif (preg_match('/^[a-zA-Z ]+$/', $this->first_name) == 0) {
            $this->errors["last_name2"] = 'Last Name should consists of only letters';
        }

        // mobile number
        // Check if it is null
        if ($this->mobile_number == '') {
            $this->errors["mobile_number1"] = 'Mobile number is required';
        } 
        // Check if mobile number is valid
        elseif (preg_match('/.*07[0-9]{8}+.*/', $this->mobile_number) == 0) {
            $this->errors["mobile_number2"] = 'Mobile number entered is invalid';
        } 
        // Check if moblie number already exist
        elseif (static::mobileNumberExists($this->mobile_number)) {
            $this->errors["mobile_number3"] = 'An account already exists with this mobile number';
        }

        // username
        // Check if it is null
        if ($this->username == '') {
            $this->errors["username1"] = 'Username is required';
        } 
        // Check if username already exist
        elseif (static::usernameExists($this->username)) {
            $this->errors["username2"] = 'Username is already taken';
        }

        // Password
        // Check if it is null
        if ($this->password == '') {
            $this->errors["password1"] = 'Password is required';
        } 
        // Check if has at least 8 characters
        elseif (strlen($this->password) < 8) {
            $this->errors["password2"] = 'Please enter at least 8 characters for the password';
        }
        //Check if has one simple letter
        elseif (preg_match('/.*[a-z]+.*/i', $this->password) == 0) {
            $this->errors["password3"] = 'Password needs at least one simple letter';
        } 
        //Check if has one capital letter
        elseif (preg_match('/.*[A-Z]+.*/i', $this->password) == 0) {
            $this->errors["password4"] = 'Password needs at least one capital letter';
        }
        //Check if has one number
        elseif (preg_match('/.*\d+.*/i', $this->password) == 0) {
            $this->errors["password5"] = 'Password needs at least one number';
        }
        //Check if has one symbol
        elseif (preg_match('/.*[!@#$%^&*-].*/i', $this->password) == 0) {
            $this->errors["password6"] = 'Password needs at least one character';
        }
        return $this->errors;
    }
    //End of validate sports arena and manager details

    //Checking whether a sports arena already exists
    public static function spArenaExists($arena_name, $location, $category)
    {
        return static::findBySportsArena($arena_name, $location, $category) !== false;
    }
    
    //Checking whether a username already exists
    public static function usernameExists($username)
    {
        return static::findByUsername($username) !== false;
    }

    //Checking whether the mobile number is already exists
    public static function mobileNumberExists($mobile_number)
    {
        return static::findByMobileNumber($mobile_number) !== false;
    }

    // Find a sports arena model by name, location and category
    public static function findBySportsArena($arena_name, $category, $location)
    {
        // Retrieve inserted manager's user id from database
        $sql = 'SELECT sa_name,location,category FROM sports_arena_profile WHERE sa_name = :arena_name 
        AND location = :location AND category = :category AND account_status = "active"';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        // Binding input data into database query variables
        $stmt->bindValue(':arena_name', $arena_name, PDO::PARAM_STR);
        $stmt->bindValue(':location', $location, PDO::PARAM_STR);
        $stmt->bindValue(':category', $category, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetch();
    }
    
    // Find a user model by username
    public static function findByUsername($username)
    {
        // Retrieve inserted manager's user id from database
        $sql = 'SELECT * FROM user WHERE username = :username AND account_status= "active"';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        // Binding input data into database query variables
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetch();
    }
    
    // Find a user model by mobile number
    public static function findByMobileNumber($mobile_number)
    {
        // Retrieve inserted manager's user id from database
        $sql = 'SELECT * FROM user WHERE primary_contact = :mobile_number 
        AND account_status= "active"';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        // Binding input data into database query variables
        $stmt->bindValue(':mobile_number', $mobile_number, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();
        return $stmt->fetch();
    }

    public static function getAllLocation()
    {
        $db= static::getDB();
        $sql = 'SELECT DISTINCT UPPER(location) as location from sports_arena_profile';
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public static function getAllSportsCategory()
    {
        $db= static::getDB();
        $sql = 'SELECT DISTINCT UPPER(category) as category from sports_arena_profile';
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }
}
