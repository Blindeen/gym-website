<?php
require_once 'src/constants.php';

function perform_query(mysqli $conn, string $query, array $params, string $types): false|mysqli_result
{
    $stm = $conn->prepare($query);
    $stm->bind_param($types, ...$params);
    if (!$stm->execute()) {
        throw new mysqli_sql_exception($stm->error, $stm->errno);
    }

    return $stm->get_result();
}

function private_route(string|null $expected_account_type, string $redirect_path): void
{
    if (!isset($_SESSION)) {
        session_start();
    }

    $id = null;
    if (isset($_SESSION['id'])) {
        $id = $_SESSION['id'];
    }
    $authorization = ($expected_account_type == $id);
    if (!$authorization) header('Location: ' . $redirect_path);
}
