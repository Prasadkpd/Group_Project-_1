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


/**
 * User model
 *
 * PHP version 7.0
 */
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

            $password_hash = password_hash($this->password, PASSWORD_DEFAULT);

            $sql = 'INSERT INTO users (name, email, password_hash)
                    VALUES (:name, :email, :password_hash)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
            $stmt->bindValue(':email', $this->email, PDO::PARAM_STR);
            $stmt->bindValue(':password_hash', $password_hash, PDO::PARAM_STR);

            return $stmt->execute();
        }

        return false;
    }

    /**
     * Validate current property values, adding valiation error messages to the errors array property
     *
     * @return void
     */
    // public function validate()
    // {
    //     // Name
    //     if ($this->name == '') {
    //         $this->errors[] = 'Name is required';
    //     }

    //     // email address
    //     if (filter_var($this->email, FILTER_VALIDATE_EMAIL) === false) {
    //         $this->errors[] = 'Invalid email';
    //     }
    //     if (static::emailExists($this->email)) {
    //         $this->errors[] = 'email already taken';
    //     }

    //     // Password
    //     if (strlen($this->password) < 6) {
    //         $this->errors[] = 'Please enter at least 6 characters for the password';
    //     }

    //     if (preg_match('/.*[a-z]+.*/i', $this->password) == 0) {
    //         $this->errors[] = 'Password needs at least one letter';
    //     }

    //     if (preg_match('/.*\d+.*/i', $this->password) == 0) {
    //         $this->errors[] = 'Password needs at least one number';
    //     }
    // }

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

    /**
     * Find a user model by username
     *
     * @param string $username username to search for
     *
     * @return mixed User object if found, false otherwise
     */
    public static function findByUsername($username)
    {
        $sql = 'SELECT * FROM users WHERE username = :username';

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

