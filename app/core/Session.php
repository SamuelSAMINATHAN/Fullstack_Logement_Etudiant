<?php

class Session
{
    // ----------------------------------------------------------
    //  start()
    //  Démarre la session si elle n'est pas déjà active
    // ----------------------------------------------------------
    public static function start(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // ----------------------------------------------------------
    //  set(string $key, mixed $value)
    // ----------------------------------------------------------
    public static function set(string $key, $value): void
    {
        $_SESSION[$key] = $value;
    }

    // ----------------------------------------------------------
    //  get(string $key, mixed $default = null)
    // ----------------------------------------------------------
    public static function get(string $key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    // ----------------------------------------------------------
    //  has(string $key): bool
    // ----------------------------------------------------------
    public static function has(string $key): bool
    {
        return isset($_SESSION[$key]);
    }

    // ----------------------------------------------------------
    //  remove(string $key)
    // ----------------------------------------------------------
    public static function remove(string $key): void
    {
        unset($_SESSION[$key]);
    }

    // ----------------------------------------------------------
    //  destroy()
    //  Détruit complètement la session
    // ----------------------------------------------------------
    public static function destroy(): void
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            return;
        }

        $_SESSION = [];

        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params["path"],
                $params["domain"],
                $params["secure"],
                $params["httponly"]
            );
        }

        session_destroy();
    }

    // ----------------------------------------------------------
    //  regenerate()
    //  Régénère l'ID de session (sécurité)
    // ----------------------------------------------------------
    public static function regenerate(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_regenerate_id(true);
        }
    }

    // ----------------------------------------------------------
    //  Flash messages
    // ----------------------------------------------------------
    public static function setFlash(string $key, string $message): void
    {
        if (!isset($_SESSION['flash'])) {
            $_SESSION['flash'] = [];
        }

        $_SESSION['flash'][$key] = $message;
    }

    public static function getFlash(string $key): ?string
    {
        if (!isset($_SESSION['flash'][$key])) {
            return null;
        }

        $message = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);

        if (empty($_SESSION['flash'])) {
            unset($_SESSION['flash']);
        }

        return $message;
    }
}