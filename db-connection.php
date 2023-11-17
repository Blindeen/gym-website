<?php

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

$servername = "localhost";
$port = 3306;
$username = "windows";
$password = "qwerty123";
$database = "gym";

try {
    $conn = new mysqli($servername, $username, $password, $database, $port);
} catch (mysqli_sql_exception $exception) {
    die("Connection failed: " . $exception->getMessage());
}
