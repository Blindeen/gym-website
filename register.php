<?php
include "db-connection.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/register/styles.css">
    <link rel="icon" type="image/x-icon" href="assets/img/favicon.ico">
    <title>FitSphere - Register</title>
</head>
<body>
<form class="form-container" action="register-action.php" method="POST">
    <h2>Sign up</h2>
    <div class="form-row">
        <input type="text" name="first-name" placeholder="First name"
               minlength="2"/>
        <input type="text" name="last-name" placeholder="Last name"
               minlength="2"/>
    </div>
    <input type="date" name="birthdate"/>
    <select id="role" name="role">
        <option id="placeholder" selected hidden>Select a role</option>
        <?php
        $result = $conn->query("SELECT * FROM Roles");
        while ($row = $result->fetch_array(MYSQLI_ASSOC)):
            ?>

            <option value="<?php echo $row["ID"]; ?>">
                <?php echo $row["Name"]; ?>
            </option>

        <?php
        endwhile;
        ?>
    </select>
    <select id="payment-method" name="payment-method">
        <option id="placeholder" selected hidden>Payment Methods</option>
        <?php
        $result = $conn->query("SELECT * FROM PaymentMethods");
        while ($row = $result->fetch_array(MYSQLI_ASSOC)):
            ?>

            <option value="<?php echo $row["ID"]; ?>">
                <?php echo $row["Type"]; ?>
            </option>

        <?php
        endwhile;
        ?>
    </select>
    <div class="form-row">
        <input id="email" name="email" type="email" placeholder="Email"/>
        <input id="phone-number" name="number" type="tel"
               placeholder="Phone number">
    </div>
    <input id="password" name="password" type="password"
           placeholder="Password"/>
    <div id="confirm-password-error" class="error"></div>
    <div>
        <button type="submit">Sign up</button>
        <p>Have an account? <a href="login.php">Sign in</a></p>
    </div>
</form>
</body>
<script src="assets/js/register.js"></script>
</html>
