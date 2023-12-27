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
    }
}
