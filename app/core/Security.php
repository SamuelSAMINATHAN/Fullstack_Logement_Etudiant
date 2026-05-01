<?php

class Security
{
    // ----------------------------------------------------------
    //  hashPassword(string $password): string
    // ----------------------------------------------------------
    public static function hashPassword(string $password): string
    {
        return password_hash($password, PASSWORD_BCRYPT);
    }

    // ----------------------------------------------------------
    //  verifyPassword(string $password, string $hash): bool
    // ----------------------------------------------------------
    public static function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    // ----------------------------------------------------------
    //  generateToken(int $length = 32): string
    //  Génère un token sécurisé (ex: reset password)
    // ----------------------------------------------------------
    public static function generateToken(int $length = 32): string
    {
        return bin2hex(random_bytes($length));
    }

    // ----------------------------------------------------------
    //  csrfToken(): string
    //  Génère ou retourne le token CSRF de session
    // ----------------------------------------------------------
    public static function csrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = self::generateToken(32);
        }

        return $_SESSION['csrf_token'];
    }

    // ----------------------------------------------------------
    //  verifyCsrf(string $token): bool
    // ----------------------------------------------------------
    public static function verifyCsrf(string $token): bool
    {
        if (empty($_SESSION['csrf_token'])) {
            return false;
        }

        return hash_equals($_SESSION['csrf_token'], $token);
    }

    // ----------------------------------------------------------
    //  regenerateCsrf(): void
    // ----------------------------------------------------------
    public static function regenerateCsrf(): void
    {
        $_SESSION['csrf_token'] = self::generateToken(32);
    }

    // ----------------------------------------------------------
    //  escape(string $value): string
    //  Helper pour les vues (alternative à htmlspecialchars)
    // ----------------------------------------------------------
    public static function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}