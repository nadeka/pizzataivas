<?php

define('DB_TYPE', 'pgsql');
define('DB_HOST', 'ec2-50-16-229-89.compute-1.amazonaws.com');
define('DB_PORT', 5432);
define('DB_NAME', 'ddoevqqnab986e');
define('DB_USERNAME', 'ugdvljfraksedo');
define('DB_PASSWORD', 'Yh3UmCZ8By30Sf56U2GbDnw7MV');
define('SSL_MODE', 'require');

class DB {   
   
    public static function connection() {
        $dsn = DB_TYPE . ':dbname=' . DB_NAME . ';host=' . DB_HOST . 
               ';port=' . DB_PORT . ';sslmode=' . SSL_MODE;

        try {
           $db = new PDO($dsn, DB_USERNAME, DB_PASSWORD);
           return $db;
        } catch (PDOException $e) {
           echo $e->getMessage();
           print_r($e->getTrace());
        }        
    }
}
