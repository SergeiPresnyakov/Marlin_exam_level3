<?php
session_start();
include 'Components/Connection.php';
include 'Components/QueryBuilder.php';
$config = include 'config.php';

return new QueryBuilder(Connection::make($config['database']));