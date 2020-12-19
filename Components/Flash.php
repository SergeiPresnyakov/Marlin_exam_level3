<?php

class Flash
{
    public static function set($theme, $message = '')
    {
        $_SESSION[$theme] = $message;
    }

    public static function exists($theme)
    {
        return (isset($_SESSION[$theme]) && $_SESSION[$theme] !== '');
    }

    public static function display($theme)
    {
        if (self::exists($theme)) {
            $flash_message = $_SESSION[$theme];
            unset($_SESSION[$theme]);
            return $flash_message;
        }
    }
}