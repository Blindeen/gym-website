<?php
require_once "../utils.php";
require_once "../db-connection.php";
require_once "../components/header/index.php";

$conn = db_connection();
["CLIENT" => $client, "INDEX_PAGE" => $index] = CONSTANTS;
private_route([$client], $index);
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
                        <?php require_once "fetch-activities.php" ?>
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