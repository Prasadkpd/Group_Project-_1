<?php

namespace App\Models;
use Core\Model;
use PDO;
use PDOException;

// class User extends Model
// {

//     public static function findbyUsername($username)
//     {
//         try {
//             $db = static::getDB();
//             $stmt = $db->query('SELECT * FROM users WHERE username = :username');
//             stmt->bindVlaue(':email', $email, PDO::PARAM_STR);
//             stmt->execute();
//             return $stmt->execute();

//         } catch (PDOException $e) {
//             echo $e->getMessage();
//         }
//     }
// } 
class User extends \Core\Model
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
     * Save the user model with the current property values
     *
     * @return boolean  True if the user was saved, false otherwise
     */
    public function save()
    {
        $this->validate();

        if (empty($this->errors)) {

            $password = password_hash($this->password, PASSWORD_DEFAULT);

            $sql = 'INSERT INTO user (first_name, last_name, primary_contact, username, password)
                    VALUES (:first_name, :last_name, :mobile_number, :username, :password)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':first_name', $this->first_name, PDO::PARAM_STR);
            $stmt->bindValue(':last_name', $this->last_name, PDO::PARAM_STR);
            $stmt->bindValue(':mobile_number', $this->primary_contact, PDO::PARAM_INT);
            $stmt->bindValue(':username', $this->username, PDO::PARAM_STR);
            $stmt->bindValue(':password', $password, PDO::PARAM_STR);

            return $stmt->execute();
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
        // First Name
        if ($this->first_name == '') {
            $this->errors[] = 'First Name is required';
        }
        //letter match
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
        if ($this->primary_contact == '') {
            $this->errors[] = 'Mobile number is required';
        }
        if (preg_match('/.*07[0-9]{8}+.*/', $this->primary_contact) == 0) {
            $this->errors[] = 'Mobile number entered is invalid';
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


   
    /**
     * Find a user model by username
     *
     * @param string $username username to search for
     *
     * @return mixed User object if found, false otherwise
     */
    public static function findByUsername($username)
    {
        $sql = 'SELECT * FROM user WHERE username = :username';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindValue(':username', $username, PDO::PARAM_STR);

        $stmt->setFetchMode(PDO::FETCH_CLASS, get_called_class());

        $stmt->execute();

        return $stmt->fetch();
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
            if (password_verify($password, $user->password_hash)) {
                return $user;
            }
        }

        return false;
    }
}
