<?php
require_once "../db-connection.php";
require_once "../utils.php";

session_start();
$conn = db_connection();

["SERVER_ERROR_MESSAGE" => $error, "TRAINER_DASHBOARD_PAGE" => $trainer_dashboard] = CONSTANTS;

$data = [
    "trainer-id" => $_SESSION["id"] ?? null,
    "activity-id" => $_GET["id"] ?? null,
    "current-page" => $_GET["page"] ?? null,
];
$validated_data = filter_var_array($data, FILTER_VALIDATE_INT);

if (in_array(null, $validated_data)) {
    exit($error);
}

["trainer-id" => $trainer_id, "activity-id" => $activity_id, "current-page" => $page] = $validated_data;

$serializer = [
    "activity-name" => [
        "filter" => FILTER_VALIDATE_REGEXP,
        "options" => array("regexp" => "/[A-Z][a-zA-Z]+/"),
    ],
    "weekday" => [
        "filter" => FILTER_CALLBACK,
        "options" => "correct_weekday",
    ],
    "start-hour" => FILTER_FLAG_NONE,
    "end-hour" => FILTER_FLAG_NONE,
    "room" => [
        "filter" => FILTER_CALLBACK,
        "options" => "correct_room",
    ],
];

$query_data = filter_var_array($_POST, $serializer);
$start = date_create($query_data["start-hour"])->getTimestamp();
$end = date_create($query_data["end-hour"])->getTimestamp();

if (!in_array(null, $query_data) && ($end - $start) > 0) {
    $_SESSION["errors"] = [];

    $query = "UPDATE Activities SET Name=?, DayOfWeek=?, StartTime=?, EndTime=?, RoomID=? WHERE ID=? AND TrainerID=?";
    try {
        perform_query($conn, $query, array_values([...$query_data, $activity_id, $trainer_id]), "sssssss");
    } catch (mysqli_sql_exception $exception) {
        error_log($exception->getMessage());
        exit($error);
    }
} else {
    if (!$query_data["activity-name"]) {
        $errors["activity-name"] = "<p class='error'>Minimum 2 letters and first capitalized</p>";
    }

    if (($end - $start) <= 0) {
        $errors["time"] = "<p class='error'>End time has to be greater than start time</p>";
    }

    if (!$query_data["weekday"]) {
        $errors["weekday"] = "<p class='error'>Incorrect weekday</p>";
    }

    if (!$query_data["room"]) {
        $errors["room"] = "<p class='error'>Incorrect room number</p>";
    }

    $_SESSION["errors"] = $errors;
}

$redirect_path = "Location: " . $trainer_dashboard . "?page=$page";
if (count($_SESSION["errors"])) {
    $redirect_path .= "&modal=$activity_id";
}
header($redirect_path);
