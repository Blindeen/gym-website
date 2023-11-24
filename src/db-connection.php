<?php
require_once 'constants.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function db_connection() {
    try {
        $conn = new mysqli(SERVERNAME, USERNAME, PASSWORD, DATABASE, PORT);
    } catch (mysqli_sql_exception $exception) {
        error_log("Error: " . $exception->getMessage());
        exit('Something went wrong. Please try again later.');
    }

    return $conn;
}
