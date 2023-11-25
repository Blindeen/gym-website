<?php
require_once 'src/utils.php';
require_once 'src/actions/login-action.php';

['GUEST' => $guest, 'INDEX_PAGE' => $index] = CONSTANTS;
private_route($guest, $index);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/login/styles.css">
    <link rel="stylesheet" href="assets/css/components/styles.css">
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
    <title>FitSphere - Login</title>
</head>
<body>
<form class="form-container" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
    <h2>Sign in</h2>
    <div class="field">
        <label for="email">Email</label>
        <input id="email" name="email" type="email" required/>
    </div>
    <div class="field">
        <label for="password">Password</label>
        <input id="password" name="password" type="password" required/>
    </div>
    <div id="submit">
        <button type="submit">Sign in</button>
        <?php if (isset($error)) echo $error; ?>
        <p>Don't have an account? <a href="register.php">Sign up</a></p>
    </div>
</form>
<script src="assets/js/login.js"></script>
</body>
</html>
