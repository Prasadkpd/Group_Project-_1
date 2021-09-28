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
    public static function PasswordValidate($username, $oldpassword,$newpassword)
    {
        $user = static::findByUsername($username);

        if ($user) {
            if (password_verify($oldpassword, $user->password)) {
                return EditProfileModel::addNewPassword($username,$newpassword);
            }
        }

        return false;
    }


    public static function addNewPassword($username,$password)
    {
        // $sql = 'SELECT * FROM user WHERE username = :username AND account_status= "active"';
        $passwords = password_hash($password, PASSWORD_DEFAULT);
        $sql = 'UPDATE user
        SET user.password =:passwords
        WHERE username=:username;';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->bindValue(':passwords',$passwords, PDO::PARAM_STR);
        
        
        return $stmt->execute();
        // $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        

        // $stmt->fetch();
        // return true;
    }
    public static function UsernameValidate($username,$newUsername)
    {
        $user = static::findByUsername($newUsername);

        if ($user) {
            return false;
        }

       else{
        EditProfileModel::updateNewUsername($username,$newUsername);
        return true;
       }
    }
    public static function updateNewUsername($username,$newUsername)
    {
        // $sql = 'SELECT * FROM user WHERE username = :username AND account_status= "active"';
       
        $sql = 'UPDATE user
        SET user.username =:newUsername
        WHERE username=:username;';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->bindValue(':newUsername',$newUsername, PDO::PARAM_STR);
        
        
        return $stmt->execute();
        // $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        

        // $stmt->fetch();
        // return true;
    }
    public static function updateUserDetails($username,$firstName,$lastName)
    {
        // $sql = 'SELECT * FROM user WHERE username = :username AND account_status= "active"';
       
        $sql = 'UPDATE user
        SET user.first_name =:firstName,
        user.last_name=:lastName
        WHERE username=:username;';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);
        $stmt->bindValue(':firstName',$firstName, PDO::PARAM_STR);
        $stmt->bindValue(':lastName',$lastName, PDO::PARAM_STR);
        
        
        return $stmt->execute();
        // $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        

        // $stmt->fetch();
        // return true;
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
