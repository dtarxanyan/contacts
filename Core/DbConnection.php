<?php
namespace Core;

class DbConnection
{
    private static $connection = null;

    private function __construct()
    {
    }

    private function __clone()
    {
    }

    public static function getConnection()
    {
        if (self::$connection === null) {
            $dbConfig = Application::getConfig('database');
            self::$connection = new \PDO(
                "mysql:host=" . $dbConfig['servername'] . ";dbname=" . $dbConfig['dbname'],
                $dbConfig['username'],
                $dbConfig['password'],
                [
                    \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
                    \PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES \'UTF8\'',
                    \PDO::MYSQL_ATTR_LOCAL_INFILE => true,
                ]
            );
        }

        return self::$connection;
    }
}