<?php
require_once "../db-connection.php";
require_once "../utils.php";

session_start();
$conn = db_connection();

["SERVER_ERROR_MESSAGE" => $error, "TRAINER_DASHBOARD_PAGE" => $trainer_dashboard] = CONSTANTS;

$data = [
    "activity-id" => $_GET["id"] ?? null,
    "trainer-id" => $_SESSION["id"] ?? null,
];
$validated_data = filter_var_array($data, FILTER_VALIDATE_INT);
if (in_array(null, $validated_data)) {
    exit($error);
}

$params = array_values([...$validated_data]);
try {
    perform_query($conn, "DELETE FROM Activities WHERE ID=? AND TrainerID=?", $params, "ss");
} catch (mysqli_sql_exception $exception) {
    error_log($exception->getMessage());
    exit($error);
}

header("Location: " . $trainer_dashboard);
