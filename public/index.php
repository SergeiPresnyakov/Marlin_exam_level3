<?php
include '../Components/Router.php';
$config = include '../config.php';

Router::config($config['router']);
Router::page($_SERVER['REQUEST_URI']);