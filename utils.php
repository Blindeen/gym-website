<?php
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

    $role = null;
    if (isset($_SESSION['role'])) {
        $role = $_SESSION['role'];
    }
    $authorization = ($expected_account_type == $role);
    if (!$authorization) header('Location: ' . $redirect_path);
}
