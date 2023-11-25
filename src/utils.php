<?php

function perform_query($conn, $query, $params, $types) {
    $stm = $conn->prepare($query);
    $stm->bind_param($types, ...$params);
    if (!$stm->execute()) {
        throw new mysqli_sql_exception($stm->error, $stm->errno);
    }

    return $stm->get_result();
}
