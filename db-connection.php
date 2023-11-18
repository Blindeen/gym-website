<?php
require_once 'constants.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE, PORT);
} catch (mysqli_sql_exception $exception) {
    die("Connection failed: " . $exception->getMessage());
}
