<?php

use http\Env\Response;
use JetBrains\PhpStorm\NoReturn;

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

function prepare_data(array $data): array
{
    $result = [];
    foreach ($data as $el) {
        $result[] = htmlspecialchars(trim($el));
    }

    return $result;
}

function correct_weekday(string $value): string|null
{
    return in_array($value, ["Monday", "Tuesday", "Wednesday", "Thursday", "Friday"]) ? $value : null;
}

function correct_room(string $value): string|null
{
    if (!is_numeric($value)) {
        return null;
    }

    $conn = db_connection();
    $result = $conn->query("SELECT ID FROM Rooms");

    $identifiers = [];
    while ($row = $result->fetch_row()):
        $identifiers[] = $row[0];
    endwhile;

    $conn->close();

    return in_array($value, $identifiers) ? $value : null;
}

function response(int $status_code, array $response_message = null): void
{
    http_response_code($status_code);
    if ($response_message) {
        exit(json_encode($response_message));
    }

    exit;
}

