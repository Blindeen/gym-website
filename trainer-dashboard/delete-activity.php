<?php
require_once "../db-connection.php";
require_once "../utils.php";

session_start();
$conn = db_connection();

["SERVER_ERROR_MESSAGE" => $error, "TRAINER_DASHBOARD_PAGE" => $trainer_dashboard] = CONSTANTS;
try {
    $activity_id = $_GET["id"];
    $trainer_id = $_SESSION["id"];
    if (!is_numeric($activity_id)) {
        exit($error);
    }
    $params = [$activity_id, $trainer_id];
    perform_query($conn, "DELETE FROM Activities WHERE ID=? AND TrainerID=?", $params, "ss");
} catch (mysqli_sql_exception $exception) {
    error_log($exception->getMessage());
    exit($error);
}

header("Location: " . $trainer_dashboard);
