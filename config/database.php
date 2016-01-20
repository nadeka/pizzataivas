<?php

define('DB_OPTS', parse_url(getenv('DATABASE_URL')));
define('SSL_MODE', 'require');

class DB
{
    
    public static function connection()
    {       
        $connection_info = 'pgsql:dbname='.ltrim(DB_OPTS["path"],'/').';host='.DB_OPTS["host"].
               ';port='.DB_OPTS["port"].';sslmode='.SSL_MODE;

        try {
            $connection = new PDO($connection_info, DB_OPTS["user"], DB_OPTS["pass"]);

            return $connection;
        } catch (PDOException $exception) {
            echo $exception->getMessage();
            print_r($exception->getTrace());
        }
    }
}
