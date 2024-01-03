<?php
$user_id = $_SESSION["id"];
$result = $conn->query(
    "SELECT
                 Activities.ID,
                 Name,
                 DayOfWeek,
                 StartTime,
                 FirstName,
                 LastName FROM Activities INNER JOIN Members on Activities.TrainerID = Members.ID
                 WHERE Activities.ID NOT IN (SELECT ActivityID FROM ActivitiesMembers WHERE MemberID = $user_id)
                 ORDER BY
                       CASE DayOfWeek
                            WHEN 'Monday' THEN 1
                            WHEN 'Tuesday' THEN 2
                            WHEN 'Wednesday' THEN 3
                            WHEN 'Thursday' THEN 4
                            WHEN 'Friday' THEN 5
                       END, StartTime"
);
while ($row = $result->fetch_array(MYSQLI_ASSOC)):
    $id = $row["ID"];
    $row["StartTime"] = date("H:i", strtotime($row["StartTime"]));
    $values = array_values(array_slice($row, 1));
    echo "<option value=$id>";
    echo sprintf("%s | %s | %s | %s %s", ...$values);
    echo "</option>";
endwhile;
