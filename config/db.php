<?php

$db_name = 'e_learning';
$username = 'knowlyuser';
$password = 'YourPassword';
$host = 'localhost';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$conn = new mysqli($host, $username, $password, $db_name);

if ($conn -> connect_error) {
    echo 'koneksi gagal ' .$conn -> connect_error;
}
