<?php
require_once "../../utils.php";
require_once "../../db-connection.php";
require_once "../../constants.php";

session_start();
$conn = db_connection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $validated_id = filter_var($_SESSION["id"] ?? null, FILTER_VALIDATE_INT);
    if (!$validated_id) {
        response(401, [
            "message" => "Unauthorized access",
        ]);
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
            response(500, [
                "message" => "Server internal error",
            ]);
        }

        response(201, [
            "message" => "Activity has been added",
        ]);
    } else {
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
}
