<?php

class Input
{
    /**
     * Проверка того, была ли отправлена какая-нибудь форма
     * Принимает метод формы
     * 
     * Arguments:
     *      type - String
     * 
     * Returns: bool
     */
    public static function exists($type = 'post')
    {
        switch ($type) {
            case 'post':
                return (!empty($_POST));
            case 'get':
                return (!empty($_GET));
            default:
                return false;
            break;
        }
    }

    /**
     * Получить введенный в форме элемент
     * Если такой существует
     * 
     * Arguments:
     *      item - String
     * 
     * Returns: String
     * 
     * Example:
     * Input::get('username');
     */
    public static function get($item)
    {
        if (isset($_POST[$item])) {
            return $_POST[$item];
        } else if (isset($_GET[$item])) {
            return $_GET[$item];
        }

        return '';
    }
}