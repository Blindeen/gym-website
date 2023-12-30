<?php
require_once "../components/table/index.php";
require_once "../constants.php";
require_once "../db-connection.php";

$conn = db_connection();
["SERVER_ERROR_MESSAGE" => $error] = CONSTANTS;

$columns = ["Name", "Action"];
$action = fn($row) => "<td><button class='like-button' type='button' data-id='$row[0]' data-name='$row[1]'>Like</button></td>";
$query = "SELECT ID, Name FROM Activities";

$per_page = 4;
$pagination_query = "SELECT COUNT(*) FROM Activities";
try {
    $rows_quantity = $conn->query($pagination_query)->fetch_row()[0];
} catch (mysqli_sql_exception $exception) {
    error_log($exception->getMessage());
    exit($error);
}

$options = [
    "options" => [
        "default" => 1,
        "min_range" => 0,
        "max_range" => ceil($rows_quantity / $per_page),
    ],
];
$page = filter_var($_GET["page"] ?? null, FILTER_VALIDATE_INT, $options);

try {
    table($conn, $columns, $query, $page, $per_page, $action, true, $pagination_query);
} catch (mysqli_sql_exception $exception) {
    error_log($exception->getMessage());
    exit($error);
}
