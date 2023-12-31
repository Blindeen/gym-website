<?php
require_once "../utils.php";
require_once "../db-connection.php";
require_once "../constants.php";

session_start();
$conn = db_connection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $validated_id = filter_var($_SESSION["id"] ?? null, FILTER_VALIDATE_INT);
    if (!$validated_id) {
        exit(CONSTANTS["SERVER_ERROR_MESSAGE"]);
    }

    $serializer = [
        "activity-name" => [
            "filter" => FILTER_VALIDATE_REGEXP,
            "options" => ["regexp" => "/[A-Z][a-zA-Z]+/"],
        ],
        "start-hour" => FILTER_FLAG_NONE,
        "end-hour" => FILTER_FLAG_NONE,
        "weekday" => [
            "filter" => FILTER_CALLBACK,
            "options" => "correct_weekday",
        ],
        "room" => [
            "filter" => FILTER_CALLBACK,
            "options" => "correct_room",
        ],
    ];
    $query_data = filter_var_array($_POST, $serializer);

    $start = date_create($query_data["start-hour"])->getTimestamp();
    $end = date_create($query_data["end-hour"])->getTimestamp();

    if (!in_array(null, $query_data) && ($end - $start) > 0) {
        $query = "INSERT INTO Activities (Name, StartTime, EndTime, DayOfWeek, RoomID, TrainerID) VALUES (?, ?, ?, ?, ?, ?)";
        $params = array_values([...$query_data, $_SESSION["id"]]);

        try {
            perform_query($conn, $query, prepare_data($params), "ssssss");
        } catch (mysqli_sql_exception $exception) {
            exit(CONSTANTS["SERVER_ERROR_MESSAGE"]);
        }

        echo json_encode([
            "statusCode" => 201,
            "message" => "Activity has been added",
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
}
