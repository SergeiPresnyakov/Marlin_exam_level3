<?php
include '../Components/Router.php';
include '../Components/Input.php';
include '../Components/Flash.php';
include '../Components/Validator.php';
$config = include '../config.php';

Router::config($config['router']);
Router::page($_SERVER['REQUEST_URI']);