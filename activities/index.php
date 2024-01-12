<?php
require_once "../components/header/index.php";

session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="../components/styles.css">
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon.ico">
    <title>FitSphere - Classes</title>
</head>
<body>
<?php navbar(); ?>
<main>
    <div id="classes-wrapper">
        <?php require_once "activities-table.php" ?>
    </div>
</main>
<script type="module" src="scripts.js"></script>
</body>
</html>

