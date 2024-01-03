<?php
require_once "../db-connection.php";
require_once "../utils.php";

session_start();
$conn = db_connection();

$data = [
    "client-id" => $_SESSION["id"] ?? null,
    "activity-id" => $_GET["id"] ?? null,
];
$validated_data = filter_var_array($data, FILTER_VALIDATE_INT);
if (in_array(null, $validated_data)) {
    if (!$validated_data["client-id"]) {
        response(401, [
            "message" => "Unauthorized access",
        ]);
    } else if (!$validated_data["activity-id"]) {
        response(400, [
            "message" => "Missing activity id",
        ]);
    }
}

$params = array_values([...$validated_data]);
try {
    perform_query($conn, "DELETE FROM ActivitiesMembers WHERE MemberID = ? AND ActivityID = ?", $params, "ss");
} catch (mysqli_sql_exception $e) {
    response(500, [
        "message" => "Server internal error",
    ]);
}

response(204);
