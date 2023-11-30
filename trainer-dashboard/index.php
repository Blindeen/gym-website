<?php
require_once '../utils.php';
require_once '../db-connection.php';

$conn = db_connection();

['TRAINER' => $trainer, 'INDEX_PAGE' => $index] = CONSTANTS;
private_route($trainer, $index);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="../components/styles.css">
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon.ico">
    <title>FitSphere - Trainer dashboard</title>
</head>
<body>
<?php require_once '../components/header/index.php'; ?>
<main>
    <div id="dashboard">
        <div class="pane">
            <h2>My activities</h2>
            <?php
            require_once '../components/table/index.php';
            $id = $_SESSION['id'];
            $query = "SELECT Activities.ID, Name, DayOfWeek, TIME_FORMAT(StartTime, '%H:%i'), TIME_FORMAT(EndTime, '%H:%i'), RoomNumber FROM Activities
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
            $columns = ['Name', 'Weekday', 'Start time', 'End time', 'Room', 'Action'];
            $page = (!isset($_GET['page']) ? 1 : intval($_GET['page']));
            $per_page = 3;
            $pagination_query = "SELECT COUNT(*) FROM Activities WHERE TrainerID=$id";
            table($conn, $columns, $query, $page, $per_page, $pagination_query);
            ?>
        </div>
        <div id="form-wrapper">
            <h2>Add new activity</h2>
            <form action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
                <div class="field">
                    <label for="activity-name">Activity name</label>
                    <input id="activity-name" name="activity-name" type="text" required/>
                </div>
                <div class="form-row">
                    <div class="field">
                        <label for="start-hour">Start hour</label>
                        <input id="start-hour" name="start-hour" type="time" min="06:00" max="21:00" required/>
                    </div>
                    <div class="field">
                        <label for="end-hour">End hour</label>
                        <input id="end-hour" name="end-hour" type="time" min="06:00" max="21:00" required/>
                    </div>
                </div>
                <div class="form-row">
                    <div class="field">
                        <label for="weekday">Weekday</label>
                        <select id="weekday" name="weekday">
                            <option selected hidden></option>
                            <option value="Monday">Monday</option>
                            <option value="Tuesday">Tuesday</option>
                            <option value="Wednesday">Wednesday</option>
                            <option value="Thursday">Thursday</option>
                            <option value="Friday">Friday</option>
                        </select>
                    </div>
                    <div class="field">
                        <label for="room">Room</label>
                        <select id="room" name="room">
                            <option selected hidden></option>
                            <?php
                            $result = $conn->query('SELECT * FROM Rooms');
                            while ($row = $result->fetch_array(MYSQLI_ASSOC)):
                                ?>

                                <option value="<?php echo $row['ID']; ?>">
                                    <?php echo $row['RoomNumber']; ?>
                                </option>

                            <?php
                            endwhile;
                            ?>
                        </select>
                    </div>
                </div>
                <button type="submit">Add</button>
            </form>
        </div>
    </div>
</main>
</body>
</html>