<?php

namespace Core;

use PDO;
use App\Config;

/**
 * Base model
 *
 * PHP version 7.4.12
 */
abstract class Model
{

    /**
     * Get the PDO database connection
     *
     * @return mixed
     */
    
    // Static database connection:Using one database connectoin for one user request to all the methods involved in
    protected static function getDB()
    {
        static $db = null;

        if ($db === null) {
            $dsn = 'mysql:host=' . Config::DB_HOST . ';dbname=' . Config::DB_NAME . ';charset=utf8';
            $db = new PDO($dsn, Config::DB_USER, Config::DB_PASSWORD);

            // Throw an Exception when an error occurs
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        }

        return $db;
    }
<<<<<<< HEAD
    //writeData("customer", "'id', 'phone', 'nic'", "'1212', 'sumanapala', '1231231'");
    //INSERT INTO customer ('id', 'phone', 'nic') VALUES ('1212', 'sumanapala', '1231231')
=======

    // Insert query
>>>>>>> 067f62a5cfaa23412e5cbc7c8a1f5aad8fabb211
    public function writeData($tableName, $columns, $data){
        $sql = 'INSERT INTO '.$tableName.' ('.$columns.' ) VALUES ('.$data.');';

        // echo $sql;
        $result =  $this->con->query($sql);
        
        if ($result === FALSE) {
            // echo "Database Error";
          } else {
            return $result; 
        }
    }
<<<<<<< HEAD

    //getAllData(customer)
    //SELECT * FROM customer
=======
>>>>>>> 067f62a5cfaa23412e5cbc7c8a1f5aad8fabb211
    public function getAllData($tableName){
        $sql = 'SELECT * FROM '.$tableName;
        $result =  $this->con->query($sql);
        if ($result === FALSE) {
            echo "No data";
          } else {
            return $result; 
        }
    }
<<<<<<< HEAD

    //getAllDataWhere('customer', 'phone', '0771655198')
    //SELECT * FROM customer WHERE phone=0771655198
=======
>>>>>>> 067f62a5cfaa23412e5cbc7c8a1f5aad8fabb211
    public function getAllDataWhere($tableName, $column, $data){
        $sql = 'SELECT * FROM '.$tableName.' WHERE '.$column.'="'.$data.'"';
        $result =  $this->con->query($sql);

        
        if ($result === FALSE) {
            echo "No data";
          } else {
            return $result; 
        }
    }
<<<<<<< HEAD

    //getAllDataWhere('customer', 'phone', '0771655198', 'id', '11001')
    //SELECT * FROM customer WHERE phone=0771655198 AND id=11001
=======
>>>>>>> 067f62a5cfaa23412e5cbc7c8a1f5aad8fabb211
    public function getAllDataWhereAnd($tableName, $column1, $data1,$column2, $data2){
        $sql = 'SELECT * FROM '.$tableName.' WHERE '.$column1.'="'.$data1.'" AND '.$column2.'="'.$data2.'"';
        $result =  $this->con->query($sql);
        

        //echo $sql;

        if ($result === FALSE) {
            echo "No data";
          } else {
            return $result; 
        }
    }
<<<<<<< HEAD

    //getAllDataWhere('name','customer', 'phone', '0771655198')
    //SELECT name FROM customer WHERE phone=0771655198
=======
>>>>>>> 067f62a5cfaa23412e5cbc7c8a1f5aad8fabb211
    public function getSpecificDataWhere($columnName,$tableName, $column, $data){
        $sql = 'SELECT '.$columnName.' FROM '.$tableName.' WHERE '.$column.'="'.$data.'"';
        $result =  $this->con->query($sql);
        
        if ($result === FALSE) {
            echo "No data";
          } else {
            return $result; 
        }
    }
<<<<<<< HEAD

    //updateData('customer', 'phone', 0771655198', array('fname' => 'Suvin', 'lname' => 'Nimnaka' ))
    // This is equivilent to UPDATE customer SET "fname"="suvin", "lname"="nimnaka" WHERE "phone"="0771655198";
=======
>>>>>>> 067f62a5cfaa23412e5cbc7c8a1f5aad8fabb211
    public function updateData($tableName, $key, $keyvalue, $data) {
        $set = '';
        $x = 1;
    
        foreach($data as $name => $value) {
            $set .= "{$name} = \"{$value}\"";
            if($x < count($data)) {
                $set .= ',';
            }
            $x++;
        }
    
        $sql = "UPDATE {$tableName} SET {$set} WHERE {$key} = {$keyvalue}";

        $result =  $this->con->query($sql);
        //   echo $sql;
        // if(!$this->con->query($sql, $data)->error()) {
        //     return true;
        // }

    
        return false;
    }
<<<<<<< HEAD

    //deleteTable('customer', 'phone', '0771655198')
    // Equilent to DELETE FROM customer WHERE 'phone'='0771655198'
=======
>>>>>>> 067f62a5cfaa23412e5cbc7c8a1f5aad8fabb211
    function deleteData($tableName, $key, $keyvalue){
        $sql = "DELETE FROM {$tableName} WHERE {$key}='{$keyvalue}'";
        $result =  $this->con->query($sql);

        if ($result === FALSE) {
            echo "Delete Error";
        } else {
            return $result; 
        }
    }
<<<<<<< HEAD

    //For other uses
=======
>>>>>>> 067f62a5cfaa23412e5cbc7c8a1f5aad8fabb211
    public function executeSql($query){
        // echo $query;
        $result =  $this->con->query($query);
        if ($result === FALSE) {
            echo $this->con->error;
          } else {
            return $result; 
        }
    }
    
}
