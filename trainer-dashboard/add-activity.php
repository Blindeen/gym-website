<?php
require_once "../utils.php";
require_once "../db-connection.php";

session_start();
$conn = db_connection();

function correct_weekday(string $value): string|null
{
    return in_array($value, ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"]) ? $value : null;
}

function correct_room(string $value): string|null
{
    if (!is_numeric($value)) {
        return null;
    }

    $conn = db_connection();
    $result = $conn->query("SELECT ID FROM Rooms");

    $identifiers = [];
    while ($row = $result->fetch_row()):
        $identifiers[] = $row[0];
    endwhile;

    $conn->close();

    return in_array($value, $identifiers) ? $value : null;
}

$errors = array(
    "activity-name" => null,
    "time" => null,
    "weekday" => null,
    "room" => null,
);
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $serializer = array(
        "activity-name" => array(
            "filter" => FILTER_VALIDATE_REGEXP,
            "options" => array("regexp" => "/[A-Z][a-zA-Z]+/"),
        ),
        "start-hour" => FILTER_FLAG_NONE,
        "end-hour" => FILTER_FLAG_NONE,
        "weekday" => array(
            "filter" => FILTER_CALLBACK,
            "options" => "correct_weekday",
        ),
        "room" => array(
            "filter" => FILTER_CALLBACK,
            "options" => "correct_room",
        ),
    );

    $serialized_data = filter_var_array($_POST, $serializer);

    $start = date_create($serialized_data["start-hour"])->getTimestamp();
    $end = date_create($serialized_data["end-hour"])->getTimestamp();

    if (!in_array(null, $serialized_data) && ($end - $start) > 0) {
        $query = "INSERT INTO Activities (Name, StartTime, EndTime, DayOfWeek, RoomID, TrainerID) VALUES (?, ?, ?, ?, ?, ?)";
        $params = array(
            $serialized_data["activity-name"],
            $serialized_data["start-hour"],
            $serialized_data["end-hour"],
            $serialized_data["weekday"],
            $serialized_data["room"],
            $_SESSION["id"]
        );
        try {
            perform_query($conn, $query, $params, "ssssss");
        } catch (mysqli_sql_exception $exception) {

        }
    } else {
        if (!$serialized_data["activity-name"]) {
            $errors["activity-name"] = "<p class='error'>Minimum 2 letters and first capitalized</p>";
        }

        if (($end - $start) <= 0) {
            $errors["time"] = "<p class='error'>End time has to be greater than start time</p>";
        }

        if (!$serialized_data["weekday"]) {
            $errors["weekday"] = "<p class='error'>Incorrect weekday</p>";
        }

        if (!$serialized_data["room"]) {
            $errors["room"] = "<p class='error'>Incorrect room number</p>";
        }
    }
}
