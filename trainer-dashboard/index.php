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
    <link rel="stylesheet" href="../assets/css/components/styles.css">
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon.ico">
    <title>FitSphere - Trainer dashboard</title>
</head>
<body>
<?php require_once '../header.php' ?>
<main>
    <div id="dashboard">
        <div class="pane">
            <form>
                <h2>Your group activities</h2>
                <div class="field">
                    <label for="activity">Activity</label>
                    <select id="activity" name="activity">
                        <option selected hidden></option>
                        <?php
                        $result = $conn->query('SELECT * FROM Activities');
                        while ($row = $result->fetch_array(MYSQLI_ASSOC)):
                            ?>

                            <option value="<?php echo $row['ID']; ?>">
                                <?php echo $row['Type']; ?>
                            </option>

                        <?php
                        endwhile;
                        ?>
                    </select>
                </div>
            </form>
        </div>
        <div class="pane">
            <form>
                <h2>Add new activity</h2>
                <div class="field">
                    <label for="activity-name">Activity name</label>
                    <input id="activity-name" name="activity-name" type="text" required/>
                </div>
                <div class="form-row">
                    <div class="field">
                        <label for="start-hour">Start hour</label>
                        <input id="start-hour" name="start-hour" type="time" required/>
                    </div>
                    <div class="field">
                        <label for="start-hour">End hour</label>
                        <input id="start-hour" name="start-hour" type="time" required/>
                    </div>
                </div>
                <div class="field">
                    <label for="room">Room number</label>
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
                <button>Add</button>
            </form>
        </div>
    </div>
</main>
</body>
</html>