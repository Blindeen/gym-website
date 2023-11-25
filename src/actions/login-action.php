<?php
require_once 'src/db-connection.php';
require_once 'src/utils.php';

$conn = db_connection();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $user_info = null;
    $role = null;
    $error = null;

    try {
        $result = perform_query($conn, 'SELECT ID, Password, RoleID FROM Members WHERE Email = ?', [$email], 's');
        $user_info = $result->fetch_assoc();

        if (isset($user_info)) {
            $result = perform_query($conn, 'SELECT Name FROM Roles WHERE ID = ?', [$user_info['RoleID']], 's');
            $role = $result->fetch_assoc();
        }
    } catch (mysqli_sql_exception $exception) {
        exit(CONSTANTS['SERVER_ERROR_MESSAGE']);
    }

    if (isset($user_info) and isset($role)) {
        if (password_verify($password, $user_info['Password'])) {
            session_start();
            $_SESSION['id'] = $user_info['ID'];
            $_SESSION['role'] = $role['Name'];

            header('Location: ' . CONSTANTS['INDEX_PAGE']);
        } else {
            $error = '<p class="error">Incorrect password</p>';
        }
    } else {
        $error = '<p class="error">User not found</p>';
    }
}
