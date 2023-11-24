<?php
require_once 'src/db-connection.php';
require_once 'src/utils.php';

$conn = db_connection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $first_name = ucfirst(trim($_POST['first-name']));
    $last_name = ucfirst(trim($_POST['last-name']));
    $email = strtolower(trim($_POST['email']));
    $hashed_password = password_hash(trim($_POST['password']), PASSWORD_DEFAULT);
    $birthdate = $_POST['birthdate'];
    $pass = $_POST['pass'];
    $payment_method = $_POST['payment-method'];

    $phone_number = trim($_POST['phone-number']);
    $address_line = trim($_POST['address-line']);
    $city = ucfirst(trim($_POST['city']));
    $zip_code = trim($_POST['zip-code']);

    $error = null;
    $query = 'INSERT INTO Members (FirstName, LastName, Email, Password, Birthdate, PassID, PaymentMethodID) 
                VALUES (?, ?, ?, ?, ?, ?, ?)';
    $params = [$first_name, $last_name, $email, $hashed_password, $birthdate, $pass, $payment_method];
    try {
        perform_query($conn, $query, $params, 'sssssss');
    } catch (mysqli_sql_exception $exception) {
        error_log('Exception: ' . $exception->getMessage());
        if ($exception->getCode() == DUPLICATE_ERROR) {
            $error = '<p class="error">User already exists</p>';
            return;
        } else {
            exit(SERVER_ERROR_MESSAGE);
        }
    }

    try {
        $result = perform_query($conn, 'SELECT ID FROM Members WHERE Email = ?', [$email], 's');
        $member_data = $result->fetch_assoc();
    } catch (mysqli_sql_exception $exception) {
        error_log('Execution failed: ' . $exception->getMessage());
        exit(SERVER_ERROR_MESSAGE);
    }

    $query = 'INSERT INTO AddressesContacts (AddressLine, City, PostalCode, PhoneNumber, MemberID)
                VALUES (?, ?, ?, ?, ?)';
    try {
        $params = [$address_line, $city, $zip_code, $phone_number, $member_data['ID']];
        perform_query($conn, $query, $params, 'ssssi');
    } catch (mysqli_sql_exception $exception) {
        error_log('Execution failed: ' . $exception->getMessage());
        exit(SERVER_ERROR_MESSAGE);
    }

    session_start();
    $_SESSION['id'] = $member_data['ID'];
    $_SESSION['role'] = CLIENT_ROLE;
    header('Location: ' . INDEX_PAGE);
}
