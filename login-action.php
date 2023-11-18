<?php
require_once 'db-connection.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    $result = null;
    $user_info = null;
    $role = null;
    $error = null;

    try {
        $stm = $conn->prepare('SELECT ID, Password, RoleID FROM Members WHERE Email = ?');
        $stm->bind_param('s', $email);
        $stm->execute();
        $result = $stm->get_result();
        $user_info = $result->fetch_assoc();

        if (isset($user_info)) {
            $stm = $conn->prepare('SELECT Name FROM Roles WHERE ID = ?');
            $stm->bind_param('s', $user_info['RoleID']);
            $stm->execute();
            $role = $stm->get_result()->fetch_assoc();
        }
    } catch (mysqli_sql_exception $exception) {
        die('Execution failed: ' . $exception->getMessage());
    }

    if (isset($user_info) and isset($role)) {
        if (password_verify($password, $user_info['Password'])) {
            session_start();
            $_SESSION['id'] = $user_info['ID'];
            $_SESSION['role'] = $role['Name'];
            header('Location: ' . INDEX_PAGE);
        } else {
            $error = '<p class="error">Incorrect password</p>';
        }
    } else {
        $error = '<p class="error">User not found</p>';
    }
}
