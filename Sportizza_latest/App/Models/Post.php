<?php

namespace App\Models;

use Core\Model;
use PDO;
use PDOException;


class Post extends Model
{

    public static function getAll(): array
    {

        try {
            $db = static::getDB();

            $stmt = $db->query('SELECT id, title, content FROM posts 
                                ORDER BY created_at');
            return $stmt->fetchAll(PDO::FETCH_ASSOC);

        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
