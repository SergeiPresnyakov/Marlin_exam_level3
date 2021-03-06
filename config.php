<?php
return [
    // конфиг для базы данных
    'database' => [
        'host' => 'localhost',
        'database' => 'profiles',
        'charset' => 'utf8',
        'username' => 'root',
        'password' => ''
    ],

    // конфиг для роутера
    'router' => [
        '/' => '../pages/mainpage.php',
        '/about' => '../pages/about.php',
        '/create' => '../pages/create.php',
        '/create/newuser' => '../create_user.php',
        '/edit' => '../pages/edit.php',
        '/edit/user' => '../edit_user.php',
        '/delete' => '../delete_user.php'
    ]
];