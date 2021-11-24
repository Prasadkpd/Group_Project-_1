<?php

namespace App\Models;

use Core\Model;
use PDO;
use PDOException;

class EditProfileModel extends \Core\Model
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

    // Start of updating password
    public  function saveNewPassword($user)
    {
        // Validating new password
        $this->validatePassword();

        // If valid
        if (empty($this->errors)) {
            // Compare the old password's hash and the user entered old password's hash
            if (password_verify($this->oldPassword, $user->password)) {
                // Create hash for password and save in password variable
                $passwords = password_hash($this->newPassword, PASSWORD_DEFAULT);

                //  Update password in the database
                $sql = 'UPDATE user
                SET user.password =:passwords
                WHERE username=:username';

                $db = static::getDB();
                $stmt = $db->prepare($sql);
                
                //Binding the username and password
                $stmt->bindValue(':username', $user->username, PDO::PARAM_STR);
                $stmt->bindValue(':passwords',$passwords, PDO::PARAM_STR);

                // Fetch and return the retrieved results
                return $stmt->execute();
            }
            //Old password entered is invalid
            else{
                return false;
            }
        }
        //Backend validation errors in password
        else{
            return false;
        }
    }
    // End of updating password

    // Start of updating forgotten password
    public function saveForgotPassword($mobile)
    {
        // Validating user entered new password
        $this->validatePassword();

        // If valid
        if (empty($this->errors)) {
            // Create hash for password and save in password variable
            $passwords = password_hash($this->newPassword, PASSWORD_DEFAULT);
            
            //  Update password in the database
            $sql = 'UPDATE user
            SET user.password =:passwords
            WHERE primary_contact=:mobile';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            //Binding the mobile number and password
            $stmt->bindValue(':mobile', $mobile, PDO::PARAM_INT);
            $stmt->bindValue(':passwords',$passwords, PDO::PARAM_STR);

            // Fetch and return the retrieved results
            return $stmt->execute();
        }  
        //If new password has erros with backend validation   
        else{
            return false;
        }
    }
    // End of updating forgotten password

    // Start of update new user deatils
    public function updateNewUserDetails($oldUsername)
    {
        // If user didn't update his username 
        if($oldUsername==$this->username){
            //Check this commented line below
            // $this->validateDetails();
            
            // If valid
            if (empty($this->errors)){
            
                // Update first name and last name in the database  
            $sql = 'UPDATE user
            SET user.first_name =:firstName,
            user.last_name=:lastName
            WHERE username=:oldUsername;';
    
            $db = static::getDB();
            $stmt = $db->prepare($sql);

            // Binding the old username, firstname and lastname
            $stmt->bindValue(':oldUsername', $oldUsername, PDO::PARAM_STR);
            $stmt->bindValue(':firstName',$this->firstName, PDO::PARAM_STR);
            $stmt->bindValue(':lastName',$this->lastName, PDO::PARAM_STR);  

            // Fetch and return the retrieved results
            return $stmt->execute();
            }
            // If there are errors with backend validation
            else{
                return false;
            }
        }
        // If user updates his username 
        else{

            // Validate details and the username
            $this->validateDetails();
            $this->validateUsername();

            // If valid
            if (empty($this->errors)) {
            
            // Update firstname,lastname and username in the database
            $sql = 'UPDATE user
            SET user.first_name =:firstName,
            user.last_name=:lastName,user.username=:username
            WHERE username=:oldUsername;';
    
            $db = static::getDB();
            $stmt = $db->prepare($sql);

            // Binding the old username, new username, firstname and lastname
            $stmt->bindValue(':oldUsername', $oldUsername, PDO::PARAM_STR);
            $stmt->bindValue(':username', $this->username, PDO::PARAM_STR);
            $stmt->bindValue(':firstName',$this->firstName, PDO::PARAM_STR);
            $stmt->bindValue(':lastName',$this->lastName, PDO::PARAM_STR);

            // Fetch and return the retrieved results
            return $stmt->execute();
            }
            // If there are errors with backend validation
            else{
                return false;
            }
        }
    }
    // End of update new user deatils


    // Start of update new mobile number
    public static  function updateNewMobileNumber($username,$mobile_number)
    {
            //Check this code chunk
            // $this->validateMobileNumber();

            // if (empty($this->errors)) {
            //     $sql = 'UPDATE user
            // SET user.primary_contact =:mobile_number
            // WHERE username=:username;';
    
            // $db = static::getDB();
            // $stmt = $db->prepare($sql);
            // $stmt->bindValue(':username', $username, PDO::PARAM_STR);
            // $stmt->bindValue(':mobile_number',$mobile_number, PDO::PARAM_STR);
            
            
            // return $stmt->execute();
            // }
    
            // else{
            //     return false;
            // }


            // Update mobile number in the database
            $sql = 'UPDATE user
            SET user.primary_contact =:mobile_number
            WHERE username=:username;';
    
            $db = static::getDB();
            $stmt = $db->prepare($sql);

            // Binding username and mobile number
            $stmt->bindValue(':username', $username, PDO::PARAM_STR);
            $stmt->bindValue(':mobile_number',$mobile_number, PDO::PARAM_STR);
            
            // Fetch and return the retrieved results
            return $stmt->execute();
    }
    // End of update new mobile number

    // Start of find user by ID
    public static function findByID($id)
    {
        // Retrieve all details of user from the dataabse
        $sql = 'SELECT * FROM user WHERE user_id = :id';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        // Binding user id and Converting retrieved data from database into PDO
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        // Fetch and return the retrieved results
        return $stmt->fetch();
    }
    // End of find by ID


    // Start of find by mobile number
    public static function findByMobileNumber($mobile_number)
    {
        // Retrieve all details of user from the database
        $sql = 'SELECT * FROM user WHERE primary_contact = :mobile_number AND account_status= "active"';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        
        // Binding user id and Converting retrieved data from database into PDO
        $stmt->bindValue(':mobile_number', $mobile_number, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        // Fetch and return the retrieved results
        return $stmt->fetch();
    }
    // End of find by mobile number


    // Start of find by username
    public static function findByUsername($username)
    {
        // Retrieve all details of user from the database
        $sql = 'SELECT * FROM user WHERE username = :username AND account_status= "active"';

        $db = static::getDB();
        $stmt = $db->prepare($sql);

        // Binding user id and Converting retrieved data from database into PDO
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());
        $stmt->execute();

        // Fetch and return the retrieved results
        return $stmt->fetch();
    }
    // End of find by username

    // Start of checking username exists
    public static function usernameExists($username)
    {
        // Return if username exists
        return static::findByUsername($username) !== false;
    }
    // End of checking username exists

    // Start of checking mobile number exists
    public static function mobileNumberExists($mobile_number)
    {
        // Return if mobile number exists
        return static::findByMobileNumber($mobile_number) !== false;    
    }
    // End of checking mobile number exists

    // Start of validating details
    public function validateDetails()
    {
        // First Name
        if ($this->firstName == '') {
            $this->errors["first_name1"] = 'First Name is required';
        }
        // letter match
        elseif (preg_match('/^[a-zA-Z ]+$/', $this->firstName) == 0) {
            $this->errors["first_name2"] = 'First Name should consists of only letters';
        }
        
        // Last Name
        if ($this->lastName == '') {
            $this->errors["last_name1"] = 'Last Name is required';
        }
        //letter match
        elseif (preg_match('/^[a-zA-Z ]+$/', $this->firstName) == 0) {
            $this->errors["last_name2"] = 'Last Name should consists of only letters';
        }

        // username
        if ($this->username == '') {
            $this->errors["username1"] = 'Username is required';
        }
        elseif (static::usernameExists($this->username)) {
            $this->errors["username2"] = 'Username is already taken';
        }
        //Finally, returning all the errors
        return $this->errors;
    
    }
    // End of validating details


    // Start of validating password
    public function validatePassword()
    {
        // Password
        if ($this->newPassword == '') {
            $this->errors["password1"] = 'Password is required';
        }
        elseif (strlen($this->newPassword) < 8) {
            $this->errors["password2"] = 'Please enter at least 8 characters for the password';
        }
        // Letter match
        elseif (preg_match('/.*[a-z]+.*/i', $this->newPassword) == 0) {
            $this->errors["password3"] = 'Password needs at least one simple letter';
        }
        elseif (preg_match('/.*[A-Z]+.*/i', $this->newPassword) == 0) {
            $this->errors["password4"] = 'Password needs at least one capital letter';
        }
        //number match
        elseif (preg_match('/.*\d+.*/i', $this->newPassword) == 0) {
            $this->errors["password5"] = 'Password needs at least one number';
        }
        //character match
        elseif (preg_match('/.*[!@#$%^&*-].*/i', $this->newPassword) == 0) {
            $this->errors["password6"] = 'Password needs at least one character';
        }
        //Finally, returning all the errors
        return $this->errors;
    }
    // End of validating password

    // Start of validating username
    public function validateUsername()
    {
        // username
        if ($this->username == '') {
            $this->errors["username1"] = 'Username is required';
        }
        elseif (static::usernameExists($this->username)) {
            $this->errors["username2"] = 'Username is already taken';
        }
         //Finally, returning all the errors
        return $this->errors;
    }
    // End of validating username

    // Start of validating mobile number
    public function validateMobileNumber()
    {
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
    }
    // End of validating mobile number
}