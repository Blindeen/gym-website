<?php
require_once "../components/table/index.php";
require_once "../constants.php";

if (isset($_SESSION["id"])) {
    $id = $_SESSION["id"];
    $columns = ["Name", "Weekday", "Start time", "End time", "Room", "Action"];
    $action = "delete-activity.php?id=";
    $query = "SELECT
            Activities.ID,
            Name,
            DayOfWeek,
            TIME_FORMAT(StartTime, '%H:%i'),
            TIME_FORMAT(EndTime, '%H:%i'),
            RoomNumber FROM Activities
                INNER JOIN gym.Rooms R on Activities.RoomID = R.ID
                WHERE TrainerID = $id
                ORDER BY
                    CASE DayOfWeek
                        WHEN 'Monday' THEN 1
                        WHEN 'Tuesday' THEN 2
                        WHEN 'Wednesday' THEN 3
                        WHEN 'Thursday' THEN 4
                        WHEN 'Friday' THEN 5
                    END, StartTime";

    if (!is_numeric($_GET["page"]) && !is_null($_GET["page"])) {
        exit(CONSTANTS["SERVER_ERROR_MESSAGE"]);
    }
    $page = intval($_GET["page"] ?? 1);
    $per_page = 3;
    $pagination_query = "SELECT COUNT(*) FROM Activities WHERE TrainerID=$id";

    try {
        table($conn, $columns, $action, $query, $page, $per_page, true, $pagination_query);
    } catch (mysqli_sql_exception $exception) {
        error_log($exception->getMessage());
        exit(CONSTANTS["SERVER_ERROR_MESSAGE"]);
    }
}
