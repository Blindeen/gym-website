<?php
require_once "../utils.php";
require_once "../constants.php";

["GUEST" => $guest, "INDEX_PAGE" => $index] = CONSTANTS;
private_route($guest, $index);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="../components/styles.css">
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon.ico">
    <title>FitSphere - Login</title>
</head>
<body>
<form class="form-container" action="login.php" method="POST">
    <h2>Sign in</h2>
    <div class="field">
        <label for="email">Email</label>
        <input id="email" name="email" type="text"/>
    </div>
    <div class="field">
        <label for="password">Password</label>
        <input id="password" name="password" type="password"/>
    </div>
    <div id="submit">
        <button type="submit">Sign in</button>
        <p>Don't have an account? <a href="../register/index.php">Sign up</a></p>
        <p id="form-message"></p>
    </div>
</form>
<script src="scripts.js"></script>
</body>
</html>
