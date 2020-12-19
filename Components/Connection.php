<?php

class Connection
{
    private static $pdo;

    /**
     * Создает подключение к БД используя PDO
     * Arguments:
     *      $congig - Array
     * 
     * Returns: PDO object
     * 
     * Example:
     *      $config = [
     *      'host' => 'localhost',
     *      'database' => 'mydatabase',
     *      'charset' => 'utf8' (In case of charset is 'utf8' this field is optional)
     *      'username' => 'root',
     *      'password' => '' (In case of there is no password, this field is optional)
     *  ];
     */
    public static function make($config)
    {
        if (!isset($config['charset'])) {
            $config['charset'] = 'utf8';
        }

        if (!isset($config['password'])) {
            $config['password'] = '';
        }

        return new PDO(
            "mysql:host={$config['host']};
            dbname={$config['database']};
            charset={$config['charset']}",
            $config['username'],
            $config['password']
        );
    }
}