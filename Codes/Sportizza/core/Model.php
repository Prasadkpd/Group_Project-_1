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

    // Insert query
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
    public function getAllData($tableName){
        $sql = 'SELECT * FROM '.$tableName;
        $result =  $this->con->query($sql);
        if ($result === FALSE) {
            echo "No data";
          } else {
            return $result; 
        }
    }
    public function getAllDataWhere($tableName, $column, $data){
        $sql = 'SELECT * FROM '.$tableName.' WHERE '.$column.'="'.$data.'"';
        $result =  $this->con->query($sql);

        
        if ($result === FALSE) {
            echo "No data";
          } else {
            return $result; 
        }
    }
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
    public function getSpecificDataWhere($columnName,$tableName, $column, $data){
        $sql = 'SELECT '.$columnName.' FROM '.$tableName.' WHERE '.$column.'="'.$data.'"';
        $result =  $this->con->query($sql);
        
        if ($result === FALSE) {
            echo "No data";
          } else {
            return $result; 
        }
    }
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
    function deleteData($tableName, $key, $keyvalue){
        $sql = "DELETE FROM {$tableName} WHERE {$key}='{$keyvalue}'";
        $result =  $this->con->query($sql);

        if ($result === FALSE) {
            echo "Delete Error";
        } else {
            return $result; 
        }
    }
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
