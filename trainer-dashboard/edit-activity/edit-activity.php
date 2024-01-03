<?php
require_once "../../db-connection.php";
require_once "../../utils.php";

session_start();
$conn = db_connection();

$data = [
    "trainer-id" => $_SESSION["id"] ?? null,
    "activity-id" => $_GET["id"] ?? null,
];
$validated_data = filter_var_array($data, FILTER_VALIDATE_INT);

if (in_array(null, $validated_data)) {
    if (!$validated_data["trainer-id"]) {
        response(401, [
            "message" => "Unauthorized access",
        ]);
    } else if (!$validated_data["activity-id"]) {
        response(400, [
            "message" => "Missing activity id",
        ]);
    }
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

if (in_array(null, $query_data) || ($end - $start) <= 0) {
    $response = [];
    if (!$query_data["activity-name"]) {
        $response["message"] = "Minimum 2 letters and first capitalized";
        $response["fields"] = ["activity-name"];
    } else if (($end - $start) <= 0) {
        $response["message"] = "End time has to be greater than start time";
        $response["fields"] = ["start-hour", "end-hour"];
    } else if (!$query_data["weekday"]) {
        $response["message"] = "Incorrect weekday";
        $response["fields"] = ["weekday"];
    } else if (!$query_data["room"]) {
        $response["message"] = "Incorrect room number";
        $response["fields"] = ["room"];
    }

    response(400, $response);
}

$query = "UPDATE Activities SET Name=?, DayOfWeek=?, StartTime=?, EndTime=?, RoomID=? WHERE ID=? AND TrainerID=?";
try {
    perform_query($conn, $query, array_values([...$query_data, $activity_id, $trainer_id]), "sssssss");
} catch (mysqli_sql_exception $exception) {
    response(500, [
        "message" => "Server internal error",
    ]);
}

response(201, [
    "message" => "Activity has been updated",
]);
