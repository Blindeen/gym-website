<?php
require_once "../components/table/index.php";
require_once "../constants.php";

["SERVER_ERROR_MESSAGE" => $error, "PER_PAGE" => $per_page] = CONSTANTS;

$validated_id = filter_var($_SESSION["id"] ?? null, FILTER_VALIDATE_INT);
if (!$validated_id) {
    exit($error);
}

$columns = ["Name", "Weekday", "Start time", "End time", "Room", "Action"];
$action = fn($row) => "<td class='action'>
<a class='button remove-button' data-id='$row[0]'>Unsubscribe</a>
</td>";
$query = "SELECT
            Activities.ID,
            Name,
            DayOfWeek,
            TIME_FORMAT(StartTime, '%H:%i'),
            TIME_FORMAT(EndTime, '%H:%i'),
            RoomNumber FROM Members
                INNER JOIN ActivitiesMembers ON Members.ID = ActivitiesMembers.MemberID
                INNER JOIN Activities ON ActivitiesMembers.ActivityID = Activities.ID
                INNER JOIN Rooms ON Activities.RoomID = Rooms.ID
                WHERE Members.ID = $validated_id
                ORDER BY
                    CASE DayOfWeek
                        WHEN 'Monday' THEN 1
                        WHEN 'Tuesday' THEN 2
                        WHEN 'Wednesday' THEN 3
                        WHEN 'Thursday' THEN 4
                        WHEN 'Friday' THEN 5
                    END, StartTime";

$pagination_query = "SELECT COUNT(*) FROM Members
                        INNER JOIN ActivitiesMembers ON Members.ID = ActivitiesMembers.MemberID 
                        WHERE Members.ID = $validated_id";
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
