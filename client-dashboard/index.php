<?php
require_once "../utils.php";
require_once "../db-connection.php";
require_once "../components/header/index.php";

$conn = db_connection();
["CLIENT" => $client, "INDEX_PAGE" => $index] = CONSTANTS;
private_route($client, $index);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="../components/styles.css">
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon.ico">
    <title>FitSphere - Client dashboard</title>
</head>
<body>
<?php navbar(); ?>
<main>
    <div id="dashboard">
        <div id="table-pane">
            <h2>My weekly activities </h2>
            <?php require_once "client-table.php"; ?>
        </div>
        <div id="form-wrapper">
            <form id="enrollment-form" action="enroll.php" method="POST">
                <div class="field">
                    <label for="activity">Activity</label>
                    <select id="activity" name="activity">
                        <option selected hidden></option>
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
                                    WHERE Activities.ID NOT IN (
                                    SELECT ActivityID FROM ActivitiesMembers WHERE MemberID = $user_id)
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
                            ?>
                            <option value="<?php echo $row["ID"]; ?>">
                                <?php
                                $row["StartTime"] = date("H:i", strtotime($row["StartTime"]));
                                $values = array_values(array_slice($row, 1));
                                echo sprintf("%s | %s | %s | %s %s", ...$values);
                                ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <button type="submit">Enroll</button>
            </form>
            <p id="form-message"></p>
        </div>
    </div>
</main>
<script type="module" src="scripts.js"></script>
</body>
</html>