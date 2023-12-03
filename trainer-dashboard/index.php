<?php
require_once "../utils.php";
require_once "../db-connection.php";
require_once "add-activity.php";

["TRAINER" => $trainer, "INDEX_PAGE" => $index] = CONSTANTS;
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
<?php require_once "../components/header/index.php"; ?>
<main>
    <div id="dashboard">
        <div class="pane">
            <h2>My group activities</h2>
            <?php require_once "trainer-table.php"; ?>
        </div>
        <div id="form-wrapper">
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                <div class="field">
                    <label for="activity-name">Activity name</label>
                    <input id="activity-name" name="activity-name" type="text" required/>
                    <?php if (isset($errors["activity-name"])) echo $errors["activity-name"] ?>
                </div>
                <div id="form-row-wrapper">
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
                    <?php if (isset($errors["time"])) echo $errors["time"] ?>
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
                        <?php if (isset($errors["weekday"])) echo $errors["weekday"] ?>
                    </div>
                    <div class="field">
                        <label for="room">Room</label>
                        <select id="room" name="room">
                            <option selected hidden></option>
                            <?php
                            $result = $conn->query("SELECT * FROM Rooms");
                            while ($row = $result->fetch_array(MYSQLI_ASSOC)):
                            ?>

                                <option value="<?php echo $row["ID"]; ?>">
                                    <?php echo $row["RoomNumber"]; ?>
                                </option>

                            <?php
                            endwhile;
                            ?>
                        </select>
                        <?php if (isset($errors["room"])) echo $errors["room"]?>
                    </div>
                </div>
                <button type="submit">Add</button>
            </form>
        </div>
    </div>
</main>
</body>
</html>