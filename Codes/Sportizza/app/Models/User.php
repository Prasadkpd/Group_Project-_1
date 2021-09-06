<?php

namespace App\Models;

use PDO;

/**
 * Sports Arena Registration model
 *
 * PHP version 7.4.12
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
   * @param array $data  Initial property values
   *
   * @return void
   */
  public function __construct($data)
  {
    foreach ($data as $key => $value) {
      $this->$key = $value;
    };
  }

  /**
   * Save the sports arena registration model with the current property values
   *
   * @return boolean True if the application is submitted successfully, false otherwise
   */
  public function spArenaReg()
  {
    $this->validate();

    if (empty($this->errors)) {

    $sql = 'INSERT INTO sports_arena_reg (sa_name, contact, category, location, google_map_link, 
    description, other_facilities, cash_payment,card_payment, file)
            VALUES (:name, :contact, :category, :location, :google_map_link, :description, 
            :other_facilities, :cash_payment, :card_payment, :file)';
        
    $db = static::getDB();
    $stmt = $db->prepare($sql);

    
    $stmt->bindValue(':name', $this->name, PDO::PARAM_STR);
    $stmt->bindValue(':contact', $this->contact, PDO::PARAM_INT);
    $stmt->bindValue(':category', $this->category, PDO::PARAM_STR);
    $stmt->bindValue(':location', $this->location, PDO::PARAM_STR);
    $stmt->bindValue(':map_link', $this->map_link, PDO::PARAM_STR);
    $stmt->bindValue(':description', $this->description, PDO::PARAM_STR);
    $stmt->bindValue(':other_facilities', $this->other_facilities, PDO::PARAM_STR);
    $stmt->bindValue(':cash_payment', $this->cash_payment, PDO::PARAM_BOOLEAN);
    $stmt->bindValue(':card_payment', $this->card_payment, PDO::PARAM_BOOLEAN);

    $stmt[':files'] = json_encode($files);
    $stmt->execute();
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
        // Sp Arena Name
        if ($this->name == '') {
            $this->errors[] = 'Sports Arena Name is required';
        }
        //Contact
        if(!(preg_match("/^[0]-[0-9]{9}$/", $contact))) {
          $this->errors[] = 'Contact Number is invalid';
        }
        //Payment
        if ($this->cash_payment =='FALSE' && this->card_payment == 'FALSE') {
          $this->errors[] = 'Please enter a payment method(s)';
    }
    //Checking whether a Sports Arena with same name, location and category exists
    if ($this->spArenaExists($this->name, $this->location, $this->category)) {
      $this->errors[] = 'Sports Arena already exists under the above name & category in the stated location';
  }
}

    /**
     * See if a sports arena record already exists with the sports arena name, category and location
     *
     * @param string $email email address to search for
     *
     * @return boolean  True if a record already exists, false otherwise
     */
    protected function spArenaExists($name, $location, $category)
    {
        $sql = 'SELECT * FROM sports_arena_profile  
        WHERE sa_name = :name AND location = :location AND category= :category';

        $db = static::getDB();
        $stmt = $db->prepare($sql);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':location', $location, PDO::PARAM_STR);
        $stmt->bindParam(':category', $category, PDO::PARAM_STR);

        $stmt->execute();

        return $stmt->fetch() !== false;
    }
}
