<?php

namespace App\Models;

use Core\Model;
use PDO;
use PDOException;

class EditProfileModel extends \Core\Model
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
     * See if a user record already exists with the specified username
     *
     * @param string $username username to search for
     *
     * @return boolean  True if a record already exists with the specified username, false otherwise
     */
    // public static function usernameExists($username)
    // {
    //     return static::findByUsername($username) !== false;
    // }
    // //Checking whether the mobile number is already exists
    // public static function mobileNumberExists($mobile_number)
    // {
    //     return static::findByMobileNumber($mobile_number) !== false;    
    // }

    //And Check whether account_status== inactive 

    /**
     * Find a user model by username
     *
     * @param string $username username to search for
     *
     * @return mixed User object if found, false otherwise
     */

   

    

   

    
  
     /** Authenticate a user by username and password.
     *
     * @param string $username username
     * @param string $password password
     *
     * @return mixed  The user object or false if authentication fails
     */
    // public static function PasswordValidate($username, $oldpassword,$newpassword)
    // {
    //     // this->validate()
    //     $user = static::findByUsername($username);

    //     if ($user) {
    //         if (password_verify($oldpassword, $user->password)) {
    //             return EditProfileModel::addNewPassword($username,$newpassword);
    //         }
    //     }

    //     return false;
    // }


    public  function saveNewPassword($user)
    {
        $this->validatePassword();
        if (empty($this->errors)) {

            if (password_verify($this->oldPassword, $user->password)) {
                $passwords = password_hash($this->newPassword, PASSWORD_DEFAULT);
             $sql = 'UPDATE user
             SET user.password =:passwords
            WHERE username=:username';

            $db = static::getDB();
             $stmt = $db->prepare($sql);
            $stmt->bindValue(':username', $user->username, PDO::PARAM_STR);
            $stmt->bindValue(':passwords',$passwords, PDO::PARAM_STR);
        
        
            return $stmt->execute();
            }
               
            else{
                return false;
            }
        }
        
        else{
            return false;
        }
    }



    public  function saveForgotPassword($mobile)
    {
        $this->validatePassword();
        if (empty($this->errors)) {

            $passwords = password_hash($this->newPassword, PASSWORD_DEFAULT);
             $sql = 'UPDATE user
             SET user.password =:passwords
            WHERE primary_contact=:mobile';

            $db = static::getDB();
             $stmt = $db->prepare($sql);
            $stmt->bindValue(':mobile', $mobile, PDO::PARAM_STR);
            $stmt->bindValue(':passwords',$passwords, PDO::PARAM_STR);
        
        
            return $stmt->execute();
            
        }     
          
        
        
        else{
            return false;
        }
    }






    public  function updateNewUserDetails($oldUsername)
    {
        
        if($oldUsername==$this->username){
            // $this->validateDetails();

            if (empty($this->errors)) {
                $sql = 'UPDATE user
            SET user.first_name =:firstName,
            user.last_name=:lastName
            WHERE username=:oldUsername;';
    
            $db = static::getDB();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':oldUsername', $oldUsername, PDO::PARAM_STR);
            // $stmt->bindValue(':username', $this->username, PDO::PARAM_STR);
            $stmt->bindValue(':firstName',$this->firstName, PDO::PARAM_STR);
            $stmt->bindValue(':lastName',$this->lastName, PDO::PARAM_STR);
            
            
            return $stmt->execute();
            }
    
            else{
                return false;
            }
            
        }

        else{


            $this->validateDetails();
            $this->validateUsername();

            if (empty($this->errors)) {
                $sql = 'UPDATE user
            SET user.first_name =:firstName,
            user.last_name=:lastName,user.username=:username
            WHERE username=:oldUsername;';
    
            $db = static::getDB();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':oldUsername', $oldUsername, PDO::PARAM_STR);
            $stmt->bindValue(':username', $this->username, PDO::PARAM_STR);
            $stmt->bindValue(':firstName',$this->firstName, PDO::PARAM_STR);
            $stmt->bindValue(':lastName',$this->lastName, PDO::PARAM_STR);
            
            
            return $stmt->execute();
            }
    
            else{
                return false;
            }
        }




        


       
        
        // $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        

        // $stmt->fetch();
        // return true;
    }



    public static  function updateNewMobileNumber($username,$mobile_number)
    {
        
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


            
            $sql = 'UPDATE user
            SET user.primary_contact =:mobile_number
            WHERE username=:username;';
    
            $db = static::getDB();
            $stmt = $db->prepare($sql);
            $stmt->bindValue(':username', $username, PDO::PARAM_STR);
            $stmt->bindValue(':mobile_number',$mobile_number, PDO::PARAM_STR);
            
            
            return $stmt->execute();
            
        


    }


    // public static function UsernameValidate($username,$newUsername)
    // {
    //     $user = static::findByUsername($newUsername);

    //     if ($user) {
    //         return false;
    //     }

    //    else{
    //     EditProfileModel::updateNewUsername($username,$newUsername);
    //     return true;
    //    }
    // }
    // public static function updateNewUsername($username,$newUsername)
    // {
    //     // $sql = 'SELECT * FROM user WHERE username = :username AND account_status= "active"';
       
    //     $sql = 'UPDATE user
    //     SET user.username =:newUsername
    //     WHERE username=:username;';

    //     $db = static::getDB();
    //     $stmt = $db->prepare($sql);
    //     $stmt->bindValue(':username', $username, PDO::PARAM_STR);
    //     $stmt->bindValue(':newUsername',$newUsername, PDO::PARAM_STR);
        
        
    //     return $stmt->execute();
    //     // $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        

    //     // $stmt->fetch();
    //     // return true;
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

    public static function usernameExists($username)
    {
        return static::findByUsername($username) !== false;
    }

    public static function mobileNumberExists($mobile_number)
    {
        return static::findByMobileNumber($mobile_number) !== false;    
    }


    public function validateDetails()
    {
        //First Name
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

        // mobile number
        // if ($this->mobile_number == '') {
        //     $this->errors["mobile_number1"] = 'Mobile number is required';
        // }
        // elseif (preg_match('/.*07[0-9]{8}+.*/', $this->mobile_number) == 0) {
        //     $this->errors["mobile_number2"] = 'Mobile number entered is invalid';
        // }
        // elseif (static::mobileNumberExists($this->mobile_number)) {
        //     $this->errors["mobile_number3"] = 'An account already exists with this mobile number';
        // }

        // username
        if ($this->username == '') {
            $this->errors["username1"] = 'Username is required';
        }
        elseif (static::usernameExists($this->username)) {
            $this->errors["username2"] = 'Username is already taken';
        }


        return $this->errors;
    
    }

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
       
        return $this->errors;
    
    }

    public function validateUsername()
    {

        // username
        if ($this->username == '') {
            $this->errors["username1"] = 'Username is required';
        }
        elseif (static::usernameExists($this->username)) {
            $this->errors["username2"] = 'Username is already taken';
        }
        return $this->errors;
    
    }

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




}
