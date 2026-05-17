<?php
class SessionManager
{
    public static function Start()
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            return;
        }

        $secure = !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';

        session_set_cookie_params([
            'lifetime' => 0,
            'path' => '/',
            'secure' => $secure,
            'httponly' => true,
            'samesite' => 'Strict'
        ]);

        session_start();
    }

    public static function Set($key, $val)
    {
        $_SESSION[$key] = $val;
    }

    public static function Get($key)
    {
        return $_SESSION[$key] ?? null;
    }

    public static function Destroy()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            return;
        }

        $_SESSION = [];
        session_unset();
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
        session_destroy();
    }

    public static function Regenerate()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            return;
        }

        session_regenerate_id(true);
    }

    public static function IsAuthenticated()
    {
        return isset($_SESSION['user_id']);
    }
}
?>