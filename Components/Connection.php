<?php

class Connection
{
    private static $pdo;

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