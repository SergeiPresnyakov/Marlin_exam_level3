<?php
session_start();
include 'Components/Connection.php';
include 'Components/QueryBuilder.php';
$config = include 'config.php';
$connection = new QueryBuilder(Connection::make($config['database']));

return $connection;