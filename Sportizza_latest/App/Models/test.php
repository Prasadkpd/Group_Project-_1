<?php

namespace App\Models;

use PDO;
use PDOException;
class Test extends \Core\Model{

    public static function getAll(){
        // $host="localhost";
        // $dbname="sportizza";
        // $username="root";
        // $password="";

        try {
            $db = static::getDB();
            // $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8",
            //         $username, $password);

            $stmt = $db->query('SELECT * FROM user');
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}