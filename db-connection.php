<?php
require_once 'constants.php';

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

function db_connection(): mysqli
{
    [
        'SERVERNAME' => $servername,
        'USERNAME' => $username,
        'PASSWORD' => $password,
        'DATABASE' => $database,
        'PORT' => $port,
        'SERVER_ERROR_MESSAGE' => $server_error_message,
    ] = CONSTANTS;
    try {
        $conn = new mysqli($servername, $username, $password, $database, $port);
    } catch (mysqli_sql_exception $exception) {
        error_log("Error: " . $exception->getMessage());
        exit($server_error_message);
    }

    return $conn;
}
