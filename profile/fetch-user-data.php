<?php
require_once "../utils.php";
require_once "../db-connection.php";

session_start();
$conn = db_connection();

$validated_id = filter_var($_SESSION["id"] ?? null, FILTER_VALIDATE_INT);
if (!$validated_id) {
    response(401, [
        "message" => "Unauthorized access",
    ]);
}

$query = "SELECT
            FirstName,
            LastName,
            Email,
            PhoneNumber,
            AddressLine,
            PostalCode,
            City,
            Birthdate,
            PaymentMethodID,
            PassID FROM Members
            INNER JOIN AddressesContacts ON Members.ID = AddressesContacts.MemberID
            WHERE Members.ID = $validated_id";
$row = null;
try {
    $result = $conn->query($query);
    $row = $result->fetch_row();
} catch (mysqli_sql_exception $e) {
    response(500, [
        "message" => "Server internal error",
    ]);
}

$user_data = [];
array_push($user_data, ...$row);

response(200, [
    "userData" => $user_data,
]);
