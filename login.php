<?php
include 'src/actions/login-action.php';

session_start();
if (isset($_SESSION['id'])) {
    header('Location: ' . INDEX_PAGE);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/login/styles.css">
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
    <title>FitSphere - Login</title>
</head>
<body>
<form class="form-container" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
    <h2>Sign in</h2>
    <input id="email" name="email" type="email" placeholder="Email" required/>
    <input id="password" name="password" type="password" placeholder="Password" required/>
    <div id="submit">
        <button type="submit">Sign in</button>
        <?php if (isset($error)) echo $error; ?>
        <p>Don't have an account? <a href="register.php">Sign up</a></p>
    </div>
</form>
<script src="assets/js/login.js"></script>
</body>
</html>
