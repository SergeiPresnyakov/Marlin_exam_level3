<?php
$db = require_once 'dbstart.php';
$db->delete('users', $_GET['id']);
header("Location: /");