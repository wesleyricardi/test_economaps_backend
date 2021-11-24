<?php
namespace App\Data;

use PDO as PDO;

abstract class DB {
    public static $connection;

    public static function Conectar() {   
        if (self::$connection==null):  
            try {
                self::$connection = new PDO(DATABASE_TYPE.':host='.DATABASE_HOST.';dbname='.DATABASE_NAME, DATABASE_USER, DATABASE_PASS);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
            
        endif;
        return self::$connection;
    }
}
