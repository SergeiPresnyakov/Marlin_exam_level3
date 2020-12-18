<?php

class Router
{
    private static $routes = [];

    /*
     * Передает правила маршрутизации в роутер 
     * Arguments:
     *    config - Array
     * 
     *  Returns: null
     * 
     * Example: 
     *  $config = ['/' => '../pages/mainpage.php'];
     *  Router::config($config);
     */
    public static function config($config)
    {
        self::$routes = $config;
    }

    /*
     * Возвращает массив с правилами 
     * 
     * Retuns: Array
     * 
     * Example:
     * var_dump(Router::showConfig());
     */
    public static function showConfig()
    {
        return self::$routes;
    }

    /*
     * Вызывает страницу согласно URL,
     * а если такого URL нет в правилах маршрутизации,
     * то на страницу с ошибкой 404.
     * 
     * Страница 404 должна находиться 
     * в директории на уровень выше компонента Router
     * 
     * Arguments:
     *      url - String
     * 
     * Retruns: null
     * 
     * Example:
     *  $url = $_SERVER['REQUEST_URI'];
     *  Route::page($url);
     */
    public static function page($url)
    {
        // отделим путь от get-параметров
        $parsed_url = parse_url($url);
        $path = $parsed_url['path'];

        if (array_key_exists($path, self::$routes)) {
            include self::$routes[$path];
        } else {
            include '../404.php';
        }
    }
}