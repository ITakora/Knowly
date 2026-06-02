<?php

$db_name = 'e_learning';
$username = 'root';
$password = '';
$host = 'localhost';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {

    $conn = new mysqli($host, $username, $password, $db_name);
    
} catch (mysqli_sql_exception $e) {

    die("Database connection failed: " . $e->getMessage());
}
