<?php


namespace App\Models;
use PDO;
use PDOException;


class AdminManageUser extends \Core\Model
{
    public static function getAll(): array
    {

        try {
            $db = static::getDB();

            $stmt = $db->query('SELECT user_id, username, first_name, last_name, primary_contact, 
                                        secondary_contact FROM user');
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}