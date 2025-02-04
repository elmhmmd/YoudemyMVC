<?php
    namespace App\Lib;
    class Database {
        private static $instance ;
        public static function getConnection()
        {
            if (self::$instance == null) {
                $host = DB_HOST;
                $port = DB_PORT;
                $dbname = DB_NAME;
                $dbUser = DB_USER;
                $dbPass = DB_PASS;
                try {
                    $dsn = "mysql:host={$host};port={$port};dbname={$dbname}";
                    $pdo = new \PDO($dsn, $dbUser, $dbPass);
                    $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                    self::$instance = $pdo;
                } catch (\PDOException $exception) {
                    die("Database connection failed: " . $exception->getMessage());
                }
            }
            return self::$instance;
        }
    }