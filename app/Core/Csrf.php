<?php

namespace App\Core;

class Csrf
{
    public static function generateToken(): string
    {
        Session::start();
        $token = bin2hex(random_bytes(32));
        Session::set('csrf_token', $token);
        return $token;
    }

    public static function getToken(): string
    {
        Session::start();
        if (!Session::has('csrf_token')) {
            return self::generateToken();
        }
        return Session::get('csrf_token');
    }

    public static function validateToken(?string $token): bool
    {
        Session::start();

        if (empty($token)) {
            return false;
        }

        $sessionToken = Session::get('csrf_token');

        if (empty($sessionToken)) {
            return false;
        }

        return hash_equals($sessionToken, $token);
    }

    public static function field(): string
    {
        $token = self::getToken();
        return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars($token, ENT_QUOTES, 'UTF-8') . '">';
    }

    public static function verify(): bool
    {
        $token = $_POST['csrf_token'] ?? null;

        if (!self::validateToken($token)) {
            self::generateToken();
            return false;
        }

        return true;
    }
}
