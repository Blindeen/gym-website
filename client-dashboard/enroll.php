<?php
require_once "../utils.php";
require_once "../db-connection.php";
require_once "../constants.php";

session_start();
$conn = db_connection();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $validated_id = filter_var($_SESSION["id"] ?? null, FILTER_VALIDATE_INT);
    if (!$validated_id) {
        response(401, [
            "message" => "Unauthorized access",
        ]);
    }

    $activity_id = intval($_POST["activity"]);
    $activity_id = filter_var($activity_id, FILTER_VALIDATE_INT);
    if (!$activity_id) {
        response(400, [
            "message" => "Activity ID must be a number",
            "fields" => ["activity"],
        ]);
    }

    $query = "SELECT ID FROM Activities";
    try {
        $result = $conn->query($query);
        $activities = $result->fetch_all();
        if (!in_array($activity_id, array_column($activities, 0))) {
            response(404, [
                "message" => "Activity does not exist",
                "fields" => ["activity"],
            ]);
        }
    } catch (mysqli_sql_exception $e) {
        response(500, [
            "message" => "Server internal error",
        ]);
    }

    $query = "INSERT INTO ActivitiesMembers (MemberID, ActivityID) VALUES (?, ?)";
    $params = array_values([$_SESSION["id"], $activity_id]);
    try {
        perform_query($conn, $query, $params, "ss");
    } catch (mysqli_sql_exception $e) {
        if ($e->getCode() == CONSTANTS["DUPLICATE_ERROR"]) {
            response(409, [
                "message" => "Already enrolled for this activity",
                "fields" => ["activity"],
            ]);
        }
        response(500, [
            "message" => "Server internal error",
        ]);
    }

    response(204);
}
