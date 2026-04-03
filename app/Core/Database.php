<?php

namespace App\Core;

use PDO;

class Database
{
    private static $instance = null;

    public static function getInstance()
    {
        if (self::$instance === null) {
            $host = getenv('DB_HOST') ?: 'db';
            $dbname = getenv('DB_NAME') ?: 'quai_antique';
            $username = getenv('DB_USER') ?: 'quai_user';
            $password = getenv('DB_PASSWORD') ?: 'quai_password';
            $port = getenv('DB_PORT') ?: '3306';

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci"
            ];

            self::$instance = new PDO(
                "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4",
                $username,
                $password,
                $options
            );
        }

        return self::$instance;
    }
}
