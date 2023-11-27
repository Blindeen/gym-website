<?php
require_once '../utils.php';
require_once 'action.php';

['GUEST' => $guest, 'INDEX_PAGE' => $index] = CONSTANTS;
private_route($guest, $index);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="../assets/css/components/styles.css">
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon.ico">
    <title>FitSphere - Register</title>
</head>
<body>
<form class="form-container" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" method="POST">
    <h2>Sign up</h2>
    <div class="form-row">
        <div class="field">
            <label for="first-name">First name</label>
            <input id="first-name" type="text" name="first-name" placeholder="Josh" minlength="2" pattern="[a-z,A-Z,\s]{2,}" required/>
        </div>
        <div class="field">
            <label for="last-name">Last name</label>
            <input id="last-name" type="text" name="last-name" placeholder="Price" minlength="2" pattern="[a-z,A-Z,\s,-]{2,}" required/>
        </div>
    </div>
    <div class="form-row">
        <div class="field">
            <label for="email">Email</label>
            <input id="email" name="email" type="email" placeholder="josh.price@email.com" required/>
        </div>
        <div class="field">
            <label for="phone-number">Phone number</label>
            <input id="phone-number" name="phone-number" type="tel" placeholder="123 456 789" pattern="[0-9]{9]" required>
        </div>
    </div>
    <div class="field">
        <label for="address-line">Address</label>
        <input id="address-line" type="text" name="address-line" placeholder="50 Oakland Ave" required/>
    </div>
    <div class="form-row">
        <div class="field">
            <label for="zip-code">Zip code</label>
            <input id="zip-code" type="text" name="zip-code" placeholder="12-34" pattern="[0-9]{2,}-[0-9]{2,}"
                   required/>
        </div>
        <div class="field">
            <label for="city">City</label>
            <input id="city" type="text" name="city" placeholder="New York" required/>
        </div>
    </div>
    <div class="field">
        <label for="birthdate">Birthdate</label>
        <input id="birthdate" type="date" name="birthdate" pattern="[0-9]{4}-[0-9]{2}-[0-9]{2}"
               max=<?php echo date("Y-m-d") ?> required/>
    </div>
    <div class="form-row">
        <div class="field">
            <label for="payment-method">Payment method</label>
            <select id="payment-method" name="payment-method">
                <option selected hidden></option>
                <?php
                $result = $conn->query('SELECT * FROM PaymentMethods');
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
        <div class="field">
            <label for="pass">Pass</label>
            <select id="pass" name="pass">
                <option selected hidden></option>
                <?php
                $result = $conn->query('SELECT * FROM Passes');
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
    </div>
    <div class="field">
        <label for="password">Password</label>
        <input id="password" name="password" type="password" minlength="8" required/>
    </div>
    <div id="submit">
        <button type="submit">Sign up</button>
        <?php if (isset($error)) echo $error; ?>
        <p>Have an account? <a href="../login/index.php">Sign in</a></p>
    </div>
</form>
</body>
<script src="scripts.js"></script>
</html>
