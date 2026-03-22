<?php

namespace App\Core;

class Database
{
    private static ?Database $instance = null;
    private \PDO $pdo;

    private function __construct()
    {
        $host = $_ENV['DB_HOST'] ?? 'db';
        $dbname = $_ENV['DB_NAME'] ?? 'quai_antique';
        $user = $_ENV['DB_USER'] ?? 'quai_user';
        $password = $_ENV['DB_PASSWORD'] ?? 'quai_password';
        $port = $_ENV['DB_PORT'] ?? '3306';

        $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";

        $this->pdo = new \PDO($dsn, $user, $password, [
            \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES => false,
        ]);
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection(): \PDO
    {
        return $this->pdo;
    }
}
