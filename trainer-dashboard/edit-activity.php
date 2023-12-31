<?php
require_once "../db-connection.php";
require_once "../utils.php";

session_start();
$conn = db_connection();
["SERVER_ERROR_MESSAGE" => $error] = CONSTANTS;

$data = [
    "trainer-id" => $_SESSION["id"] ?? null,
    "activity-id" => $_GET["id"] ?? null,
];
$validated_data = filter_var_array($data, FILTER_VALIDATE_INT);

if (in_array(null, $validated_data)) {
    exit($error);
}

["trainer-id" => $trainer_id, "activity-id" => $activity_id] = $validated_data;

$serializer = [
    "activity-name" => [
        "filter" => FILTER_VALIDATE_REGEXP,
        "options" => array("regexp" => "/[A-Z]+[a-zA-Z]+/"),
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
    $query = "UPDATE Activities SET Name=?, DayOfWeek=?, StartTime=?, EndTime=?, RoomID=? WHERE ID=? AND TrainerID=?";
    try {
        perform_query($conn, $query, array_values([...$query_data, $activity_id, $trainer_id]), "sssssss");
    } catch (mysqli_sql_exception $exception) {
        error_log($exception->getMessage());
        exit($error);
    }

    echo json_encode([
        "statusCode" => 201,
        "message" => "Activity has been updated",
    ]);
} else {
    if (!$query_data["activity-name"]) {
        echo json_encode([
            "statusCode" => 400,
            "message" => "Minimum 2 letters and first capitalized",
            "fields" => ["activity-name"],
        ]);
    } else if (($end - $start) <= 0) {
        echo json_encode([
            "statusCode" => 400,
            "message" => "End time has to be greater than start time",
            "fields" => ["start-hour", "end-hour"],
        ]);
    } else if (!$query_data["weekday"]) {
        echo json_encode([
            "statusCode" => 400,
            "message" => "Incorrect weekday",
            "fields" => ["weekday"],
        ]);
    } else if (!$query_data["room"]) {
        echo json_encode([
            "statusCode" => 400,
            "message" => "Incorrect room number",
            "fields" => ["room"],
        ]);
    }
}
