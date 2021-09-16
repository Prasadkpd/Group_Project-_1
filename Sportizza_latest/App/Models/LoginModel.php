<?php

namespace App\Models;

use PDO;

/**
 * User model
 *
 * PHP version 7.0
 */
class LoginModel extends \Core\Model
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

            $sql = 'INSERT INTO users (name, username, password_hash)
                    VALUES (:name, :username, :password_hash)';

            $db = static::getDB();
            $stmt = $db->prepare($sql);

            
            $stmt->bindValue(':username', $this->username, PDO::PARAM_STR);
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
    public function validate()
    {
        // Name
        if ($this->name == '') {
            $this->errors[] = 'Name is required';
        }

        // username address
        if (filter_var($this->username, FILTER_VALIDATE_EMAIL) === false) {
            $this->errors[] = 'Invalid username';
        }
        if (static::usernameExists($this->username)) {
            $this->errors[] = 'username already taken';
        }

        // Password
        if (strlen($this->password) < 6) {
            $this->errors[] = 'Please enter at least 6 characters for the password';
        }

        if (preg_match('/.*[a-z]+.*/i', $this->password) == 0) {
            $this->errors[] = 'Password needs at least one letter';
        }

        if (preg_match('/.*\d+.*/i', $this->password) == 0) {
            $this->errors[] = 'Password needs at least one number';
        }
    }

    /**
     * See if a user record already exists with the specified username
     *
     * @param string $username username address to search for
     *
     * @return boolean  True if a record already exists with the specified username, false otherwise
     */
    public static function usernameExists($username)
    {
        return static::findByUsername($username) !== false;
    }

    /**
     * Find a user model by username address
     *
     * @param string $username username address to search for
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
     * Authenticate a user by username and password.
     *
     * @param string $username username address
     * @param string $password password
     *
     * @return mixed  The user object or false if authentication fails
     */
    public static function authenticate($username, $password)
    {
        $user = static::findByUsername($username);
        // var_dump($user);

        if ($user) {
            if (password_verify($password, $user->password)) {
                return $user;
            }
        }

        return false;
    }
}
